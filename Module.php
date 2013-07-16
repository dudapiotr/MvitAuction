<?php
namespace MvitAuction;

use MvitAuction\Form\CategoryFieldset;
use MvitAuction\Form\CurrencyFieldset;
use MvitAuction\Model\Auction;
use MvitAuction\Model\AuctionTable;
use MvitAuction\Model\Bid;
use MvitAuction\Model\BidTable;
use MvitAuction\Model\Category;
use MvitAuction\Model\CategoryTable;
use MvitAuction\Model\Currency;
use MvitAuction\Model\CurrencyTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

class Module implements FormElementProviderInterface {
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

    public function getFormElementConfig() {
        return array(
            'factories' => array(
                'MvitAuction\Form\CategoryFieldset' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $categoryTable = $serviceLocator->get('MvitAuction\Model\CategoryTable');
                    $fieldset = new CategoryFieldset($categoryTable);
                    return $fieldset;
                },
                'MvitAuction\Form\CurrencyFieldset' => function($sm) {
                    $serviceLocator = $sm->getServiceLocator();
                    $currencyTable = $serviceLocator->get('MvitAuction\Model\CurrencyTable');
                    $fieldset = new CurrencyFieldset($currencyTable);
                    return $fieldset;
                },
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'MvitAuction\Model\AuctionTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new AuctionTable($dbAdapter);
                    return $table;
                },
                'MvitAuction\Model\BidTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new BidTable($dbAdapter);
                    return $table;
                },
                'MvitAuction\Model\CategoryTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new CategoryTable($dbAdapter);
                    return $table;
                },
                'MvitAuction\Model\CurrencyTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new CurrencyTable($dbAdapter);
                    return $table;
                },
            ),
        );
    }

    public function getViewHelperConfig()   {
        return array(
            'invokables' => array(
                'relativeTime' => 'MvitAuction\View\Helper\RelativeTime',
            )
        );
    }
}
