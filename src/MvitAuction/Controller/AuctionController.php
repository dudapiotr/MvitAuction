<?php
// src/MvitAuction/Controller/AuctionController.php:
namespace MvitAuction\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MvitAuction\Model\Auction;
use MvitAuction\Form\AuctionForm;

class AuctionController extends AbstractActionController {
    protected $auctionTable;

    public function getAuctionTable() {
        if (!$this->auctionTable) {
            $sm = $this->getServiceLocator();
            $this->auctionTable = $sm->get('MvitAuction\Model\AuctionTable');
        }
        return $this->auctionTable;
    }

    public function addAction() {
        $form = new AuctionForm();
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $auction = new Auction();
            $form->setInputFilter($auction->getInputFilter());
            $form->setData($request->getPost());
echo "!Valid: ".$form->isValid();
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
        $auction = $this->getAuctionTable()->getAuction($id);

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
            'auctions' => $this->getAuctionTable()->fetchAll(),
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
}
