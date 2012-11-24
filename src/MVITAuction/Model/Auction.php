<?php
// src/MVITAuction/Model/MVITAuction.php:
namespace MVITAuction\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Auction {
    public $id;
    public $owner;
    public $start;
    public $stop;
    public $updated;
    public $price;
    public $bid;
    public $bidcount;
    public $bidhistory;
    public $header;
    public $body;
    public $protection;
    protected $inputFilter;

    public function exchangeArray($data) {
        $this->id         = (isset($data['id'])) ? $data['id'] : null;
        $this->owner      = (isset($data['owner'])) ? $data['owner'] : null;
        $this->start      = (isset($data['start'])) ? $data['start'] : null;
        $this->stop       = (isset($data['stop'])) ? $data['stop'] : null;
        $this->updated    = (isset($data['updated'])) ? $data['updated'] : null;
        $this->price      = (isset($data['price'])) ? $data['price'] : null;
        $this->bid        = (isset($data['bid'])) ? $data['bid'] : null;
        $this->bidcount   = (isset($data['bidcount'])) ? $data['bidcount'] : null;
        $this->bidhistory = (isset($data['bidhistory'])) ? $data['bidhistory'] : null;
        $this->header     = (isset($data['header'])) ? $data['header'] : null;
        $this->body       = (isset($data['body'])) ? $data['body'] : null;
        $this->protection = (isset($data['protection'])) ? $data['protection'] : null;
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
                'name'     => 'artist',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'title',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min'      => 1,
                            'max'      => 100,
                        ),
                    ),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}
