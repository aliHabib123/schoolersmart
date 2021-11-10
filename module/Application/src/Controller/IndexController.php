<?php

declare(strict_types=1);

namespace Application\Controller;

use ContentMySqlExtDAO;
use CurrencyMySqlExtDAO;
use ImageMySqlExtDAO;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use SaleOrderItemMySqlExtDAO;
use WishlistMySqlExtDAO;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        $langId = HelperController::langId(HelperController::filterInput($this->params('lang')));
        $bannerLocation = ($langId == 1) ? 1 : 2;
        $banners = ContentController::getBanners($bannerLocation);
        $ads = ContentController::getContent("type = 'ad' and lang = $langId ORDER BY display_order asc LIMIT 3");
        $services = ContentController::getContent("type = 'service' ORDER BY display_order ASC LIMIT 4 OFFSET 0");
        $shoppingSlider = ContentController::getContent("type = 'shopping_slider' ORDER BY display_order ASC, id DESC");//shopping_slider
        $featuredCategories = CategoryController::getCategories("is_featured = 1");

        //Todays DEALS, PICKED FOR YOU and BEST OFFERS
        $featuredItems = ProductController::getItems(false, false, "", "", "", ProductController::$FEATURED, "", 24, 0);
        // $pickedForYou = ProductController::getItems(false, false, "", "", "", ProductController::$PICKED_FOR_YOU, "", 10, 0);
        // $bestOffers = ProductController::getItems(false, false, "", "", "", ProductController::$BEST_OFFERS, "", 10, 0);

        $this->layout()->withBanner = true;
        $this->layout()->banners = $banners;
        $this->layout()->isHome = true;
        $data = [
            'ads' => $ads,
            'featuredCategories' => $featuredCategories,
            'featuredItems' => $featuredItems,
            'services' => $services,
            'shoppingSlider' => $shoppingSlider,
            // 'pickedForYou' => $pickedForYou,
            // 'bestOffers' => $bestOffers,
        ];
        return new ViewModel($data);
    }

    public function contentAction()
    {
        $contentMySqlExtDAO = new ContentMySqlExtDAO();
        $imageMySqlExtDAO = new ImageMySqlExtDAO();
        $slug = HelperController::filterInput($this->params('slug'));
        if ($slug == 'about-us') {
            $page = $contentMySqlExtDAO->queryBySlug($slug);
            $albumId = $page[0]->albumId;
            $images = $imageMySqlExtDAO->queryByAlbumId($albumId);

            $whatMakesUsSpecial = $contentMySqlExtDAO->load(93);
            $whatMakesUsSpecialAlbum = $imageMySqlExtDAO->queryByAlbumId($whatMakesUsSpecial->albumId);
            $this->layout()->htmlClass = 'content';

            $view = new ViewModel([
                'page' => $page[0],
                'images' => $images,
                'what' => $whatMakesUsSpecial,
                'whatAlbum' => $whatMakesUsSpecialAlbum,
            ]);
            $view->setTemplate('application/index/about');
            return $view;
        } else {
            $page = $contentMySqlExtDAO->queryBySlug($slug);
            $this->layout()->htmlClass = 'content header-style-2';
            $this->layout()->header2 = true;
            return new ViewModel([
                'page' => $page[0],
            ]);
        }
    }
    public function ourTeamAction()
    {
        $team = ContentController::getContent("type = 'team' ORDER BY display_order asc");
        $bannerLocation = 6;
        $banners = ContentController::getBanners($bannerLocation);
        $view = new ViewModel([
            'banner' => $banners[0],
            'team' => $team,
        ]);
        return $view;
    }
    public function ideasAndResourcesAction(){
        $team = ContentController::getContent("type = 'resource' ORDER BY display_order asc");
        $bannerLocation = 7;
        $banners = ContentController::getBanners($bannerLocation);
        $view = new ViewModel([
            'banner' => $banners[0],
            'resources' => $team,
        ]);
        return $view;
    }
    public function ideaDetailsAction(){
        $id = HelperController::filterInput($this->params('id'));
        $contentMySqlExtDAO = new ContentMySqlExtDAO();
        $pageInfo = $contentMySqlExtDAO->load($id);
        $this->layout()->htmlClass = 'header-style-2';
        $this->layout()->header2 = true;
        return new ViewModel([
            'page' => $pageInfo,
        ]);
    }
    public function testAction()
    {
        $view = new ViewModel();
        $view->setTerminal(true);
        return $view;
    }
    public function setSessionAction()
    {
        $_SESSION['currency'] = $_POST['currency'];
        $_SESSION['rate'] = $_POST['rate'];
        $response = ['currency' => $_SESSION['currency'], 'rate' => $_SESSION['rate']];
        print_r($response);
        return $this->response;
    }
}
