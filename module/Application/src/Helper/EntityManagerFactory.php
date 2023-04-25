<?php
namespace Application\Helper;
require_once __DIR__."/../../../../vendor/autoload.php";
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Dotenv\Dotenv;
use Psr\Container\ContainerAwareInterface;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\ServiceManager\ServiceManager;
use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class EntityManagerFactory
{
    public function __construct()
    {
        
    }
    public function __invoke(ContainerInterface $container)
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
        try {
            $connection =  DriverManager::getConnection($dbParams, $config);
            $entityManager = new EntityManager($connection, $config);
        } catch (\Exception $th) {
            printf($th->getMessage());
        }
        return $entityManager;
    }

    public function getEntityManager(): EntityManagerInterface
    {
    // Create a simple "default" Doctrine ORM configuration for Attributes
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
    try {
        $connection =  DriverManager::getConnection($dbParams, $config);
        $entityManager = new EntityManager($connection, $config);
    } catch (\Exception $th) {
        printf($th->getMessage());
    }
    return $entityManager;
    }
};
?>