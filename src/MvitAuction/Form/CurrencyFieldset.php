<?php
namespace MvitAuction\Form;

use MvitAuction\Model\CurrencyTable;
use Zend\Form\Fieldset;

class CurrencyFieldset extends Fieldset { 
    public function __construct(CurrencyTable $currencyTable) {
        parent::__construct('currencyfieldset');

        $options = array();
        foreach($currencyTable->fetchAll() as $currencyRow) {
            $options[$currencyRow->id] = $currencyRow->name;
        }

        $this->add(array(
            'name' => 'id',
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
