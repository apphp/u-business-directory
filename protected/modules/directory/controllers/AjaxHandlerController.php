<?php
/**
 * AjaxHandler controller
 * This controller is intended for both Backend and Frontend modes
 *
 * PUBLIC:                      PRIVATE
 * -----------                  ------------------
 * __construct                  _output
 * findCoordinatesAction
 * getSubRegionsAction
 * getAllCategoriesAction
 * getLocationsHtmlAction
 * indexAction
 *
 */

class AjaxHandlerController extends CController
{
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Block access if the module is not installed
        if(!Modules::model()->exists("code = 'directory' AND is_installed = 1")){
            if(CAuth::isLoggedInAsAdmin()){
                $this->redirect('modules/index');
            }else{
                $this->redirect(Website::getDefaultPage());
            }
        }

        // Block access if this is not AJAX request
        $this->_cRequest = A::app()->getRequest();
        if(!$this->_cRequest->isAjaxRequest()){
            $this->redirect(Website::getDefaultPage());
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->_output();
    }

    /**
     * Returns coordinates of given address
     * @return json
     */
    public function findCoordinatesAction()
    {
        $arr = array();
        $arr[] = '"status": "0"';

        if(CAuth::isLoggedIn()){
            $address = $this->_cRequest->getPost('address');
            $act = $this->_cRequest->getPost('act');

            if($act == 'send' && !empty($address)){

                $coordinates = CGeoLocation::coordinatesByAddress($address);
                if(!empty($coordinates['longitude']) && !empty($coordinates['latitude'])){
                    $arr[] = '"status": "1"';
                    $arr[] = '"longitude" : '.$coordinates['longitude'];
                    $arr[] = '"latitude" : '.$coordinates['latitude'];
                }else{
                    $arr[] = '"status": "0"';
                }
            }
        }

        $this->_output($arr, false);
    }

    /**
     * Returns sublocations
     * @return json
     */
    public function getSubRegionsAction()
    {
        $arr = array();
        $act = $this->_cRequest->getPost('act');


        if($act == 'send'){
            $arr[] = '{"status": "1"}';

            $result = Regions::model()->findAll('parent_id = :id', array('i:id'=>$this->_cRequest->getPost('parent_id')));
            if($result){
                foreach($result as $key => $val){
                    $arr[] = '{"id": "'.$val['id'].'", "name": "'.$val['name'].'"}';
                }
            }

        }else{
            $arr[] = '{"status": "0"}';
        }

        $this->_output($arr, true);
    }

    /**
     * Returns all Categories
     * @return json
     */
    public function getAllCategoriesAction()
    {
        $arr = array();

        $result = DirectoryComponent::getAllCategoriesArray();
        if($result){
            foreach($result as $key => $val){
                $arr[] = '{"value": "'.$key.'", "label": "'.$val.'"}';
            }
        }

        $this->_output($arr, true);
    }

    /**
     * Returns all Listings
     * @return json
     */
    public function getAllLocationsAction()
    {
        $arr = array();

        $result = DirectoryComponent::getAllRegionsArray();
        if($result){
            foreach($result as $key => $val){
                $arr[] = '{"value": "'.$key.'", "label": "'.$val.'"}';
            }
        }

        $this->_output($arr, true);
    }

