<?php
// src/MVITAuction/Controller/AuctionController.php:
namespace MVITAuction\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MVITAuction\Model\Auction;
use MVITAuction\Form\AuctionForm;

class AuctionController extends AbstractActionController {
    protected $auctionTable;

    public function getAuctionTable() {
        if (!$this->auctionTable) {
            $sm = $this->getServiceLocator();
            $this->auctionTable = $sm->get('MVITAuction\Model\AuctionTable');
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

            if ($form->isValid()) {
                $auction->exchangeArray($form->getData());
                $this->getAuctionTable()->saveAuction($auction);

                // Redirect to list of auctions
                return $this->redirect()->toRoute('auction');
            }
        }
        return array('form' => $form);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('auction', array(
                'action' => 'add'
            ));
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
                return $this->redirect()->toRoute('auction');
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
            return $this->redirect()->toRoute('auction');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $id = (int) $request->getPost('id');
                $this->getAuctionTable()->deleteAuction($id);
            }

            // Redirect to list of auctions
            return $this->redirect()->toRoute('auction');
        }

        return array(
            'id'      => $id,
            'auction' => $this->getAuctionTable()->getAuction($id)
        );
    }
}
