<?php
namespace MvitAuction\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
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

    public function fetchAll($activeOnly = true) {
        $resultSet = $this->select(function (Select $select) {
                $subquery = "(SELECT AB_Bid FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id ORDER BY AB_Id DESC LIMIT 1)";
                $subquerycount = "(SELECT COUNT(*) FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id)";
                $select->columns(array('id' => 'A_Id',
                                       'user_id' => 'A_UserId',
                                       'category_id' => 'A_CategoryId',
                                       'currency_id' => 'A_CurrencyId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'bid' => new Expression ($subquery),
                                       'bid_count' => new Expression ($subquerycount),
                                       'slug' => 'A_Slug',
                                       'header' => 'A_Header',
                                       'body' => 'A_Body',
                                       'protection' => 'A_Protection',
                                      )
                                )
                       ->join('auction_category', 'auction.A_CategoryId = auction_category.AC_Id', array('category_name' => 'AC_Name', 'category_slug' => 'AC_Slug',))
                       ->order('auction.A_EndTime DESC')->limit(30);
                if ($activeOnly == true) {
                    $select->where('A_EndTime > ?', time());
                }
            });
        return $resultSet;
    }

    public function getAuctionById($id) {
        $id = (int) $id;
        $rowset = $this->select(function (Select $select) use ($id) {
                $subquery = "(SELECT AB_Bid FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id ORDER BY AB_Id DESC LIMIT 1)";
                $subquerycount = "(SELECT COUNT(*) FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id)";
                $select->columns(array('id' => 'A_Id',
                                       'user_id' => 'A_UserId',
                                       'category_id' => 'A_CategoryId',
                                       'currency_id' => 'A_CurrencyId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'buyout' => 'A_Buyout',
                                       'bid' => new Expression ($subquery),
                                       'bid_count' => new Expression ($subquerycount),
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
                $subquery = "(SELECT AB_Bid FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id ORDER BY AB_Id DESC LIMIT 1)";
                $subquerycount = "(SELECT COUNT(*) FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id)";
                $select->columns(array('id' => 'A_Id',
                                       'user_id' => 'A_UserId',
                                       'category_id' => 'A_CategoryId',
                                       'currency_id' => 'A_CurrencyId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'buyout' => 'A_Buyout',
                                       'bid' => new Expression ($subquery),
                                       'bid_count' => new Expression ($subquerycount),
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

    public function getAuctionByCategoryId($id = 0, $activeOnly = true) {
        $id = (int) $id;
        $rowset = $this->select(function (Select $select) use ($id, $activeOnly) {
                $subquery = "(SELECT AB_Bid FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id ORDER BY AB_Id DESC LIMIT 1)";
                $subquerycount = "(SELECT COUNT(*) FROM auction_bid WHERE auction_bid.AB_AuctionId = auction.A_Id)";
                $select->columns(array('id' => 'A_Id',
                                       'user_id' => 'A_UserId',
                                       'category_id' => 'A_CategoryId',
                                       'currency_id' => 'A_CurrencyId',
                                       'created' => 'A_Created',
                                       'end_time' => 'A_EndTime',
                                       'updated' => 'A_Updated',
                                       'price' => 'A_Price',
                                       'buyout' => 'A_Buyout',
                                       'bid' => new Expression ($subquery),
                                       'bid_count' => new Expression ($subquerycount),
                                       'slug' => 'A_Slug',
                                       'header' => 'A_Header',
                                       'body' => 'A_Body',
                                       'protection' => 'A_Protection',
                                      )
                                )
                       ->join('auction_category', 'auction.A_CategoryId = auction_category.AC_Id', array('category_name' => 'AC_Name', 'category_slug' => 'AC_Slug',))
                       ->where(array('A_CategoryId' => $id))
                       ->order('auction.A_EndTime DESC');
                if ($activeOnly == false) {
                    $select->where('A_EndTime < ?', time());
                }
            });
        return $rowset;
    }

    public function saveAuction(Auction $auction) {
        $data = array(
            'A_UserId' => $auction->user_id,
            'A_CategoryId' => $auction->category_id,
            'A_CurrencyId' => $auction->currency_id,
            'A_Created' => $auction->created,
            'A_Endtime' => $auction->end_time,
            'A_Updated' => time(),
            'A_Price' => $auction->price,
            'A_Buyout' => $auction->buyout,
            'A_Slug' => $this->toAscii($auction->header),
            'A_Header' => $auction->header,
            'A_Body' => $auction->body,
            'A_Protection' => $auction->protection,
        );
        foreach ($data as $datakey => $dataitem) {
            if ($dataitem == null) {
                unset($data[$datakey]);
            }
        }
        $id = (int)$auction->id;
        if ($id == 0) {
            $data['A_Created'] = time();
            $this->insert($data);
	    $iid = $this->lastInsertValue;
	    $data = array('A_Slug' => $this->toAscii($iid." ".$auction->header));
	    $this->update($data, array('A_Id' => $iid));
        } else {
            if ($this->getAuctionById($id)) {
                $this->update($data, array('A_Id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteAuction($id) {
        $this->delete(array('A_Id' => $id));
    }

    private function toAscii($str, $replace=array(), $delimiter='-') {
        # source: http://cubiq.org/the-perfect-php-clean-url-generator
        setlocale(LC_ALL, 'en_US.UTF8');
        if( !empty($replace) ) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }
}
