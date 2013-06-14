<?php
namespace MvitAuction\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class BidTable extends AbstractTableGateway  {
    protected $table = 'auction_bid';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Bid());
        $this->initialize();
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                $select->columns(array('id' => 'AB_Id',
                                       'auction' => 'AB_Auction',
                                       'user' => 'AB_User',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                );
            });
        return $resultSet;
    }

    public function getBidById($id) {
        $id = (int) $id;
        $resultSet = $this->select(function (Select $select) use ($id) {
                $select->columns(array('id' => 'AB_Id',
                                       'auction' => 'AB_Auction',
                                       'user' => 'AB_User',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->where(array('AB_Id' => $id));
            });
        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getBidsByAuction($auction) {
        $auction = (int) $auction;
        $resultSet = $this->select(function (Select $select) use ($auction) {
                $select->columns(array('id' => 'AB_Id',
                                       'auction' => 'AB_Auction',
                                       'user' => 'AB_User',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->where(array('AB_Auction' => $auction));
            });
        return $resultSet;
    }

    public function getBidsByUser($user) {
        $user = (int) $user;
        $resultSet = $this->select(function (Select $select) use ($user) {
                $select->columns(array('id' => 'AB_Id',
                                       'auction' => 'AB_Auction',
                                       'user' => 'AB_User',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->where(array('AB_User' => $user));
            });
        return $resultSet;
    }

    public function saveBid(Bid $bid) {
        $data = array(
            'AB_Id'      => $bid->id,
            'AB_Auction' => $bid->auction,
            'AB_User'    => $bid->user,
            'AB_Bid'     => $bid->bid,
            'AB_Time'    => $bid->time,

        );

        $id = (int) $bid->id;
        if ($id == 0) {
            $this->insert($data);
        } else {
            if ($this->getBidById($id)) {
                $this->update($data, array('AB_Id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAuction($id) {
        $this->delete(array('AB_Id' => $id));
    }
}
