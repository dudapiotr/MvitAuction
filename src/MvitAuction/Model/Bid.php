<?php
namespace MvitAuction\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Bid {
    public $id;
    public $auction;
    public $user;
    public $bid;
    public $time;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id      = (isset($data['id'])) ? $data['id'] : null;
        $this->auction = (isset($data['auction'])) ? $data['auction'] : 0;
        $this->user    = (isset($data['user'])) ? $data['user'] : null;
        $this->bid     = (isset($data['bid'])) ? $data['bid'] : null;
        $this->time    = (isset($data['time'])) ? $data['time'] : 0;
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
                'name'     => 'auction',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'bid',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'time',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
