<?php
namespace MvitAuction\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Bid {
    public $id;
    public $auction_id;
    public $user_id;
    public $username;
    public $bid;
    public $time;
    protected $inputFilter;

    public function exchangeArray($data) {
        $float_filter = new \Zend\I18n\Filter\NumberFormat();

        $this->id         = (isset($data['id'])) ? $data['id'] : null;
        $this->auction_id = (isset($data['auction_id'])) ? $data['auction_id'] : 0;
        $this->user_id    = (isset($data['user_id'])) ? $data['user_id'] : 0;
        $this->username   = (isset($data['username'])) ? $data['username'] : "";
        $this->bid        = (isset($data['bid'])) ? (float) $float_filter->filter($data['bid']) : 0;
        $this->time       = (isset($data['time'])) ? $data['time'] : 0;
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
                'name'     => 'auction_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'bid',
                'required' => true,
                'validators' => array(
                    array('name' => 'Float'),
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
