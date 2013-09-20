<?php


namespace Mparaiso\Crud\Service;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ODM\MongoDB\DocumentManager;

class DoctrineMongoDBODMCrudService implements ICRUDService
{
    /**
     * @var \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected $dm;
    /**
     * @var String
     */
    protected $className;

    function __construct(DocumentManager $dm, $className)
    {
        $this->dm = $dm;
        $this->className = $className;
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentRepository
     */
    protected function getRepository()
    {
        return $this->dm->getRepository($this->className);
    }

    function count(array $criteria = array())
    {
        return $this->getRepository()->findBy($criteria)->count();
    }

    function save($entity, $flush = 1)
    {
        $this->dm->persist($entity);
        if (1 == $flush) {
            $this->dm->flush($entity);
        }
    }

    function delete($entity, $flush = 1)
    {
        $this->dm->remove($entity);
        if (1 == $flush) {
            $this->dm->flush($entity);
        }
    }

    /**
     * Finds an object by its primary key / identifier.
     *
     * @param mixed $id The identifier.
     *
     * @return object The object.
     */
    public function find($id)
    {
        return $this->getRepository()->find($id);
    }

    /**
     * Finds all objects in the repository.
     *
     * @return array The objects.
     */
    public function findAll()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Finds objects by a set of criteria.
     *
     * Optionally sorting and limiting details can be passed. An implementation may throw
     * an UnexpectedValueException if certain values of the sorting or limiting details are
     * not supported.
     *
     * @param array      $criteria
     * @param array|null $orderBy
     * @param int|null   $limit
     * @param int|null   $offset
     *
     * @return array The objects.
     *
     * @throws \UnexpectedValueException
     */
    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
    {
        return $this->getRepository()->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * Finds a single object by a set of criteria.
     *
     * @param array $criteria The criteria.
     *
     * @return object The object.
     */
    public function findOneBy(array $criteria)
    {
        return $this->getRepository()->findOneBy($criteria);
    }

    /**
     * Returns the class name of the object managed by the repository.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }
}