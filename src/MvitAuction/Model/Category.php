<?php
namespace MvitAuction\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Category {
    public $id;
    public $parent;
    public $name;
    public $slug;
    public $auctions;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id       = (isset($data['id'])) ? $data['id'] : null;
        $this->parent   = (isset($data['parent'])) ? $data['parent'] : 0;
        $this->name     = (isset($data['name'])) ? $data['name'] : null;
        $this->slug     = (isset($data['slug'])) ? $data['slug'] : null;
        $this->auctions = (isset($data['auctions'])) ? $data['auctions'] : 0;
    }

    public function getArrayCopy() {
        return get_object_vars($this);
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        throw new \Exception("Not used");
    }

    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory     = new InputFactory();

            $inputFilter->add($factory->createInput(array(
                'name'     => 'id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'parent',
                'required' => false,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'slug',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter a name!' 
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 30,
                            'messages' => array(
                                'stringLengthTooShort' => 'Please enter a name between 3 to 30 character!', 
                                'stringLengthTooLong' => 'Please enter a name between 3 to 30 character!' 
                            ),
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
