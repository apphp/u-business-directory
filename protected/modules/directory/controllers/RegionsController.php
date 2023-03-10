<?php
/**
 * Business directory locations controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkRegionAccess
 * indexAction              _prepareSubRegionCounts
 * manageAction
 * addAction
 * editAction
 * deleteAction
 * activeStatusAction
 *
 */

class RegionsController extends CController
{
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

        // block access if the module is not installed
        if(!Modules::model()->exists("code = 'directory' AND is_installed = 1")){
            if(CAuth::isLoggedInAsAdmin()){
                $this->redirect('modules/index');
            }else{
                $this->redirect('index/index');
            }
        }

        if(CAuth::isLoggedInAsAdmin()){
            // set meta tags according to active business directory locations
            Website::setMetaTags(array('title'=>A::t('directory', 'Locations Management')));
            // set backend mode
            Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            $this->_view->parentId = 0;

            $this->_view->tabs = DirectoryComponent::prepareTabs('locations');
        }
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        $this->redirect('regions/manage');
    }

    /**
     * Manage action handler
     * @param int $parentId; the parent location ID
     * @return void
     */
    public function manageAction($parentId = 0)
    {
        Website::prepareBackendAction('manage', 'directory', 'regions/manage');
        $parentId = (int)$parentId;
        $parentName = '';

        if($parentId){
            $parentRegion = $this->_checkRegionAccess($parentId);
            $parentName = $parentRegion->name;
        }

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $listingsCounts = array();
        $allListingCount = Listings::model()->count(array('select'=>'subregion_id,region_id','groupBy'=>'subregion_id,region_id','allRows'=>true));

        foreach($allListingCount as $listingCount){
            if($listingCount['subregion_id'] != 0){
                $listingsCounts[$listingCount['subregion_id']] = $listingCount['cnt'];
            }else{
                $listingsCounts[$listingCount['region_id']] = $listingCount['cnt'];
            }
        }

        $this->_view->parentId = $parentId;
        $this->_view->parentName = $parentName;
        $this->_view->listingsCounts = $listingsCounts;
        if(!$parentId){
            $this->_view->subRegionCounts = $this->_prepareSubRegionCounts();
        }
        $this->_view->render('regions/manage');
    }

    /**
     * Manage Listing in regions action handler
     * @param int $regionId; the parent location ID
     * @return void
     */
    public function manageListingsAction($regionId)
    {
        Website::prepareBackendAction('manage', 'directory', 'regions/manage');

        $subRegion = $this->_checkRegionAccess($regionId);
        $subRegionId = $subRegion->id;
        $subRegionName = $subRegion->name;
        $regionId = $subRegion->parent_id;
        if(!empty($regionId)){
            $parentRegion = $this->_checkRegionAccess($regionId);
            $regionName = $parentRegion->name;
        }else{
            $regionName = '';
        }

        $advertisePlans = Plans::model()->findAll();
        $advertisePlanNames = array();
        foreach($advertisePlans as $advertisePlan){
            $advertisePlanNames[$advertisePlan['id']] = $advertisePlan['name'];
        }

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->advertisePlanNames = $advertisePlanNames;
        $this->_view->dateTimeFormat  = Bootstrap::init()->getSettings()->datetime_format;
        $this->_view->regionId = $regionId;
        $this->_view->regionName = $regionName;
        $this->_view->subRegionId = $subRegionId;
        $this->_view->subRegionName = $subRegionName;
        $this->_view->render('regions/managelistings');
    }

    /**
     * Add new action handler
     * @param int $parentId; the parent location ID
     * @return void
     */
    public function addAction($parentId)
    {
        Website::prepareBackendAction('add', 'directory', 'regions/manage');
        $parentId = (int)$parentId;
        $parentName = '';
        if($parentId){
             $parentRegion = $this->_checkRegionAccess($parentId);
             $parentName = $parentRegion->name;
        }
        $this->_view->parentId = $parentId;
        $this->_view->parentName = $parentName;
        $this->_view->render('regions/add');
    }

    /**
     * Edit business directory locations action handler
     * @param int $id
     * @return void
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'directory', 'regions/manage');
        $id = (int)$id;
        $location = $this->_checkRegionAccess($id);
        $parentId = $location->parent_id;
        $parentName = '';
        if($parentId){
            $parentRegion = $this->_checkRegionAccess($parentId);
            $parentName = $parentRegion->name;
        }
        $this->_view->id = $id;
        $this->_view->parentId = $parentId;
        $this->_view->parentName = $parentName;
        $this->_view->render('regions/edit');
    }

    /**
     * Delete action handler
     * @param int $id
     * @return void
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'directory', 'regions/manage');
        $location = $this->_checkRegionAccess($id);
        $parentId = $location->parent_id;

        $issetSubRegions = Regions::model()->find('`parent_id`= :id', array(':id' => $id)) ? true : false;
        $alert = '';
        $alertType = '';
        if($issetSubRegions){
            $alert = A::t('directory', 'You cannot delete this location');
            $alertType = 'error';
        }else{
            // check if default
            if($location->delete()){
                $alert = A::t('directory', 'Delete Success Message');
                $alertType = 'success';
            }else{
                if(APPHP_MODE == 'demo'){
                    $alert = CDatabase::init()->getErrorMessage();
                    $alertType = 'warning';
                }else{
                    $alert = A::t('directory', 'Delete Error Message');
                    $alertType = 'error';
                }
            }
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $parentName = '';
        if($parentId){
            $parentRegion = $this->_checkRegionAccess($parentId);
            $parentName = $parentRegion->name;
        }

        $this->_view->parentId = $parentId;
        $this->_view->parentName = $parentName;
        $this->_view->subRegionCounts = $this->_prepareSubRegionCounts();
        $this->_view->render('regions/manage');
    }

    /**
     * Change status location action handler
     * @param int $id the location or sub-location ID
     * @return void
     */
    public function activeStatusAction($id)
    {
        Website::prepareBackendAction('edit', 'directory', 'regions/manage');

        $location = Regions::model()->findbyPk((int)$id);
        $parentId = $location->parent_id;

        if(!empty($location)){
            if(Regions::model()->updateByPk($location->id, array('is_active'=>($location->is_active == 1 ? '0' : '1')))){
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', 'error');
            }
        }

        $this->redirect('regions/manage/parentId/'.$parentId);
    }

    /**
     * Check if passed location ID is valid
     * @param int $id
     * @return Regions
     */
    private function _checkRegionAccess($id = 0)
    {
        $location = Regions::model()->findByPk($id);
        if(!$location){
            $this->redirect('regions/manage');
        }
        return $location;
    }

    /**
     * Prepares array of total counts for each sub-location
     * @return array
     */
    private function _prepareSubRegionCounts()
    {
        $result = Regions::model()->count(array('condition'=>'', 'select' => 'parent_id', 'count'=>'*', 'groupBy'=>'parent_id', 'allRows'=>true));
        $subRegionCounts = array();
        foreach($result as $key => $model){
            $subRegionCounts[$model['parent_id']] = $model['cnt'];
        }
        return $subRegionCounts;
    }
}
