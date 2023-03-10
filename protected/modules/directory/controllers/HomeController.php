<?php
/**
 * Home controller
 *
 * PUBLIC:                  PRIVATE:
 * ---------------          ---------------
 * __construct
 * indexAction
 *
 */

class HomeController extends CController
{
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->_view->actionMessage = '';
        $this->_view->errorField = '';
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        // Set frontend mode
        Website::setFrontend();

        $this->_view->title = '';
        $this->_view->text = '';
        $this->_view->lastListings = array();
        $this->_view->contentHtml = '';
        $this->_view->contentCss = '';
        $this->_view->markers = '';

        if(Modules::model()->exists("code = 'directory' AND is_installed = 1")){
            $lastListings = array();
            $numberLatestListings = ModulesSettings::model()->param('directory', 'latest_listings');
            if($numberLatestListings != 'not_show'){
                $condition         = '';

                // 0 - Public, 1 - Registered
                $accessLevel = (int)CAuth::isLoggedIn();
                $condition   = DirectoryComponent::getListingCondition('not_expired');
                $condition   = CConfig::get('db.prefix').'listings.is_published = 1 AND '.CConfig::get('db.prefix').'listings.is_approved = 1 AND '.CConfig::get('db.prefix').'listings.access_level <= '.$accessLevel.' AND '.$condition;

                $lastListings = Listings::model()->findAll(array('condition'=>'','limit'=>(int)$numberLatestListings,'orderBy'=>CConfig::get('db.prefix').'listings.date_published DESC'));
            }
            $categoryContent = DirectoryComponent::prepareCategories();
            $this->_view->lastListings = $lastListings;
            $this->_view->contentHtml = $categoryContent['html'];
            $this->_view->contentCss = $categoryContent['css'];
            $this->_view->markers = DirectoryComponent::printAllMarkers();
        }
        $this->_view->render('home/index');
    }
}
