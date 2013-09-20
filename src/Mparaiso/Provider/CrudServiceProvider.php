<?php

namespace Mparaiso\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * FR : fournit des outils pour la génération de crud
 * EN : provide tools for crud generation
 */
class CrudServiceProvider implements ServiceProviderInterface {


    public function register(Application $app) {

    }

    public function boot(Application $app) {
        $app['twig.loader.filesystem']->addPath(
            __DIR__ . "/../Crud/Resources/templates/");

        $twigEnv = $app['twig'];
        /* @var $twigEnv \Twig_Environment */
        $twigEnv->addTest(new \Twig_SimpleTest("datetime", function ($value) {
            return $value instanceof \DateTime;
        }));
        $twigEnv->addTest(new \Twig_SimpleTest("scalar", "is_scalar"));
        $twigEnv->addTest(new \Twig_SimpleTest("to_string", function ($value) {
            return method_exists($value, "__toString");
        }));

        $toString = function ($value) {
            if (method_exists($value, "__toString")) {
                return call_user_func(array($value, "__toString"));
            } elseif ($value instanceof \DateTime) {
                return $value->format("r");
            } elseif (is_scalar($value)) {
                return $value;
            } elseif ($value instanceof \ArrayAccess || is_array($value)) {
                $res = "{ ";
                foreach ($value as $val) {
                    $res .= (string)$val . " ";
                }
                return $res . "}";
            } else {
                return json_encode($value);
            }
        };
        $twigEnv->addFilter(new \Twig_SimpleFilter("toString", $toString));
    }
}