<?php
/**
 * Business directory advertise plans controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkPlanAccess
 * indexAction              _prepareFieldValues
 * manageAction
 * viewAction
 * addAction
 * editAction
 * deleteAction
 * setDefaultAction
 *
 *
 */

class PlansController extends CController
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
            // set meta tags according to active business directory advertise plans
            Website::setMetaTags(array('title'=>A::t('directory', 'Advertise Plans Management')));

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_view->tabs = DirectoryComponent::prepareTabs('plans');
        }
        $this->_view->currencySymbol     = A::app()->getCurrency('symbol');
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        $this->redirect('plans/manage');
    }

    /**
     * Manage action handler
     * @return void
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'directory', 'plans/manage');
        // set backend mode
        Website::setBackend();

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_prepareFieldValues();
        $this->_view->render('plans/manage');
    }

    /**
     * View description advertise plans
     * @return void
     */
    public function viewAction()
    {
        $advertisePlans = Plans::model()->findAll();
        $this->_view->advertisePlans = $advertisePlans;
        $this->_view->yes = '<span class="badge-green">'.A::t('directory', 'Yes').'</span>';
        $this->_view->no = '<span class="badge-red">'.A::t('directory', 'No').'</span>';
        $this->_prepareFieldValues();

        $this->_view->render('plans/view');
    }

    /**
     * Add new action handler
     * @return void
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'directory', 'plans/manage');
        // set backend mode
        Website::setBackend();

        $this->_prepareFieldValues();
        $this->_view->render('plans/add');
    }

    /**
     * Edit business directory advertise plans action handler
     * @param int $id
     * @return void
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'directory', 'plans/manage');
        // set backend mode
        Website::setBackend();
        $plan = $this->_checkPlanAccess($id);

        $this->_prepareFieldValues();
        $this->_view->id = $plan->id;
        $this->_view->render('plans/edit');
    }

    /**
     * Delete action handler
     * @param int $id
     * @return void
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'directory', 'plans/manage');
        // set backend mode
        Website::setBackend();
        $model = $this->_checkPlanAccess($id);

        $alert = '';
        $alertType = '';
        if(Listings::model()->find('advertise_plan_id = :advertise_plan_id', array('i:advertise_plan_id' => $model->id))){
            $alert = A::t('directory', 'You cannot delete this advertise plan');
            $alertType = 'error';
        }else if($model->delete()){
            if($model->getError()){
                $alert = A::t('directory', 'Delete Warning Message');
                $alertType = 'warning';
            }else{
                $alert = A::t('directory', 'Delete Success Message');
                $alertType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('directory', 'Delete Error Message');
                $alertType = 'error';
            }
        }

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($alertType, $alert, array('button'=>true))
            );
        }

        $this->_prepareFieldValues();
        $this->_view->render('plans/manage');
    }

    /**
     * Change status customer group action handler
     * @param int $id the group ID
     * @return void
     */
    public function setDefaultAction($id)
    {
        Website::prepareBackendAction('edit', 'directory', 'plans/manage');
        // set backend mode
        Website::setBackend();

        $plans = Plans::model()->findbyPk((int)$id);
        if(!empty($plans)){
            if (!$plans->is_default){
                if(Plans::model()->updateByPk($plans->id, array('is_default'=>'1'))){
                    Plans::model()->updateAll(array('is_default'=>0), 'id != :id', array(':id'=>$plans->id));
                    A::app()->getSession()->setFlash('alert', A::t('directory', 'Status has been successfully changed!'));
                    A::app()->getSession()->setFlash('alertType', 'success');
                }else{
                    A::app()->getSession()->setFlash('alert', A::t('directory', 'Status changing error'));
                    A::app()->getSession()->setFlash('alertType', 'error');
                }
            }
        }
        $this->redirect('plans/manage');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return Plans
     */
    private function _checkPlanAccess($id = 0)
    {
        $model = Plans::model()->findByPk($id);
        if(!$model){
            $this->redirect('plans/manage');
        }
        return $model;
    }

    /**
     * Prepare fields: inquiries, categories, durations, validDurations, validInquiries, validCategories (in View)
     * @return void
     */
    private function _prepareFieldValues()
    {
        $durations = array(A::t('directory', '- select -'));
        $inquiries = array(-2=>A::t('directory', '- select -'));
        $categories = array(A::t('directory', '- select -'));
        $validDurations = array(1,2,3,4,5,6,7,8,9,10,14,21,28,30,45,60,90,120,180,240,270,365,730,1095,1460,1825,-1);
        $validInquiries = array(0,1,2,3,4,5,6,7,8,9,10,15,20,30,40,50,75,100,150,200,250,500,750,1000,-1);
        $validCategories = array(1,2,3,4,5,6,7,8,9,10);

        foreach($validDurations as $days){
            if($days < 0){
                $durations[$days] = A::t('directory', 'Unlimited');
            }else if($days < 30){
                $durations[$days] = ($days == 1 ? '1 '.A::t('directory', 'Day') : $days.' '.A::t('directory', 'Days'));
            }else if($days < 365){
                $durations[$days] = (round($days/30,1) == 1 ? '1 '.A::t('directory', 'Month') : round($days/30,1).' '.A::t('directory', 'Months'));
            }else{
                $durations[$days] = (round($days/365,1) == 1 ? '1 '.A::t('directory', 'Year') : round($days/365,1).' '.A::t('directory', 'Years'));
            }
        }

        // array(-2=>'- select -',0=>0,1=>1,2=>2,...,-1=>-1)
        $inquiries += array_combine($validInquiries, $validInquiries);
        $inquiries[-1] = A::t('directory', 'Unlimited');

        // array(0=>'- select -',1=>1,2=>2,...)
        $categories += array_combine($validCategories, $validCategories);

        $this->_view->inquiries = $inquiries;
        $this->_view->categories = $categories;
        $this->_view->durations = $durations;
        $this->_view->validDurations  = $validDurations;
        $this->_view->validInquiries  = $validInquiries;
        $this->_view->validCategories = $validCategories;
    }
}
