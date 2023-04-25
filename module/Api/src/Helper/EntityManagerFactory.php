<?php
namespace Api\Helper;
require_once __DIR__."/../../../../vendor/autoload.php";
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dotenv\Dotenv;
use Psr\Container\ContainerAwareInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceManager;

use Laminas\ServiceManager\Factory\FactoryInterface;

class EntityManagerFactory
{
     
    public function __invoke()
    {
        $root_dir = __DIR__."/../../../../";
        $config = ORMSetup::createAnnotationMetadataConfiguration(
            array($root_dir."/module/Api/src/Model"),
            true,
        );
        // configuring the database connection
        
        $dotenv = Dotenv::createUnsafeImmutable($root_dir);
        $dotenv->load();
        $dbParams =[
            'dbname' => getenv("DB_NAME"),
            'user' => getenv("DB_USER"),
            'port' => getenv("DB_PORT"),
            'password' => getenv("MY_SQL_PASS"),
            'host' => getenv("DB_HOST"),
            'driver' => getenv("DB_DRIVER"),
        ];
        $connection =  DriverManager::getConnection($dbParams, $config);
        $entityManager = new EntityManager($connection, $config);
        
        return $entityManager; 
    }
    public function getEntityManager(): EntityManagerInterface{
        $root_dir = __DIR__."/../../../../";
        $config = ORMSetup::createAnnotationMetadataConfiguration(
            array($root_dir."/module/Api/src/Model"),
            true,
        );
        // configuring the database connection
        
        $dotenv = Dotenv::createUnsafeImmutable($root_dir);
        $dotenv->load();
        $dbParams =[
            'dbname' => getenv("DB_NAME"),
            'user' => getenv("DB_USER"),
            'port' => getenv("DB_PORT"),
            'password' => getenv("MY_SQL_PASS"),
            'host' => getenv("DB_HOST"),
            'driver' => getenv("DB_DRIVER"),
        ];
        $connection =  DriverManager::getConnection($dbParams, $config);
        $entityManager = new EntityManager($connection, $config);
        
        return $entityManager; 
    }
};
?>