<?php

namespace Mparaiso\CodeGeneration\Controller;

use Silex\ControllerProviderInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use ReflectionClass;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\SecurityContext;
use Silex\Application;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Form;

/**
 * FR : classe générique pour un classique CRUD.
 * EN : generic class for classical CRUD operations
 * @author M.Paraiso
 * copyright 2013 M.Paraiso
 * contact mparaiso@online.fr
 */
class CRUD implements ControllerProviderInterface
{

    var $entityClass;
    var $formClass;
    var $serviceName;
    var $resourceName;
    var $collectionName;
    var $beforeCreateEvent;
    var $afterCreateEvent;
    var $beforeUpdateEvent;
    var $afterUpdateEvent;
    var $beforeDeleteEvent;
    var $afterDeleteEvent;
    /*var $indexTemplate;
    var $readTemplate;
    var $createTemplate;
    var $updateTemplate;
    var $deleteTemplate;*/
    var $indexCallback = "index";
    var $readCallback = "read";
    var $createCallback = "create";
    var $updateCallback = "update";
    var $deleteCallback = "delete";
    var $createMethod = "save";
    var $updateMethod = "save";
    var $deleteMethod = "delete";
    var $readMethod = "find";
    var $indexMethod = "findAll";
    var $userAware = FALSE;
    var $userEntityProperty = "user";
    var $createSuccessMessage = "%s with id %s was created";
    var $deleteSuccessMessage = "%s with id %s was deleted";
    var $updateSuccessMessage = "%s with id %s was updated";
    // pagination
    var $limit = 20;
    var $order = array();

