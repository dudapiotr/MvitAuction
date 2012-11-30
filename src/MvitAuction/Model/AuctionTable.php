<?php
// src/MvitAuction/Model/MvitAuctionTable.php:
namespace MvitAuction\Model;

use Zend\Db\TableGateway\TableGateway;

class AuctionTable {
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway) {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll() {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getAuction($id) {
        $id = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveAuction(Auction $auction) {
        $data = array(
            'owner' => $auction->owner,
            'start' => $auction->start,
            'stop' => $auction->stop,
            'updated' => $auction->updated,
            'price' => $auction->price,
            'bid' => $auction->bid,
            'bidcount' => $auction->bidcount,
            'bidhistory' => $auction->bidhistory,
            'header' => $auction->header,
            'body' => $auction->body,
            'protection' => $auction->protection,
        );

        $id = (int)$auction->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAuction($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAuction($id) {
        $this->tableGateway->delete(array('id' => $id));
    }
}

