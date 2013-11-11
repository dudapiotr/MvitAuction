<?php
namespace MvitAuction\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\Sql\Expression;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\AbstractTableGateway;

class CategoryTable extends AbstractTableGateway  {
    protected $table = 'auction_category';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
        $this->resultSetPrototype = new ResultSet();
        $this->resultSetPrototype->setArrayObjectPrototype(new Category());
        $this->initialize();
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                $subquery = "(SELECT COUNT(*) FROM auction WHERE auction.A_CategoryId = auction_category.AC_Id AND A_EndTime > UNIX_TIMESTAMP())";
                $select->columns(array('id' => 'AC_Id',
                                       'parent' => 'AC_Parent',
                                       'name' => 'AC_Name',
                                       'slug' => 'AC_Slug',
                                       'auctions' => new Expression ($subquery),
                                       'visible' => 'AC_Visible',
                                      )
                                )
                       ->where(array('AC_Parent' => 0, 'AC_Visible' => 1));
            });
        return $resultSet;
    }

    public function getCategoryById($id) {
        $id = (int) $id;
        $resultSet = $this->select(function (Select $select) use ($id) {
                $subquery = "(SELECT COUNT(*) FROM auction WHERE auction.A_CategoryId = auction_category.AC_Id AND A_EndTime > UNIX_TIMESTAMP())";
                $select->columns(array('id' => 'AC_Id',
                                       'parent' => 'AC_Parent',
                                       'name' => 'AC_Name',
                                       'slug' => 'AC_Slug',
                                       'auctions' => new Expression ($subquery),
                                       'visible' => 'AC_Visible',
                                      )
                                )
                       ->where(array('AC_Id' => $id));
            });
        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function getCategoryBySlug($slug) {
        $slug = (string) $slug;
        $resultSet = $this->select(function (Select $select) use ($slug) {
                $subquery = "(SELECT COUNT(*) FROM auction WHERE auction.A_CategoryId = auction_category.AC_Id AND A_EndTime > UNIX_TIMESTAMP())";
                $select->columns(array('id' => 'AC_Id',
                                       'parent' => 'AC_Parent',
                                       'name' => 'AC_Name',
                                       'slug' => 'AC_Slug',
                                       'auctions' => new Expression ($subquery),
                                       'visible' => 'AC_Visible',
                                      )
                                )
                       ->where(array('AC_Slug' => $slug));
            });
        $row = $resultSet->current();
        if (!$row) {
            throw new \Exception("Could not find row $slug");
        }
        return $row;
    }

    public function saveCategory(Category $category) {
        $data = array(
            'AC_Id'       => $category->id,
            'AC_Parent'   => $category->parent,
            'AC_Name'     => $category->name,
            'AC_Slug'     => $category->slug,
            'AC_Visible'  => $category->visible,

        );

        $id = (int) $category->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
        } else {
            if ($this->getCategoryById($id)) {
                $this->tableGateway->update($data, array('AC_Id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
    }

    public function deleteCategory($id) {
        $this->tableGateway->delete(array('AC_Id' => $id));
    }
}
