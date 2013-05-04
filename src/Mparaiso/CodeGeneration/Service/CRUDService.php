<?php

namespace Mparaiso\CodeGeneration\Service;


use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;

class CRUDService extends EntityRepository implements ICRUDService
{
    function count()
    {

        return $this->getEntityManager()->createQuery("SELECT COUNT(e) FROM " . $this->getEntityName() . " e ")->getSingleScalarResult();

    }

    function save($entity, $flush = TRUE)
    {
        $this->getEntityManager()->persist($entity);
        $flush AND $this->getEntityManager()->flush();
        return $entity;
    }

    function delete($entity, $flush = TRUE)
    {
        $this->getEntityManager()->remove($entity);
        $flush AND $this->getEntityManager()->flush();
        return $entity;
    }
}