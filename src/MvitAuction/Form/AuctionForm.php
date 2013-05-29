<?php
namespace MvitAuction\Form;

use Zend\Form\Form;

class AuctionForm extends Form {
    public function __construct($name = null) {
        parent::__construct('auction');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'owner',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Owner',
            ),
        ));
        $this->add(array(
            'name' => 'created',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Created',
            ),
        ));
        $this->add(array(
            'name' => 'endtime',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'End date',
            ),
        ));
        $this->add(array(
            'name' => 'updated',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Updated',
            ),
        ));
        $this->add(array(
            'name' => 'price',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Price',
            ),
        ));
        $this->add(array(
            'name' => 'buyout',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Buyout',
            ),
        ));
        $this->add(array(
            'name' => 'bid',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Bid',
            ),
        ));
        $this->add(array(
            'name' => 'bidcount',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Bidcount',
            ),
        ));
        $this->add(array(
            'name' => 'bidhistory',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Bidhistory',
            ),
        ));
        $this->add(array(
            'name' => 'header',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Header',
            ),
        ));
        $this->add(array(
            'name' => 'body',
            'attributes' => array(
                'type'  => 'textarea',
            ),
            'options' => array(
                'label' => 'Body',
            ),
        ));
        $this->add(array(
            'name' => 'protection',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Protection',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Go',
                'id' => 'submitbutton',
            ),
        ));
    }
}
