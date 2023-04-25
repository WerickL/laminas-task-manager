#!/usr/bin/env php
<?php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;
use Symfony\Component\Console\Event\ConsoleEvent;
use Application\Helper\EntityManagerFactory;
// replace with path to your own project bootstrap file
require_once __DIR__.'/vendor/autoload.php';

// replace with mechanism to retrieve EntityManager in your app
$entityManagerFactory = new EntityManagerFactory();
$entityManager= $entityManagerFactory->getEntityManager();

ConsoleRunner::run(
    new SingleManagerProvider($entityManager)
);
//return ConsoleRunner::createHelperSet($entityManager);