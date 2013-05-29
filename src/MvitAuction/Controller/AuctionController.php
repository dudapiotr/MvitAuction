<?php
namespace MvitAuction\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MvitAuction\Model\Auction;
use MvitAuction\Model\Category;
use MvitAuction\Form\AuctionForm;

class AuctionController extends AbstractActionController {
    protected $auctionTable;
    protected $categoryTable;

    public function getAuctionTable() {
        if (!$this->auctionTable) {
            $sm = $this->getServiceLocator();
            $this->auctionTable = $sm->get('MvitAuction\Model\AuctionTable');
        }
        return $this->auctionTable;
    }

    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('MvitAuction\Model\CategoryTable');
        }
        return $this->categoryTable;
    }

    public function addAction() {
        $form = new AuctionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $auction = new Auction();
            $form->setInputFilter($auction->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $auction->exchangeArray($form->getData());
                $this->getAuctionTable()->saveAuction($auction);

                // Redirect to list of auctions
                return $this->redirect()->toRoute('mvitauction');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('mvitauction');
        }
        $auction = $this->getAuctionTable()->getAuctionById($id);

        $form  = new AuctionForm();
        $form->bind($auction);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($auction->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getAuctionTable()->saveAuction($auction);
                // Redirect to list of auctions
                return $this->redirect()->toRoute('mvitauction');
            }
        }

        return array(
            'id' => $id,
            'form' => $form,
        );
    }

    public function indexAction() {
        return new ViewModel(array(
            'categories' => $this->getCategoryTable()->fetchAll(),
        ));
    }

    public function categoryAction() {
        $slug = (string) $this->params()->fromRoute('slug', 0);
        $category = $this->getCategoryTable()->getCategoryBySlug($slug);
        return new ViewModel(array(
            'auctions' => $this->getAuctionTable()->getAuctionByCategoryId($category->id),
            'category' => $category,
        ));
    }

    public function deleteAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('mvitauction');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAuctionTable()->deleteAuction($id);
            }

            // Redirect to list of auctions
            return $this->redirect()->toRoute('mvitauction');
        }

        return array(
            'id'      => $id,
            'auction' => $this->getAuctionTable()->getAuction($id)
        );
    }

    public function viewAction() {
        $slug = (string) $this->params()->fromRoute('slug', 0);
        if (!$slug) {
            return $this->redirect()->toRoute('mvitauction');
        }

        return new ViewModel(array(
            'auction' => $this->getAuctionTable()->getAuctionBySlug($slug),
        ));
    }
}
