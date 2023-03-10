<?php
/**
 * Business directory customer groups controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkCastomerGroupAccess
 * indexAction
 * manageAction
 * addAction
 * editAction
 * deleteAction
 * setDefaultAction
 */

class CustomerGroupsController extends CController
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
            // set meta tags according to active business directory customers
            Website::setMetaTags(array('title'=>A::t('directory', 'Customers Management')));
            // set backend mode
            Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_view->tabs = DirectoryComponent::prepareTabs('accounts');
            $this->_view->subTabs = DirectoryComponent::prepareSubTabs('accounts', 'groups');
        }
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        $this->redirect('customerGroups/manage');
    }

    /**
     * Manage action handler
     * @return void
     */
    public function manageAction()
    {
        Website::prepareBackendAction('manage', 'directory', 'customerGroups/manage');

        $alert = A::app()->getSession()->getFlash('alert');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array('success', $alert, array('button'=>true)));
        }

        $this->_view->render('customerGroups/manage');
    }

    /**
     * Add new customer group action handler
     * @return void
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'directory', 'customerGroups/manage');

        $this->_view->render('customerGroups/add');
    }

    /**
     * Edit customer group action handler
     * @param int $id the customer group id
     * @return void
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'directory', 'customerGroups/manage');
        $customerGroup = $this->_checkCastomerGroupAccess($id);

        $this->_view->id = $customerGroup->id;
        $this->_view->render('customerGroups/edit');
    }

    /**
     * Delete customer group action handler
     * @param int $id the customer group id
     * @return void
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'directory', 'customerGroups/manage');
        $customerGroup = $this->_checkCastomerGroupAccess($id);

        $alert = '';
        $alertType = '';

        if($customerGroup->delete()){
            $alert = A::t('directory', 'Customer group deleted successfully');
            $alertType = 'success';
        }else{
            if(APPHP_MODE == 'demo'){
                $alert = CDatabase::init()->getErrorMessage();
                $alertType = 'warning';
            }else{
                $alert = A::t('directory', 'Customer group deleting error');
                $alertType = 'error';
            }
        }
        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }
        $this->_view->render('customerGroups/manage');
    }
    
    
    /**
     * Change status customer group action handler
     * @param int $id the group ID
     * @return void
     */
    public function setDefaultAction($id)
    {
        Website::prepareBackendAction('edit', 'directory', 'customerGroups/manage');
        
        $group = CustomerGroups::model()->findbyPk((int)$id);
        if(!empty($group)){
            if (!$group->is_default){
                if(CustomerGroups::model()->updateByPk($group->id, array('is_default'=>'1'))){
                    CustomerGroups::model()->updateAll(array('is_default'=>0), 'id != :id', array(':id'=>$group->id));
                    A::app()->getSession()->setFlash('alert', A::t('directory', 'Status has been successfully changed!'));
                }else{
                    A::app()->getSession()->setFlash('error', A::t('directory', 'Status changing error'));
                }
            }
        }
        $this->redirect('customerGroups/manage');
    }
    

    /**
     * Check if passed customer group ID is valid
     * @param int $id the customer group id
     * @return CustomerGroups
     */
    private function _checkCastomerGroupAccess($id = 0)
    {
        $model = CustomerGroups::model()->findByPk($id);
        if(!$model){
            $this->redirect('customerGroups/manage');
        }
        return $model;
    }

}
