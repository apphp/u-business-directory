<?php
/**
 * Home controller
 *
 * PUBLIC:                  PRIVATE:
 * ---------------          ---------------
 * __construct              _checkSiteInfoAccess
 * siteinfoAction
 * socialAction
 * socialAddAction
 * socialEditAction
 * socialDeleteAction
 * socialPublishedStatusAction
 *
 */

class SiteInfoFrontendController extends CController
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
     * Site Info action handler
     * @param string $typeTab
     * @return void
     */
    public function siteinfoAction()
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('manage', 'directory', 'backend/dashboard');

        // While site not in use multisite $id = 1
        $id = 1;

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        $logo = A::app()->getRequest()->getQuery('logo');
        $favicon = A::app()->getRequest()->getQuery('favicon');

        $siteInfo = $this->_checkSiteInfoAccess($id);

        if($logo == 'delete' || $favicon == 'delete'){
            $deleteFlag = false;
            if($logo == 'delete'){
                $deleteFlag = true;
                $image = $siteInfo->logo;
                $siteInfo->logo = '';
            }else if($favicon === 'delete'){
                $deleteFlag = true;
                $image = $siteInfo->favicon;
                $siteInfo->favicon = '';
            }

            if(!empty($image)){
                $imagePath = 'images/modules/directory/siteinfo/'.$image;
            }

            if($deleteFlag){
                // Save the changes in admins table
                $siteInfo->save();
                // Delete the files
                if(!empty($imagePath) && CFile::deleteFile($imagePath)){
                    $alert = A::t('directory', 'Image successfully deleted');
                    $alertType = 'success';
                }else{
                    $alert = A::t('directory', 'Image Delete Warning');
                    $alertType = 'warning';
                }
            }else{
                $alert = A::t('directory', 'Input incorrect parameters');
                $alertType = 'error';
            }
        }

        if(!empty($alertType)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        $this->_view->id = $id;
        $this->_view->tabs = DirectoryComponent::prepareTabs('siteinfo');
        $this->_view->render('siteinfofrontend/siteinfo');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @return SiteInfo
     */
    private function _checkSiteInfoAccess($id = 0)
    {
        $model = SiteInfoFrontend::model()->findByPk($id);
        if(!$model){
            $this->redirect('backend/dashboard');
        }
        return $model;
    }
}