    /**
     * FR : un crud de base pour éviter de recoder les fonctionnalités classiques d'une application <br/>
     * EN : a basic crud that implements basic web app functionalities on a resource ( READ , CREATE , UPDATE , DELETE )
     * values can be passed in the constructor. values are public;
     * possible values <br/>
     * var $entityClass;// the entity or model class <br/>
     * var $formClass; //the form type class<br/>
     * var $serviceName; // for each resource a service that knows how to persist the resource must be defined<br/>
     * for instance : $app["resource_service"]. the service is a class that extends EntityService<br/>
     * var $resourceName; // the name of the resource <br/>
     * var $collectionName; // a collection name for a list of resource, for the index template <br/>
     * var $indexCallback = "index"; // method of the class used for the index <br/>
     * var $readCallback = "read";// method of the class used for read operation<br/>
     * var $createCallback = "create";  // method of the class used for create operation<br/>
     * var $updateCallback = "update"; // method of the class used for udpate operation<br/>
     * var $deleteCallback = "delete";  // method of the class used for delete operation<br/>
     * var $createMethod = "save"; // method of the resource service used for create<br/>
     * var $updateMethod = "save";  // method of the resource service used for update<br/>
     * var $deleteMethod = "delete"; // method of the resource service used for delete<br/>
     * var $readMethod = "find"; // method of the resource service used to find a resource <br/>
     * var $indexMethod = "findAll"; // method of the resource service to find all resources <br/>
     * var $userAware = FALSE; // if true , the user has access only to the resources he created <br/>
     * var $userEntityProperty = "user";  <br/>
     * var $createSuccessMessage = "%s with id %s was created";<br/>
     * var $deleteSuccessMessage = "%s with id %s was deleted";<br/>
     * var $updateSuccessMessage = "%s with id %s was updated";<br/>
     * @param array $values
     */
    function __construct(array $values = array())
    {
        foreach ($values as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
        if (NULL === $this->collectionName)
            $this->collectionName = $this->resourceName . "Collection";
        if (NULL === $this->beforeCreateEvent) {
            $this->beforeCreateEvent = $this->resourceName . "_before_create";
        }

        if (NULL === $this->resourceName) throw new \Exception("resourceName cannot be null");

        if (NULL === $this->afterCreateEvent) {
            $this->afterCreateEvent = $this->resourceName . "_after_create";
        }
        if (NULL === $this->beforeUpdateEvent) {
            $this->beforeUpdateEvent = $this->resourceName . "_before_update";
        }
        if (NULL === $this->afterUpdateEvent) {
            $this->afterUpdateEvent = $this->resourceName . "_after_update";
        }
        if (NULL === $this->beforeDeleteEvent) {
            $this->beforeDeleteEvent = $this->resourceName + "_before_delete";
        }
        if (NULL === $this->afterDeleteEvent) {
            $this->afterDeleteEvent = $this->resourceName + "_after_delete";
        }
    }

    /**
     * FR : création des routes et de leurs callbacks respectives
     * EN : routes and callbacks creation to be appended to the app routes.
     * @param \Silex\Application $app
     * @return \Silex\ControllerCollection
     */
    function connect(Application $app)
    {
        /** @var $controllers \Silex\ControllerCollection */
        $controllers = $app["controllers_factory"];
        // READ
        $read = $controllers->match("/" . $this->resourceName . "/read/{id}/{format}", array($this, "$this->readCallback"))
            ->value("format", "html")
            ->bind("{$this->resourceName}_{$this->readCallback}");
        // CREATE
        $controllers->match("/" . $this->resourceName . "/create/{format}", array($this, "$this->createCallback"))
            ->value("format", "html")
            ->bind("{$this->resourceName}_{$this->createCallback}");
        // UPDATE
        $update = $controllers->match("/{$this->resourceName}/update/{id}/{format}", array($this, "$this->updateCallback"))
            ->value("format", "html")
            ->bind("{$this->resourceName}_{$this->updateCallback}");
        // DELETE
        $delete = $controllers->post("/{$this->resourceName}/delete/{id}/{format}", array($this, "$this->deleteCallback"))
            ->value("format", "html")->bind("{$this->resourceName}_{$this->deleteCallback}");
        $controllers->match("/" . $this->resourceName . "/{format}", array($this, "$this->indexCallback"))
            ->value("format", "html")
            ->assert('format', '\d+')
            ->bind("{$this->resourceName}_{$this->indexCallback}");
        if (TRUE === $this->userAware) {
            $read->before(array($this, "mustBeOwner"));
            $update->before(array($this, "mustBeOwner"));
            $delete->before(array($this, "mustBeOwner"));
        }
        return $controllers;
    }

    function index(Request $req, Application $app, $format)
    {
        $limit = $this->limit;
        $queryoffset = (int)$req->query->get("offset", 0);
        $offset = $queryoffset * $limit;

        if ($this->userAware === TRUE) {
            $user = $this->getCurrentUser($app["security"]);
            $resources = $app[$this->serviceName]->findAll(array($this->userEntityProperty => $user), array(), $limit, $offset);
        } else {
            $resources = $app[$this->serviceName]->findAll(array(), array(), $limit, $offset);
        }
        return $app["twig"]
            ->render("{$this->resourceName}_index.$format.twig",
            array("resources"       => $resources, "resource_limit" => $limit,
                  "resource_offset" => $queryoffset,
                  "resource_count"  => count($resources)));
    }

    /**
     * FR : affiche une resource
     * EN : show a resource
     * @param unknown $id
     * @param Request $req
     * @param Application $app
     * @param unknown $format
     */
    function read($id, Request $req, Application $app, $format)
    {
        $resource = $app[$this->serviceName]->{$this->readMethod}($id);
        $reflect = new \ReflectionClass($this->entityClass);
        $properties = array_map(function ($prop) {
            /* @var $prop \ReflectionProperty */
            return $prop->getName();
        }, $reflect->getProperties());
        return $app["twig"]
            ->render("{$this->resourceName}_read.$format.twig",
            array("resourceName" => $this->resourceName, 'resource' => $resource, 'properties' => $properties));
    }

    /**
     * FR : Créer une resource
     * EN : create a resource
     * @param Application $app
     * @param Request $req
     * @param string $format
     */
    function create(Application $app, Request $req, $format)
    {
        $resource = new $this->entityClass();
        /* @var $form Form */
        $form = $app["form.factory"]->create(new $this->formClass(), $resource);
        if ("POST" === $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $app['dispatcher']->dispatch($this->beforeCreateEvent, new GenericEvent($resource));
                $app[$this->serviceName]->{$this->createMethod}($resource);
                $app['dispatcher']->dispatch($this->afterCreateEvent, new GenericEvent($resource));
                $app["session"]->getFlashBag()->add("info",
                    sprintf($this->createSuccessMessage, $this->resourceName,
                        $resource->getId()));
                return $app->redirect($app['url_generator']
                    ->generate("{$this->resourceName}_read", array("id" => $resource->getId())));
            }
        }
        return $app["twig"]
            ->render("{$this->resourceName}_create.$format.twig", array("form" => $form->createView()));
    }

