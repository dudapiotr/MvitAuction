<?php
namespace MvitAuction\Form;

use Zend\Form\Form;

class BidForm extends Form {
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
            'name' => 'bid',
            'attributes' => array(
                'type'  => 'text',
            ),
        ));
        $this->add(array(
            'name' => 'submit',
            'attributes' => array(
                'type'  => 'submit',
                'value' => 'Place bid',
                'id' => 'submitbutton',
            ),
        ));
    }
}