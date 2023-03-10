<?php
/**
 * Banners controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                 	PRIVATE:
 * ---------------         	---------------				
 * __construct              _checkActionAccess
 * indexAction              
 * manageAction
 * changeStatusAction
 * addAction
 * editAction
 * deleteAction
 * viewAllAction
 *
 */

class BannersController extends CController
{
	
    /**
     * Class default constructor
     */
    public function __construct()
    {
        parent::__construct();

        // Block access if module is not installed
        if(!Modules::model()->exists("code = 'banners' AND is_installed = 1")){
            if(CAuth::isLoggedIn()){
                $this->redirect('modules/index');
            }else{
                $this->redirect('index/index');
            }
        }

        if(CAuth::isLoggedIn()){        
            // Set meta tags according to active BannersController
            Website::setMetaTags(array('title'=>A::t('banners', 'Banners Management')));

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';
            
            $this->_view->tabs = BannersComponent::prepareTab('banners');
        }
    }

    /**
     * Controller default action handler
     */
    public function indexAction()
    {
        $this->redirect('banners/manage');
    }
    
    /**
     * Manage action handler
     * @param string $msg 
     */
    public function manageAction($msg = '')
    {
        Website::prepareBackendAction('manage', 'banners', 'banners/manage');

		if(A::app()->getSession()->hasFlash('message')){
            $msg = A::app()->getSession()->getFlash('message');
        }
		
		$messageType = 'success';
		
        switch($msg){
            case 'added':
                $message = A::t('banners', 'Added new banner!');
                break;
            case 'updated':
                $message = A::t('banners', 'Banner has been successfully updated!');
                break;
            case 'changed':
                $message = A::t('banners', 'Status has been successfully changed!');
                break;
            case 'change-error':
				$message = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('app', 'Status changing error');
				$messageType = (APPHP_MODE == 'demo') ? 'warning' : 'error';
                break;
            default:
                $message = '';
        }
		
        if(!empty($message)){
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($messageType, $message, array('button'=>true))
            );
        }
		
        $this->_view->render('banners/manage');        
    }

	/**
     * Change banner state method
     * @param int $id
    */
    public function changeStatusAction($id)
    {

        Website::prepareBackendAction('edit', 'banners', 'banners/manage');

        $banners = $this->_checkActionAccess($id);
		$result = Banners::model()->updateByPk($id, array('is_active'=>($banners->is_active == 1 ? '0' : '1')));

        if($result){
            A::app()->getSession()->setFlash('message', 'changed');
        }else{
            A::app()->getSession()->setFlash('message', 'change-error');
        }
        
        $this->redirect('banners/manage');        
    }
	
    /**
     * Add new action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'banners', 'banners/manage');
        $this->_view->render('banners/add');
    }

    /**
     * Edit Banners edit handler
     * @param int $id
	 * @param string $image
     */
    public function editAction($id = 0, $image = '')
    {
        Website::prepareBackendAction('edit', 'banners', 'banners/manage/id/'.$id);
        $banners = $this->_checkActionAccess($id);
		
        // Delete the image file
        if($image === 'delete'){
        	$msg = '';
        	$msgType = '';
        	$image = 'images/modules/banners/'.$banners->image_file;
            $imageThumb = 'images/modules/banners/'.$banners->image_file_thumb;
        	$banners->image_file = '';
            $banners->image_file_thumb = '';

        	// Save the changes in banners table
        	if($banners->save()){
        		// Delete images
        		if(CFile::deleteFile($image) && CFile::deleteFile($imageThumb)){
        			$msg = A::t('banners', 'Banner successfully deleted');
        			$msgType = 'success';
        		}else{
        			$msg = A::t('banners', 'There was a problem removing the banner');
        			$msgType = 'warning';
        		}
        	}else{
        		$msg = A::t('banners', 'Error removing banner');
        		$msgType = 'error';
        	}
        	if(!empty($msg)){
        		$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
        	}
        }
        $this->_view->banners = $banners;
        $this->_view->render('banners/edit');
    }

    /**
     * Delete action handler
     * @param int $id  
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'banners', 'banners/manage');
        $model = $this->_checkActionAccess($id);

        $msg = '';
        $errorType = '';
    
        // Check if default
        if($model->is_default){
            $msg = A::t('app', 'Delete Default Alert');
            $errorType = 'error';
        }else if($model->delete()){
            if($model->getError()){
                $msg = A::t('app', 'Delete Warning Message');
                $errorType = 'warning';
            }else{
                $msg = A::t('app', 'Delete Success Message');
                $errorType = 'success';
            }
        }else{
            if(APPHP_MODE == 'demo'){
                $msg = CDatabase::init()->getErrorMessage();
                $errorType = 'warning';
            }else{
                $msg = A::t('app', 'Delete Error Message');
                $errorType = 'error';
            }
        }
        if(!empty($msg)){
            $this->_view->actionMessage = CWidget::create(
                'CMessage', array($errorType, $msg, array('button'=>true))
            );
        }
        $this->_view->render('banners/manage');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     */
    private function _checkActionAccess($id = 0)
    {        
        $model = Banners::model()->findByPk($id);
        if(!$model){
            $this->redirect('banners/manage');
        }
        return $model;
    }    
 
}