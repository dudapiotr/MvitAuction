<?php
namespace MvitAuction\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class CurrencyTable extends AbstractTableGateway  {
    protected $table = 'auction_currency';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Currency());
        $this->initialize();
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                $select->columns(array('id' => 'ACU_Id',
                                       'name' => 'ACU_Name',
                                       'before' => 'ACU_Before',
                                       'after' => 'ACU_After',
                                      )
                                );
            });
        return $resultSet;
    }

    public function getCurrencyById($id) {
        $id = (int) $id;
        $resultSet = $this->select(function (Select $select) use ($id) {
                $select->columns(array('id' => 'ACU_Id',
                                       'name' => 'ACU_Name',
                                       'before' => 'ACU_Before',
                                       'after' => 'ACU_After',
                                      )
                                )
                       ->where(array('ACU_Id' => $id));
            });
        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveCurrency(Currency $currency) {
        $data = array(
            'ACU_Id' => $currency->id,
            'ACU_Name' => $currency->name,
            'ACU_Before' => $currency->before,
            'ACU_After' => $currency->after,
        );

        $id = (int) $bid->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getCurrencyById($id)) {
                $this->update($data, array('ACU_Id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCurrency($id) {
        $this->delete(array('ACU_Id' => $id));
    }
}
