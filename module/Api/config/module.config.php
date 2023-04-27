<?php

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
                return new Api\V1\Rpc\TaskController($entityManager);
            },
        ],
    ],
    'router' => [
        'routes' => [
            'get-tasks' => [
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
            ],'create-task' => [
                'type' => 'literal',
                'options' => [
                    'route' => '/task/create',
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

        ],
    ],
];
