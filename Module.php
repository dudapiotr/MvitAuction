<?php
// Module.php
namespace MvitAuction;

use MvitAuction\Model\Auction;
use MvitAuction\Model\AuctionTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module {
    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'MvitAuction\Model\AuctionTable' => function($sm) {
                    $tableGateway = $sm->get('AuctionTableGateway');
                    $table = new AuctionTable($tableGateway);
                    return $table;
                },
                'AuctionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Auction());
                    return new TableGateway('auction', $dbAdapter, null, $resultSetPrototype);
                },
            ),
        );
    }
}
