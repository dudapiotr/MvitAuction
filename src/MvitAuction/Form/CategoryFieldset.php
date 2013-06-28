<?php
namespace MvitAuction\Form;

use MvitAuction\Model\CategoryTable;
use Zend\Form\Fieldset;

class CategoryFieldset extends Fieldset { 
    public function __construct(CategoryTable $categoryTable) {
        parent::__construct('category'); 

        $options = array();
        foreach($categoryTable->fetchAll() as $categoryRow) {
            $options[$categoryRow->id] = $categoryRow->name;
        }

        $this->add(array(
            'name' => 'category_id',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => true,
            ),
            'options' => array(
                'label' => 'Category',
                'options' => $options,
            ),
        ));
    }
}
