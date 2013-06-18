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
                                       'auction_id' => 'AB_AuctionId',
                                       'user_id' => 'AB_UserId',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->join('user', 'auction_bid.AB_UserId = user.user_id', array('username' => 'username',));
            });
        return $resultSet;
    }

    public function getBidById($id) {
        $id = (int) $id;
        $resultSet = $this->select(function (Select $select) use ($id) {
                $select->columns(array('id' => 'AB_Id',
                                       'auction_id' => 'AB_AuctionId',
                                       'user_id' => 'AB_UserId',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->join('user', 'auction_bid.AB_UserId = user.user_id', array('username' => 'username',))
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
                                       'auction_id' => 'AB_AuctionId',
                                       'user_id' => 'AB_UserId',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->join('user', 'auction_bid.AB_UserId = user.user_id', array('username' => 'username',))
                       ->where(array('AB_AuctionId' => $auction))
                       ->order('auction_bid.AB_Bid DESC');
            });
        return $resultSet;
    }

    public function getHighestBidByAuction($auction) {
        $auction = (int) $auction;
        $resultSet = $this->select(function (Select $select) use ($auction) {
                $select->columns(array('id' => 'AB_Id',
                                       'auction_id' => 'AB_AuctionId',
                                       'user_id' => 'AB_UserId',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->join('user', 'auction_bid.AB_UserId = user.user_id', array('username' => 'username',))
                       ->where(array('AB_AuctionId' => $auction))
                       ->order('auction_bid.AB_Bid DESC')->limit(1);
            });
        $row = $resultSet->current();$
        if (!$row) {$
            throw new \Exception("Could not find row $id");$
        }$
        return $row;$
    }

    public function getBidsByUser($user) {
        $user = (int) $user;
        $resultSet = $this->select(function (Select $select) use ($user) {
                $select->columns(array('id' => 'AB_Id',
                                       'auction_id' => 'AB_AuctionId',
                                       'user_id' => 'AB_UserId',
                                       'bid' => 'AB_Bid',
                                       'time' => 'AB_Time',
                                      )
                                )
                       ->join('user', 'auction_bid.AB_UserId = user.user_id', array('username' => 'username',))
                       ->where(array('AB_UserId' => $user));
            });
        return $resultSet;
    }

    public function saveBid(Bid $bid) {
        $data = array(
            'AB_Id'        => $bid->id,
            'AB_AuctionId' => $bid->auction_id,
            'AB_UserId'    => $bid->user_id,
            'AB_Bid'       => $bid->bid,
            'AB_Time'      => $bid->time,
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

    public function deleteBid($id) {
        $this->delete(array('AB_Id' => $id));
    }
}
