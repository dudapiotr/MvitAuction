<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'mvitauction' => 'MvitAuction\Controller\AuctionController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'mvitauction' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/auctions',
                    'defaults' => array(
                        'controller' => 'mvitauction',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'category' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/c/:slug',
                            'constraints' => array(
                               'slug' => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'mvitauction',
                                'action'     => 'category',
                            ),
                        ),
                    ),
                    'view' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/v/:slug',
                            'constraints' => array(
                               'slug' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'mvitauction',
                                'action'     => 'view',
                            ),
                        ),
                    ),
                    'add' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/add',
                            'defaults' => array(
                                'controller' => 'mvitauction',
                                'action'     => 'add',
                            ),
                        ),
                    ),
                    'bid' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/b[/:slug]',
                            'constraints' => array(
                               'slug' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'mvitauction',
                                'action'     => 'bid',
                            ),
                        ),
                    ),
                    'edit' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/edit[/:slug]',
                            'constraints' => array(
                               'slug' => '[a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                                'controller' => 'mvitauction',
                                'action'     => 'edit',
                            ),
                        ),
                    ),
                    'delete' => array(
                        'type' => 'segment',
                        'options' => array(
                            'route' => '/delete[/:id]',
                            'constraints' => array(
                               'id' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'controller' => 'mvitauction',
                                'action'     => 'delete',
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),

    'view_manager' => array(
        'template_path_stack' => array(
            'mvitauction' => __DIR__ . '/../view',
        ),
    ),
);
