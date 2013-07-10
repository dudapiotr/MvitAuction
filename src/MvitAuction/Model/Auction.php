<?php
namespace MvitAuction\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class Auction {
    public $id;
    public $user_id;
    public $category_id;
    public $currency_id;
    public $category_name;
    public $category_slug;
    public $created;
    public $end_time;
    public $updated;
    public $views;
    public $price;
    public $buyout;
    public $bid;
    public $bid_count;
    public $slug;
    public $header;
    public $body;
    public $protection;
    protected $inputFilter;

    public function exchangeArray($data) {
        if (isset($data['category']['id'])) { $data['category_id'] = $data['category']['id']; }
        if (isset($data['currency']['id'])) { $data['currency_id'] = $data['currency']['id']; }
        $this->id             = (isset($data['id'])) ? $data['id'] : null;
        $this->user_id        = (isset($data['user_id'])) ? $data['user_id'] : null;
        $this->category_id    = (isset($data['category_id'])) ? $data['category_id'] : null;
        $this->currency_id    = (isset($data['currency_id'])) ? $data['currency_id'] : null;
        $this->category_name  = (isset($data['category_name'])) ? $data['category_name'] : null;
        $this->category_slug  = (isset($data['category_slug'])) ? $data['category_slug'] : null;
        $this->created        = (isset($data['created'])) ? $data['created'] : null;
        $this->end_time       = (isset($data['end_time'])) ? $data['end_time'] : null;
        $this->updated        = (isset($data['updated'])) ? $data['updated'] : null;
        $this->views          = (isset($data['views'])) ? $data['views'] : null;
        $this->price          = (isset($data['price'])) ? $data['price'] : null;
        $this->buyout         = (isset($data['buyout'])) ? $data['buyout'] : null;
        $this->bid            = (isset($data['bid'])) ? $data['bid'] : null;
        $this->bid_count      = (isset($data['bid_count'])) ? $data['bid_count'] : null;
        $this->slug           = (isset($data['slug'])) ? $data['slug'] : null;
        $this->header         = (isset($data['header'])) ? $data['header'] : null;
        $this->body           = (isset($data['body'])) ? $data['body'] : null;
        $this->protection     = (isset($data['protection'])) ? $data['protection'] : null;
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
                'required' => false,
		'filters'  => array(
                    array('name' => 'Int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'user_id',
                'required' => false,
		'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'category_id',
                'required' => true,
		'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'currency_id',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'created',
                'required' => false,
		'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'end_time',
                'required' => true,
                'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'updated',
                'required' => false,
		'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'price',
                'required' => true,
		'validators' => array(
                    array(
                        'name'    => 'float',
#                        'options' => array(
#                            'locale' => 'en_GB'
#                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'buyout',
                'required' => false,
                'validators' => array(
                    array(
                        'name'    => 'float',
#                        'options' => array(
#                            'locale' => 'en_GB'
#                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'slug',
                'required' => false,
		'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                      'name' =>'NotEmpty', 
                        'options' => array(
                            'messages' => array(
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'Please enter a slug!' 
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 50,
                            'messages' => array(
                                'stringLengthTooShort' => 'Please enter a slug between 3 to 50 character!', 
                                'stringLengthTooLong' => 'Please enter a slug between 3 to 50 character!' 
                            ),
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'header',
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
                                \Zend\Validator\NotEmpty::IS_EMPTY => 'You need to enter a subject!'
                            ),
                        ),
                    ),
                    array(
                        'name' => 'StringLength',
                        'options' => array(
                            'encoding' => 'UTF-8',
                            'min' => 3,
                            'max' => 50,
                            'messages' => array(
                                'stringLengthTooShort' => 'Your subject is to short, min 3 chars!', 
                                'stringLengthTooLong' => 'Your subject is to long, max 50 chars!' 
                            ),
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'body',
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
                            'min'      => 50,
                            'max'      => 1000,
                            'messages' => array(
                                'stringLengthTooShort' => 'Your body is to short, min 50 chars!', 
                                'stringLengthTooLong' => 'Your body is to large, max 1000 chars!' 
                            ),
                        ),
                    ),
                ),
            )));

            $inputFilter->add($factory->createInput(array(
                'name'     => 'protection',
                'required' => false,
		'filters'  => array(
                    array('name' => 'int'),
                ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }
}