    /**
     * Returns Listings
     * @return json
     */
    public function getLocationsHtmlAction()
    {
        $arr = array();
        $nl = "\n";

        $act = $this->_cRequest->getPost('act');

        if($act == 'send'){
            $categoryId = (int)$this->_cRequest->getPost('category_id');
            $locationId = (int)$this->_cRequest->getPost('region_id');
            $subLocationId = (int)$this->_cRequest->getPost('subregion_id');

            $result = CWidget::create('CFormValidation', array(
                'fields' => array(
                    'category_id' => array('title'=>A::t('directory', 'Category'), 'validation'=>array('required'=>false, 'type'=>'int', 'maxValue'=>11)),
                    'region_id' => array('title'=>A::t('directory', 'Location'), 'validation'=>array('required'=>false, 'type'=>'int', 'maxValue'=>11)),
                    'subregion_id' => array('title'=>A::t('directory', 'Sub-Location'), 'validation'=>array('required'=>false, 'type'=>'int', 'maxValue'=>11)),
                ),
                'messagesSource' => 'core',
                'showAllErrors' => false
            ));
            if(!empty($result['error'])){
                $alert = str_replace('"', "'", $result['errorMessage']);
                $fieldError = $result['errorField'];

                $arr[] = '{"status": 0, "message": "'.$alert.'", "fieldError": "'.$fieldError.'"}';
            }else{
                $params = array();
                $accessLevel = (int)CAuth::isLoggedIn();
                $condition   = DirectoryComponent::getListingCondition('not_expired');
                $condition   = 'is_published = 1 AND is_approved = 1 AND access_level <= '.$accessLevel.' AND '.$condition;

                if(!empty($categoryId)){
                    $condition  .= ' AND category_id = :category_id';
                    $params['i:category_id'] = $categoryId;
                }

                if(!empty($locationId)){
                    $condition  .= ' AND region_id = :region_id';
                    $params['i:region_id'] = (int)$locationId;
                    if(!empty($subLocationId)){
                        $condition .= ' AND subregion_id = :subregion_id';
                        $params['i:subregion_id'] = (int)$subLocationId;
                    }
                }

                $plans = array();
                $allPlans = Plans::model()->findAll();
                if(!empty($allPlans)){
                    foreach($allPlans as $plan){
                        $plans[$plan['id']] = $plan['inquiries_count'];
                    }
                }

                // Check whether the number of inquiries exceeds the last month
                $switchPlan  = '(SELECT CASE '.CConfig::get('db.prefix').'listings.advertise_plan_id ';
                foreach($plans as $key => $number){
                    $switchPlan .= 'WHEN '.(int)$key.' THEN '.(int)$number."\n";
                }

                $switchPlan .= 'END)';
                $condition .= ' AND ('.$switchPlan.' = -1 OR '.$switchPlan.' > (SELECT COUNT(*) FROM '.CConfig::get('db.prefix').'inquiries WHERE date_created > DATE_SUB(NOW(), INTERVAL 1 MONTH) AND '.CConfig::get('db.prefix').'inquiries.listing_id = '.CConfig::get('db.prefix').'listings_categories.listing_id))';

                $listings    = ListingsCategories::model()->findAll(array('condition'=>$condition, 'orderBy'=>CConfig::get('db.prefix').'listings.is_featured DESC, '.CConfig::get('db.prefix').'listings.date_published DESC', 'limit'=>'5'), $params);
                if(is_array($listings) && !empty($listings)){
                    $arr[] = '{"status": "1"}';
                    $i = 0;
                    $output = '<fieldset>
                        <legend>'.A::te('directory', 'Search Result').'</legend>'.$nl;
                    foreach($listings as $listing){
                        $output .= '<div class="row">
                            <input id="listing'.++$i.'" class="listing-checkbox" checked="checked" value="'.CHtml::encode($listing['listing_id']).'" name="listing'.$i.'" type="checkbox" />
                            <div class="inquiries-listing">
                                <div class="listing-image">
                                    <a href="listings/view/id/'.$listing['listing_id'].'" target="_blank">
                                        <img src="images/modules/directory/listings/thumbs/'.(!empty($listing['image_file_thumb']) ? CHtml::encode($listing['image_file_thumb']) : 'no_logo.jpg').'" />
                                    </a>
                                </div>
                                <div class="listings-description-block">
                                    <h3 class="listing-name">
                                        <a href="listings/view/id/'.$listing['listing_id'].'" target="_blank">'.CHtml::encode($listing['business_name']).'</a>
                                    </h3>
                                    <div class="listing-description">'.CHtml::encode(CString::substr($listing['business_description'], 350, false, true)).'</div>
                                </div>
                            </div>
                            <div class="title-send">'.A::te('directory', 'Send Email').'</div>
                        </div>'.$nl;
                    }
                    $output .= '</fieldset>';
                    $output = str_replace(array('"', "\r\n", "\n", "\t"), array("'", '', '', ''), $output);
                    $arr[] = '{"content": "'.$output.'"}';
                }else if(empty($listings)){
                    $arr[] = '{"status": "1", "empty": "0"}';
                    $output = '<fieldset>
                        <legend>'.A::te('directory', 'Search Result').'</legend>'.$nl;
                    $output .= '<h3 class="listing-empty">'.A::te('directory', 'Sorry, but your search did not found any listing').'</h3>';
                    $output .= '</fieldset>';
                    $output = str_replace(array('"', "\r\n", "\n", "\t"), array("'", '', '', ''), $output);
                    $arr[] = '{"content": "'.$output.'", "empty": "1"}';
                }
            }
        }

        $this->_output($arr, true);
    }


    /**
     * Outputs data to browser
     * @param $array $data
     * @param string $returnArray
    */
    private function _output($data = array(), $returnArray = true)
    {
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Content-Type: application/json');

        if($returnArray){
            echo '[';
            echo array($data) ? implode(',', $data) : '';
            echo ']';
        }else{
            echo '{';
            echo implode(',', $data);
            echo '}';
        }

        exit;
    }

}

