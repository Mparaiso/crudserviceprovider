<?php

namespace Mparaiso\CodeGeneration\Service;


use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityRepository;

class CRUDService extends EntityRepository
{
    function count()
    {

        return $this->getEntityManager()->createQuery("SELECT COUNT(e) FROM " . $this->getEntityName() . " e ")->getSingleScalarResult();

    }
}