<?php

namespace Mparaiso\CodeGeneration\Service;


use Doctrine\Common\Persistence\ObjectManager;

class CRUDService implements ICRUDService
{
    protected $em;
    protected $class;

    public function __construct(ObjectManager $em, $class)
    {
        $this->class = $class;
        $this->em = $em;
    }

    function count()
    {

        return count($this->em->getRepository($this->class)->findAll());

    }

    function save($entity, $flush = TRUE)
    {
        $this->em->persist($entity);
        $flush AND $this->em->flush();
        return $entity;
    }

    function delete($entity, $flush = TRUE)
    {
        $this->em->remove($entity);
        $flush AND $this->em->flush();
        return $entity;
    }

    /**
     * {@inheritdoc}
     */
    function find($id)
    {
        return $this->em->getRepository($this->class)->find($id);
    }

    /**
     * {@inheritdoc}
     */
    function findAll()
    {
        return $this->em->getRepository($this->class)->findAll();
    }

    /**
     * {@inheritdoc}
     */
    function findBy(array $criteria, array $orderBy = NULL, $limit = NULL, $offset = NULL)
    {
        return $this->em->getRepository($this->class)->findBy($criteria, $orderBy, $limit, $offset);
    }

    /**
     * {@inheritdoc}
     */
    function findOneBy(array $criteria, array $order = array())
    {
        return $this->em->getRepository($this->class)->findOneBy($criteria, $order);
    }

    /**
     * {@inheritdoc}
     */
    function getClassName()
    {
        return $this->class;
    }
}