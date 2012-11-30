<?php
// conﬁg/module.config.php:
return array(
    'controllers' => array(
        'invokables' => array(
            'mvitauction' => 'MvitAuction\Controller\AuctionController',
        ),
    ),

    'router' => array(
        'routes' => array(
            'mvitauction' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/auctions[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'mvitauction',
                        'action'     => 'index',
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
