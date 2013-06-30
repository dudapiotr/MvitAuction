<?php
namespace MvitAuction\Form;

use Zend\Form\Form;

class AuctionForm extends Form {
    public function __construct($name = null) {
        parent::__construct('auction');
        $this->setName('auction');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'id',
            'attributes' => array(
                'type'  => 'hidden',
            ),
        ));
        $this->add(array(
            'name' => 'user_id',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'User',
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
            'name' => 'end_time',
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
            'name' => 'slug',
            'attributes' => array(
                'type'  => 'text',
            ),
            'options' => array(
                'label' => 'Slug',
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

    public function init() {
        $this->add(array(
            'name' => 'category',
            'type' => 'MvitAuction\Form\CategoryFieldset',
        ));
        $this->add(array(
            'name' => 'currency',
            'type' => 'MvitAuction\Form\CurrencyFieldset',
        ));
    }
}
