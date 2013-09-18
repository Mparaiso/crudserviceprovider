<?php

namespace Mparaiso\CodeGeneration\Service;

use Doctrine\ODM\MongoDB\DocumentManager;

class DoctrineMongoDBODMCRUDService implements ICRUDService {

    /**
     *
     * @var DocumentManager 
     */
    protected $dm;

    /**
     *
     * @var String
     */
    protected $className;

    public function __construct(DocumentManager $dm, $className) {
        $this->dm = $dm;
        $this->className = $className;
    }

    public function count(array $criteria = array()) {
        return $this->dm->getRepository($this->className)->findBy($criteria)->count();
    }

    public function delete($entity, $flush = 1) {
        $this->dm->remove($entity);
        if ($flush)
            $this->dm->flush();
    }

    public function find($id) {
        $this->dm->getRepository($this->className)->find($id);
    }

    public function findAll() {
        return $this->dm->getRepository($this->className)->findAll();
    }

    public function findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null) {
        return $this->dm->getRepository($this->className)->findBy($criteria, $orderBy, $limit, $offset);
    }

    public function findOneBy(array $criteria) {
        return $this->dm->getRepository($this->className)->findOneBy($criteria);
    }

    public function getClassName() {
        return $this->className;
    }

    public function save($entity, $flush = 1) {
        $this->dm->persist($entity);
        if ($flush)
            $this->dm->flush();
    }

    protected function supportClass($entity, $className) {
        if (!$entity instanceof $className) {
            $realClassName = get_class($entity);
            throw new \Exception("Class $realClassName unsupported by service");
        }
    }

}