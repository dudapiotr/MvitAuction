<?php
namespace MvitAuction\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class AuctionTable extends AbstractTableGateway  {
    protected $table = 'auction';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Auction());
        $this->initialize();
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                $select->columns(array('id' => 'A_Id',
                                       'owner' => 'A_Owner',
                                       'category_id' => 'A_CategoryId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'bid' => 'A_Bid',
                                       'bid_count' => 'A_BidCount',
                                       'bid_history' => 'A_BidHistory',
                                       'slug' => 'A_Slug',
                                       'header' => 'A_Header',
                                       'body' => 'A_Body',
                                       'protection' => 'A_Protection',
                                      )
                                )
                       ->join('auction_category', 'auction.A_CategoryId = auction_category.AC_Id', array('category_name' => 'AC_Name', 'category_slug' => 'AC_Slug',))
                       ->order('auction.A_EndTime DESC')->limit(30);
            });
        return $resultSet;
    }

    public function getAuctionById($id) {
        $id = (int) $id;
        $rowset = $this->select(function (Select $select) use ($id) {
                $select->columns(array('id' => 'A_Id',
                                       'owner' => 'A_Owner',
                                       'category_id' => 'A_CategoryId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'buyout' => 'A_Buyout',
                                       'bid' => 'A_Bid',
                                       'bid_count' => 'A_BidCount',
                                       'bid_history' => 'A_BidHistory',
                                       'slug' => 'A_Slug',
                                       'header' => 'A_Header',
                                       'body' => 'A_Body',
                                       'protection' => 'A_Protection',
                                      )
                                )
                       ->join('auction_category', 'auction.A_CategoryId = auction_category.AC_Id', array('category_name' => 'AC_Name', 'category_slug' => 'AC_Slug',))
                       ->where(array('A_Id' => $id));
            });
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getAuctionBySlug($slug) {
        $slug = (string) $slug;
        $rowset = $this->select(function (Select $select) use ($slug) {
                $select->columns(array('id' => 'A_Id',
                                       'owner' => 'A_Owner',
                                       'category_id' => 'A_CategoryId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'buyout' => 'A_Buyout',
                                       'bid' => 'A_Bid',
                                       'bid_count' => 'A_BidCount',
                                       'bid_history' => 'A_BidHistory',
                                       'slug' => 'A_Slug',
                                       'header' => 'A_Header',
                                       'body' => 'A_Body',
                                       'protection' => 'A_Protection',
                                      )
                                )
                       ->join('auction_category', 'auction.A_CategoryId = auction_category.AC_Id', array('category_name' => 'AC_Name', 'category_slug' => 'AC_Slug',))
                       ->where(array('A_Slug' => $slug));
            });
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $slug");
        }
        return $row;
    }

    public function getAuctionByCategoryId($id) {
        $id = (int) $id;
        $rowset = $this->select(function (Select $select) use ($id) {
                $select->columns(array('id' => 'A_Id',
                                       'owner' => 'A_Owner',
                                       'category_id' => 'A_CategoryId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'buyout' => 'A_Buyout',
                                       'bid' => 'A_Bid',
                                       'bid_count' => 'A_BidCount',
                                       'bid_history' => 'A_BidHistory',
                                       'slug' => 'A_Slug',
                                       'header' => 'A_Header',
                                       'body' => 'A_Body',
                                       'protection' => 'A_Protection',
                                      )
                                )
                       ->join('auction_category', 'auction.A_CategoryId = auction_category.AC_Id', array('category_name' => 'AC_Name', 'category_slug' => 'AC_Slug',))
                       ->where(array('A_CategoryId' => $id));
            });
        return $rowset;
    }

    public function saveAuction(Auction $auction) {
        $data = array(
            'A_Owner' => $auction->owner,
            'A_Category'=> $auction->category,
            'A_Created' => $auction->created,
            'A_Endtime' => $auction->endtime,
            'A_Updated' => time(),
            'A_Price' => $auction->price,
            'A_Buyout' => $auction->buyout,
            'A_Bid' => $auction->bid,
            'A_Bidcount' => $auction->bidcount,
            'A_Bidhistory' => serialize($auction->bidhistory),
            'A_Slug' => $auction->slug,
            'A_Header' => $auction->header,
            'A_Body' => $auction->body,
            'A_Protection' => $auction->protection,
        );

        $id = (int)$auction->id;
        if ($id == 0) {
            $data['A_Created'] = time();
            $data['A_Bid'] = 0;
            $data['A_Bidcount'] = 0;
            $this->tableGateway->insert($data);
        } else {
            if ($this->getAuction($id)) {
                $this->tableGateway->update($data, array('A_Id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAuction($id) {
        $this->tableGateway->delete(array('A_Id' => $id));
    }
}

