<?php
namespace MvitAuction\Controller;

use Exception;
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
        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('MvitAuction\Form\AuctionForm');
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();
        if ($request->isPost() || !$this->zfcUserAuthentication()->getIdentity()) {
            $auction = new Auction();
            $form->bind($auction);

            $form->setInputFilter($auction->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $auction->user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
                $auction->created= time();
                $this->getAuctionTable()->saveAuction($auction);
                $this->flashMessenger()->addMessage('Auction created!');
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
        $slug = (string) $this->params()->fromRoute('slug', 0);
        if (!$slug) {
            return $this->redirect()->toRoute('mvitauction');
        }
        try {
            $auction = $this->getAuctionTable()->getAuctionBySlug($slug);
        } catch (Exception $e) {
	    return $this->redirect()->toRoute('mvitauction');
        }

        if (!$this->zfcUserAuthentication()->getIdentity()->getId() == $auction->user_id) {
            return $this->redirect()->toRoute('mvitauction/view', array('category' => $auction->category_slug, 'slug' => $auction->slug));
        }


        $formManager = $this->serviceLocator->get('FormElementManager');
        $form = $formManager->get('MvitAuction\Form\AuctionForm');
        $form->setValidationGroup("id", "user_id", "end_time", "price", "buyout", "header", "body");
        $form->bind($auction);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter($auction->getInputFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $this->getAuctionTable()->saveAuction($auction);
                $this->flashMessenger()->addMessage('Auction edited!');
                // Redirect to list of auctions
                return $this->redirect()->toRoute('mvitauction/view', array('category' => $auction->category_slug, 'slug' => $auction->slug));
            }
        }

        return array(
            'slug' => $slug,
            'form' => $form,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function bidAction() {
        $slug = (string) $this->params()->fromRoute('slug', 0);
        if (!$slug || !$this->zfcUserAuthentication()->getIdentity()) {
            return $this->redirect()->toRoute('mvitauction');
        }
        try {
            $auction = $this->getAuctionTable()->getAuctionBySlug($slug);
        } catch (Exception $e) {
            return $this->redirect()->toRoute('mvitauction');
        }
        $request = $this->getRequest();
        if ($request->isPost()) {
            $bid = new Bid();
            $form = new BidForm();
            $form->bind($bid);

            $form->setInputFilter($bid->getInputFilter());
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $bid->auction_id = $auction->id;
                $bid->user_id = $this->zfcUserAuthentication()->getIdentity()->getId();
                $bid->time = time();

                if ($this->getBidTable()->getHighestBidByAuction($auction->id)->bid < $bid->bid) {
                    $this->getBidTable()->saveBid($bid);
                    $this->flashMessenger()->addMessage('Bid accepted!');
                } else {
                    $this->flashMessenger()->addMessage('Bid to low, not accepted!');
                }
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
        try {
            $category = $this->getCategoryTable()->getCategoryBySlug($slug);
        } catch (Exception $e) {
            return $this->redirect()->toRoute('mvitauction');
        }
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
        try {
            $auction = $this->getAuctionTable()->getAuctionById($id);
        } catch (Exception $e) {
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
            'auction' => $auction,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        );
    }

    public function viewAction() {
        $slug = (string) $this->params()->fromRoute('slug', 0);
        if (!$slug) {
            return $this->redirect()->toRoute('mvitauction');
        }
        try {
            $auction = $this->getAuctionTable()->getAuctionBySlug($slug);
        } catch (Exception $e) {
            return $this->redirect()->toRoute('mvitauction');
        }

        $bid = new Bid();

        $form = new BidForm();
        $form->bind($bid);

        $bids = "";

        foreach ($this->getBidTable()->getBidsByAuction($auction->id) as $bid) {
            $bids[] = $bid;
        }

        return new ViewModel(array(
            'auction' => $auction,
            'currency' => $this->getCurrencyTable()->getCurrencyById($auction->currency_id),
            'bids' => $bids,
            'form' => $form,
            'flashMessages' => $this->flashMessenger()->getMessages(),
        ));
    }
}
