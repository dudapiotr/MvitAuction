<?php
namespace MvitAuction\Form;

use MvitAuction\Model;
use Zend\Form\Fieldset;

class CurrencyFieldset extends Fieldset { 
    public function __construct($currencyTable) {
        parent::__construct('currency');

        $options = array();
        foreach($currencyTable->fetchAll() as $currencyRow) {
            $options[$currencyRow->id] = $currencyRow->name;
        }

        $this->add(array(
            'name' => 'currency_id',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => true,
            ),
            'options' => array(
                'label' => 'Currency',
                'options' => $options,
            ),
        ));

    }
}
