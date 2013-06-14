<?php
namespace MvitAuction;

use MvitAuction\Model\Auction;
use MvitAuction\Model\AuctionTable;
use MvitAuction\Model\Bid;
use MvitAuction\Model\BidTable;
use MvitAuction\Model\Category;
use MvitAuction\Model\CategoryTable;
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
                'MvitAuction\Model\AuctionTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table     = new AuctionTable($dbAdapter);
                    return $table;
                },
                'MvitAuction\Model\BidTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table     = new BidTable($dbAdapter);
                    return $table;
                },
                'MvitAuction\Model\CategoryTable' =>  function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table     = new CategoryTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }
}
