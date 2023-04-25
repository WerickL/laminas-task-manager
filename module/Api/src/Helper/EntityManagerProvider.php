<?php
namespace Api\Helper;

use PhpParser\Builder\Class_;

require_once __DIR__."/../../../../vendor/autoload.php";
use Psr\Container\ContainerInterface;
use Api\Helper\EntityManagerFactory;

class EntityManagerProvider{
    public function __invoke(ContainerInterface $container)
    {
        return new EntityManagerFactory();
    }
}