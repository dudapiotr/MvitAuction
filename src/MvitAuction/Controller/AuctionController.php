<?php
namespace MvitAuction\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use MvitAuction\Model\Auction;
use MvitAuction\Model\Bid;
use MvitAuction\Model\Category;
use MvitAuction\Form\AuctionForm;
use MvitAuction\Form\BidForm;

class AuctionController extends AbstractActionController {
    protected $auctionTable;
    protected $bidTable;
    protected $categoryTable;
    protected $currencyTable;

    public function getAuctionTable() {
        if (!$this->auctionTable) {
            $sm = $this->getServiceLocator();
            $this->auctionTable = $sm->get('MvitAuction\Model\AuctionTable');
        }
        return $this->auctionTable;
    }

    public function getBidTable() {
        if (!$this->bidTable) {
            $sm = $this->getServiceLocator();
            $this->bidTable = $sm->get('MvitAuction\Model\BidTable');
        }
        return $this->bidTable;
    }

    public function getCategoryTable() {
        if (!$this->categoryTable) {
            $sm = $this->getServiceLocator();
            $this->categoryTable = $sm->get('MvitAuction\Model\CategoryTable');
        }
        return $this->categoryTable;
    }

    public function getCurrencyTable() {
        if (!$this->currencyTable) {
            $sm = $this->getServiceLocator();
            $this->currencyTable = $sm->get('MvitAuction\Model\CurrencyTable');
        }
        return $this->currencyTable;
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
        return array(
	    'form' => $form,
	    'flashMessages' => $this->flashMessenger()->getMessages(),
	);
    }

    public function editAction() {
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('mvitauction');
        }
        $auction = $this->getAuctionTable()->getAuctionById($id);

        $form = new AuctionForm();
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
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function bidAction() {
        $slug = (string) $this->params()->fromRoute('slug', 0);
        if (!$slug) {
            return $this->redirect()->toRoute('mvitauction');
        }
        $auction = $this->getAuctionTable()->getAuctionBySlug($slug);
        $request = $this->getRequest();
        if ($request->isPost()) {
            $bid = new Bid();
            $form = new BidForm();
            $form->bind($bid);

            $form->setInputFilter($bid->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $bid->auction = $auction->id;
                $bid->user = $this->zfcUserAuthentication()->getIdentity()->getId();
                $bid->time = time();
                $this->getBidTable()->saveBid($bid);
                $this->flashMessenger()->addMessage('Bid accepted!');
            }
        }
        return $this->redirect()->toRoute('mvitauction/view', array('category' => $auction->category_slug, 'slug' => $auction->slug));
    }

    public function indexAction() {
        return new ViewModel(array(
            'categories' => $this->getCategoryTable()->fetchAll(),
        ));
    }

    public function categoryAction() {
        $slug = (string) $this->params()->fromRoute('slug', 0);
        $category = $this->getCategoryTable()->getCategoryBySlug($slug);
        $currencies = "";
        foreach ($this->getCurrencyTable()->fetchAll() as $currency) {
            $currencies[$currency->id] = $currency;
        }
        return new ViewModel(array(
            'auctions' => $this->getAuctionTable()->getAuctionByCategoryId($category->id),
            'currencies' => $currencies,
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
            'id' => $id,
            'auction' => $this->getAuctionTable()->getAuction($id),
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function viewAction() {
        $slug = (string) $this->params()->fromRoute('slug', 0);
        if (!$slug) {
            return $this->redirect()->toRoute('mvitauction');
        }
        $bid = new Bid();

        $form = new BidForm();
        $form->bind($bid);

        $auction = $this->getAuctionTable()->getAuctionBySlug($slug);

        return new ViewModel(array(
            'auction' => $auction,
            'currency' => $this->getCurrencyTable()->getCurrencyById($auction->currency_id),
            'bids' => $this->getBidTable()->getBidsByAuction($auction->id),
            'form' => $form,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        ));
    }
}
