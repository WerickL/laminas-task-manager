<?php

use Api\Entities\Task;
use Doctrine\ORM\Query\AST\Literal;
use Laminas\Router\Http\Method;
use Laminas\Router\Http\Segment;

return [
    'service_manager' => [
        'factories' => [
            \Doctrine\ORM\EntityManager::class => \Api\Helper\EntityManagerFactory::class,
            \Api\V1\Rest\Task\TaskResource::class => \Api\V1\Rest\Task\TaskResourceFactory::class,
        ],
    ],
    'controllers' => [
        'factories' => [
            //'Api\\Rest\\Controller\\Task' => Closure::fromCallable(array()),
            'Api\\Controller\\Task' => function($container) {
                $entityManager = $container->get(\Doctrine\ORM\EntityManager::class);
                $entity = new Task();
                return new Api\V1\Rpc\TaskController($entityManager, $entity);
            },
        ],
    ],
    'router' => [
        'routes' => [
            'tasks' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/task[/:id][/]',
                    'defaults' => [
                        'controller' => 'Api\\Controller\\Task',
                        'action' => 'getTasks',
                    ],
                ],
            ],
            'get-task-by' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/task/get-by',
                    'defaults' => [
                        'controller' => 'Api\\Controller\\Task',
                        'action' => 'getBy',
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'process' => [
                            'type' => 'Request',
                            'options' => [
                                'methods' => ['POST'],
                                'handler' => RequestHandler::class,
                                'content_type' => ['application/json'],
                            ],
                        ],
                    ],
                ],
            ],
            'create-task' => [
                'type' => Method::class,
                'options' => [
                    'route' => '/task/create',
                    'verb' => 'post',
                    'defaults' => [
                        'controller' => 'Api\\Controller\\Task',
                        'action' => 'createTask',
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'process' => [
                            'type' => 'Request',
                            'options' => [
                                'methods' => ['POST'],
                                'handler' => RequestHandler::class,
                                'content_type' => ['application/json'],
                            ],
                        ],
                    ],
                ],
            ],
            'delete-task' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/task/delete[/:id]',
                    'verb' => 'delete',
                    'defaults' => [
                        'controller' => 'Api\\Controller\\Task',
                        'action' => 'deleteTask',
                    ],
                ],
            ],'
            update-task' => [
                'type' => Segment::class,
                'options' => [
                    'route' => '/task/update',
                    'defaults' => [
                        'controller' => 'Api\\Controller\\Task',
                        'action' => 'updateTask',
                    ],
                    'may_terminate' => true,
                    'child_routes' => [
                        'process' => [
                            'type' => 'Request',
                            'options' => [
                                'methods' => ['PATCH'],
                                'handler' => RequestHandler::class,
                                'content_type' => ['application/json'],
                            ],
                        ],
                    ],
                ],
            ],

        ],
    ],
];
