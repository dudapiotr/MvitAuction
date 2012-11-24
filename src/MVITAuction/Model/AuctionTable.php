<?php
// src/MVITAuction/Model/MVITAuctionTable.php:
namespace MVITAuction\Model;

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
            'artist' => $auction->artist,
            'title' => $auction->title,
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

