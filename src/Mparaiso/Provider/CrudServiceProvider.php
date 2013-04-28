<?php

namespace Mparaiso\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * FR : fournit des outils pour la génération de crud
 * EN : provide tools for crud generation
 */
class CrudServiceProvider implements ServiceProviderInterface
{

    function __construct($namespace = "mp")
    {
        $this->ns = $namespace;
    }

    public function register(Application $app)
    {

    }

    public function boot(Application $app)
    {
        $app['twig.loader.filesystem']->addPath(
            __DIR__ . "/../CodeGeneration/Resources/templates/");

        $twigEnv = $app['twig'];
        /* @var $twigEnv \Twig_Environment */
        $twigEnv->addTest(new \Twig_SimpleTest("datetime", function ($value) {
            return $value instanceof \DateTime;
        }));
        $twigEnv->addTest(new \Twig_SimpleTest("scalar", "is_scalar"));
        $twigEnv->addTest(new \Twig_SimpleTest("to_string", function ($value) {
            return method_exists($value, "__toString");
        }));
    }
}