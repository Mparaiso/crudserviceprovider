<?php

namespace Mparaiso\CodeGeneration\Service;

use Doctrine\Common\Persistence\ObjectRepository;

interface ICRUDService extends ObjectRepository
{
    function count();
}