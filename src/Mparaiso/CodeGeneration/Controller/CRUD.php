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
    var $templateLayout = 'layout.html.twig';
    var $entityClass;
    var $formClass;
    var $service;
    var $resourceName;
    var $collectionName;
    var $beforeCreateEvent;
    var $afterCreateEvent;
    var $beforeUpdateEvent;
    var $afterUpdateEvent;
    var $beforeDeleteEvent;
    var $afterDeleteEvent;
    var $indexTemplate = "crud/resource_index.html.twig";
    var $readTemplate = "crud/resource_read.html.twig";
    var $createTemplate = "crud/resource_create.html.twig";
    var $updateTemplate = "crud/resource_update.html.twig";
    var $deleteTemplate = "crud/resource_delete.html.twig";
    var $indexRoute;
    var $readRoute;
    var $createRoute;
    var $updateRoute;
    var $deleteRoute;
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
    var $countMethod = "count";
    var $userAware = FALSE;
    var $userEntityProperty = "user";
    var $createSuccessMessage = "%s with id %s was created successfully !";
    var $deleteSuccessMessage = "%s with id %s was deleted successfully !";
    var $updateSuccessMessage = "%s with id %s was updated successfully !";
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
        if (NULL === $this->indexRoute) {
            $this->indexRoute = "mp_crud_" . $this->resourceName . "_index";
        }
        if (NULL === $this->readRoute) {
            $this->readRoute = "mp_crud_" . $this->resourceName . "_read";
        }
        if (NULL === $this->createRoute) {
            $this->createRoute = "mp_crud_" . $this->resourceName . "_create";
        }
        if (NULL === $this->updateRoute) {
            $this->updateRoute = "mp_crud_" . $this->resourceName . "_update";
        }
        if (NULL === $this->deleteRoute) {
            $this->deleteRoute = "mp_crud_" . $this->resourceName . "_delete";
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
        $read = $controllers->match("/" . $this->resourceName . "/read/{id}", array($this, "$this->readCallback"))
            ->assert("id", '\d+')
            ->bind("{$this->resourceName}_{$this->readCallback}");
        // CREATE
        $controllers->match("/" . $this->resourceName . "/create", array($this, "$this->createCallback"))
            ->bind("{$this->resourceName}_{$this->createCallback}");
        // UPDATE
        $update = $controllers->match("/{$this->resourceName}/update/{id}", array($this, "$this->updateCallback"))
            ->assert("id", '\d+')
            ->bind("{$this->resourceName}_{$this->updateCallback}");
        // DELETE
        $delete = $controllers->post("/{$this->resourceName}/delete/{id}", array($this, "$this->deleteCallback"))
            ->assert("id", '\d+')
            ->bind("{$this->resourceName}_{$this->deleteCallback}");
        $controllers->match("/" . $this->resourceName, array($this, "$this->indexCallback"))
            ->bind("{$this->resourceName}_{$this->indexCallback}");
        if (TRUE === $this->userAware) {
            $read->before(array($this, "mustBeOwner"));
            $update->before(array($this, "mustBeOwner"));
            $delete->before(array($this, "mustBeOwner"));
        }
        return $controllers;
    }

    function index(Request $req, Application $app)
    {
        $limit = $this->limit;
        $queryoffset = (int)$req->query->get("offset", 0);
        $offset = $queryoffset * $limit;
        $resources = $this->service->{$this->indexMethod}(array(), array(), $limit, $offset);
        $total = $this->service->{$this->countMethod}();

        return $app["twig"]
            ->render($this->indexTemplate,
            array("resources"    => $resources, "resource_limit" => $limit,
                  "offset"       => $queryoffset,
                  "limit"        => $this->limit,
                  "total"        => $total,
                  "layout"       => $this->templateLayout,
                  "resourceName" => $this->resourceName,
                  "updateRoute"  => $this->updateRoute,
                  "deleteRoute"  => $this->deleteRoute,
                  "createRoute"  => $this->createRoute,
                  "readRoute"    => $this->readRoute
            ));
    }

    /**
     * FR : affiche une resource
     * EN : show a resource
     * @param unknown $id
     * @param Request $req
     * @param Application $app
     * @param unknown $format
     */
    function read($id, Request $req, Application $app)
    {
        $resource = $this->service->{$this->readMethod}($id);
        $resource === NULL AND $app->abort(404, "resource not found");

        $reflect = new \ReflectionClass($this->entityClass);
        $properties = array_map(function ($prop) {
            /* @var $prop \ReflectionProperty */
            return $prop->getName();
        }, $reflect->getProperties());
        return $app["twig"]
            ->render($this->readTemplate,
            array(
                "resourceName" => $this->resourceName,
                'resource'     => $resource,
                'properties'   => $properties,
                "layout"       => $this->templateLayout,
                "indexRoute"   => $this->indexRoute,
                "updateRoute"  => $this->updateRoute
            ));
    }

    /**
     * FR : Créer une resource
     * EN : create a resource
     * @param Application $app
     * @param Request $req
     * @param string $format
     */
    function create(Application $app, Request $req)
    {
        $resource = new $this->entityClass();
        /* @var $form Form */
        $form = $app["form.factory"]->create(new $this->formClass(), $resource);
        if ("POST" === $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $app['dispatcher']->dispatch($this->beforeCreateEvent, new GenericEvent($resource));
                $this->service->{$this->createMethod}($resource);
                $app['dispatcher']->dispatch($this->afterCreateEvent, new GenericEvent($resource));
                $app["session"]->getFlashBag()->add("info",
                    sprintf($this->createSuccessMessage, $this->resourceName,
                        $resource->getId()));
                return $app->redirect($app['url_generator']
                    ->generate($this->readRoute, array("id" => $resource->getId())));
            }
        }
        return $app["twig"]
            ->render($this->createTemplate, array(
            "resourceName" => $this->resourceName,
            "form"         => $form->createView(),
            "layout"       => $this->templateLayout,
            "indexRoute"   => $this->indexRoute
        ));
    }

    /**
     * FR : supprime une resource
     * EN : delete a resource
     * @param \Silex\Application $app
     * @param \Symfony\Component\HttpFoundation\Request $req
     * @param $format
     * @param $id
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    function delete(Application $app, Request $req, $format, $id)
    {
        $resource = $this->service->{$this->readMethod}($id);
        $resource === NULL AND $app->abort(404, "resource not found");
        if ("POST" === $req->getMethod()) {
            $app['dispatcher']->dispatch($this->beforeDeleteEvent, new GenericEvent($resource));
            $count = $this->service->{$this->deleteMethod}($resource);
            $app['dispatcher']->dispatch($this->afterDeleteEvent, new GenericEvent($resource));
            $app["session"]->getFlashBag()->set("info", sprintf($this->deleteSuccessMessage, $this->resourceName, $id));
            return $app->redirect($this->indexRoute);
        } else {
            return $app['twig']->render($this->deleteTemplate, array(
                "resourceName" => $this->resourceName,
                "resource"     => $resource,
                "layout"       => $this->templateLayout,
                "indexRoute"   => $this->indexRoute
            ));
        }


    }

    function update(Application $app, Request $req, $id)
    {
        $resource = $this->service->find($id);
        $resource === NULL AND $app->abort(404, "resource not found");
        /* @var $form \Symfony\Component\Form\Form */
        $form = $app["form.factory"]->create(new $this->formClass(), $resource);
        if ("POST" === $req->getMethod()) {
            $form->bind($req);
            if ($form->isValid()) {
                $app['dispatcher']->dispatch($this->beforeUpdateEvent, new GenericEvent($resource));
                $this->service->{$this->updateMethod}($resource);
                $app['dispatcher']->dispatch($this->afterUpdateEvent, new GenericEvent($resource));
                $app["session"]->getFlashBag()
                    ->add("info", sprintf($this->updateSuccessMessage,
                    $this->resourceName, $resource->getId()));
                return $app->redirect(
                    $app["url_generator"]
                        ->generate($this->readRoute,
                        array('id' => $resource->getId()))
                );
            }
        }
        return $app["twig"]
            ->render($this->updateTemplate,
            array('form'         => $form->createView(),
                  "resource"     => $resource,
                  "resourceName" => $this->resourceName,
                  "layout"       => $this->templateLayout,
                  "indexRoute"   => $this->indexRoute
            ));
    }


    function getCurrentUser(SecurityContext $security)
    {
        $user = $security->getToken()->getUser();

        return $user;
    }

}
