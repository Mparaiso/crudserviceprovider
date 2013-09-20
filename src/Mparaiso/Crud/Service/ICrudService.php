<?php

namespace Mparaiso\CRUD\Service;

use Doctrine\Common\Persistence\ObjectRepository;

interface ICrudService extends ObjectRepository
{
    function count();

    function save($entity);

    function delete($entity);
}
