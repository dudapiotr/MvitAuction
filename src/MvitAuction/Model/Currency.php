<?php
namespace MvitAuction\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Currency {
    public $id;
    public $name;
    public $before;
    public $after;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id     = (isset($data['id'])) ? $data['id'] : null;
        $this->name   = (isset($data['name'])) ? $data['name'] : 0;
        $this->before = (isset($data['before'])) ? $data['before'] : 0;
        $this->after  = (isset($data['after'])) ? $data['after'] : 0;
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

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