    function delete(Application $app, Request $req, $format, $id)
    {
        $resource = $app[$this->serviceName]->{$this->readMethod}($id);

        if ("POST" === $req->getMethod()) {
            $app['dispatcher']->dispatch($this->beforeDeleteEvent, new GenericEvent($resource));
            $count = $app[$this->serviceName]->{$this->deleteMethod}($resource);
            $app['dispatcher']->dispatch($this->afterDeleteEvent, new GenericEvent($resource));
            if ($count > 0) {
                $app["session"]->getFlashBag()->set("info", sprintf($this->deleteSuccessMessage, $this->resourceName, $id));
            } else {
                $app->abort(500, "cannot delete the resource");
            }
            return $app->redirect($app["url_generator"]->generate("{$this->resourceName}_index"));
        } else {
            return $app['twig']->render($this->resourceName . "_delete.$format.twig", array(
                "resourceName" => $this->resourceName,
                "resource"     => $resource,
            ));
        }


    }

    function update(Application $app, Request $req, $format, $id)
    {
        $resource = $app[$this->serviceName]->find($id);
        /* @var $form \Symfony\Component\Form\Form */
        $form = $app["form.factory"]->create(new $this->formClass(), $resource);
        if ("POST" === $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $app['dispatcher']->dispatch($this->beforeUpdateEvent, new GenericEvent($resource));
                $app[$this->serviceName]->{$this->updateMethod}($resource);
                $app['dispatcher']->dispatch($this->afterUpdateEvent, new GenericEvent($resource));
                $app["session"]->getFlashBag()
                    ->add("info", sprintf($this->updateSuccessMessage,
                    $this->resourceName, $resource->getId()));
                return $app->redirect(
                    $app["url_generator"]
                        ->generate("{$this->resourceName}_read",
                        array('id' => $resource->getId()))
                );
            }
        }
        return $app["twig"]
            ->render("{$this->resourceName}_update.$format.twig",
            array('form'         => $form->createView(),
                  "resource"     => $resource,
                  "resourceName" => $this->resourceName,
            ));
    }

    /**
     * FR : vérifie si un utilisateur est propriétaire d'une resource
     * sinon , renvoie une erreur.<br/>
     * EN : check if the current user own the resource before allowing a route callbackto be executed<br/>
     * @param \Symfony\Component\HttpFoundation\Request $req
     */
    function mustBeOwner(Request $req, Application $app)
    {
        $id = $req->query->get("id");
        if ($id) {
            $user = $this->getCurrentUser($app["security"]);
            $resource = $app[$this->serviceName]->findOne(array("id" => $id, $this->userEntityProperty => $user));
            if (!$resource) {
                return new Response("You cant access this resource", 403);
            }
        }
    }

    function getCurrentUser(SecurityContext $security)
    {
        $user = $security->getToken()->getUser();
        return $user;
    }

}
