<?php
/**
 * Pages controller
 *
 * PUBLIC:                  PRIVATE:
 * -----------         		------------------
 * __construct              _getShortcodes 
 * indexAction              _prepareTab
 * manageAction				_checkPageAccess
 * addAction
 * insertAction
 * editAction
 * updateAction
 * deleteAction
 * viewAction
 *
 */

class PagesController extends CController
{
	
    /**
	 * Class default constructor
     */
    public function __construct()
	{
        parent::__construct();
                
        // block access if the module is not installed
        if(!Modules::model()->exists("code = 'cms' AND is_installed = 1")){        
        	if(CAuth::isLoggedInAsAdmin()){
        		$this->redirect('modules/index');
        	}else{
        		$this->redirect('pages/view');
        	}
        }        
	
		if(CAuth::isLoggedInAsAdmin()){
	        // set meta tags according to active language
	        Website::setMetaTags(array('title'=>A::t('cms', 'Pages Management')));

	        $this->_view->actionMessage = '';
	        $this->_view->errorField = '';
	        
			$this->_view->tabs = $this->_prepareTab('pages');		
		}
	}
	
	/**
	 * Controller default action handler
	 */
    public function indexAction()
	{
		if(CAuth::isLoggedInAsAdmin()){
			$this->redirect('pages/manage');
		}else{
			$this->redirect('pages/view');
		}		
    }	
      
    /**
     * Manage pages action handler
     */
    public function manageAction()
    {        
        Website::prepareBackendAction('manage', 'pages', 'pages/manage');

    	$this->_view->render('pages/manage');
    }
    
    /**
     * Add new page action handler
     */
    public function addAction()
    {
        Website::prepareBackendAction('add', 'pages', 'pages/manage');
        
        // get default meta tags for the new page
    	if($siteInfo = SiteInfo::model()->find('language_code = :lang', array(':lang'=>A::app()->getLanguage()))){
            $this->_view->tagTitle = $siteInfo->meta_title;
            $this->_view->tagKeywords = $siteInfo->meta_keywords;
            $this->_view->tagDescription = $siteInfo->meta_description;
        }
        
    	$this->_view->pageHeader = '';
        $this->_view->pageText = '';
        $this->_view->publishStatus = 1;
        $this->_view->langList = Languages::model()->findAll('is_active = 1');
        $this->_view->language = A::app()->getLanguage();
        $this->_view->isHomepage = 0;
        $this->_view->sortOrder = 0;
		$this->_view->shortCodes = $this->_getShortcodes();
				
        $this->_view->render('pages/add');
    }
    
    /**
     * Insert new page action handler
     */
    public function insertAction()
    {
        Website::prepareBackendAction('insert', 'pages', 'pages/manage');
        
    	$cRequest = A::app()->getRequest();
        $cRequest->getCsrfTokenValue();
    	$msg = '';
    	$msgType = '';
		$this->_view->shortCodes = $this->_getShortcodes();		

    	if($cRequest->getPost('act') == 'send'){
    		// add page form submit
 	        $this->_view->pageHeader = $cRequest->getPost('page_header');
	        $this->_view->pageText = $cRequest->getPost('page_text');
	        $this->_view->tagTitle = $cRequest->getPost('tag_title');
	        $this->_view->tagKeywords = $cRequest->getPost('tag_keywords');
	        $this->_view->tagDescription = $cRequest->getPost('tag_description');
	        $this->_view->publishStatus = $cRequest->getPost('publish_status');
	        $this->_view->langList = Languages::model()->findAll('is_active = 1');
	        $this->_view->language = A::app()->getLanguage();
	        $this->_view->isHomepage = $cRequest->getPost('is_homepage');
	        $this->_view->sortOrder = $cRequest->getPost('sort_order');
    		$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'page_header'    =>array('title'=>A::t('cms', 'Page Header'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255)),
					'page_text'		 =>array('title'=>A::t('cms', 'Page Text'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>10000)),
					'tag_title' 	 =>array('title'=>A::t('cms', 'Tag TITLE'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255)),
					'tag_keywords' 	 =>array('title'=>A::t('cms', 'Tag KEYWORDS'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255)),
					'tag_description'=>array('title'=>A::t('cms', 'Tag DESCRIPTION'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255)),
					'publish_status' =>array('title'=>A::t('cms', 'Published'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array(0,1))),
					'is_homepage'    =>array('title'=>A::t('cms', 'Home Page'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1))),
					'sort_order'     =>array('title'=>A::t('cms', 'Sort Order'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>5)),
				),
    		));
    		if($result['error']){
    			$msg = $result['errorMessage'];
    			$msgType = 'validation';    
    			$this->_view->errorField = $result['errorField'];
    		}else{
   				$page = new Pages();
   				$page->tag_title = $this->_view->tagTitle;
   				$page->tag_keywords = $this->_view->tagKeywords;
   				$page->tag_description = $this->_view->tagDescription;
   				$page->publish_status = $this->_view->publishStatus;
   				$page->is_homepage = $this->_view->isHomepage;
   				$page->sort_order = $this->_view->sortOrder;
   				$page->created_at = LocalTime::currentDateTime();
   				
   				// use the same translation fields for all active languages 
   				$translationsArray = array();
   				if(is_array($this->_view->langList)){
					foreach($this->_view->langList as $lang){
						$translationsArray[$lang['code']] = array(
                            'tag_title' => $this->_view->tagTitle,
                            'tag_keywords' => $this->_view->tagKeywords,
                            'tag_description' => $this->_view->tagDescription,
							'page_header' => $this->_view->pageHeader,
							'page_text' => $this->_view->pageText,
						);
					}
   				}
				
				$page->setTranslationsArray($translationsArray);	
   				if($page->save()){
  					if($page->getError()){
	    				$msg = A::t('cms', 'New Page Warning Message');
	    				$msgType = 'warning';
	    			}else{
						$msg = A::t('cms', 'New Page Success Message');
						$msgType = 'success';	    					
	    			}
   					$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
   					$this->_view->render('pages/manage');
   					return;   
   				}else{
					if(APPHP_MODE == 'demo'){
						$msg = CDatabase::init()->getErrorMessage();
						$msgType = 'warning';
					}else{
						$msg = A::t('cms', 'New Page Error Message');
						$msgType = 'error';
					}
   				}
    		}
    		if(!empty($msg)){
    			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
    		}
    		$this->_view->render('pages/add');
    		 
    	}else{
    		$this->redirect('pages/manage');
    	}
    }
    
    /**
     * Page edit action handler
     * @param int $id the page id
     */
    public function editAction($id = 0)
    {
        Website::prepareBackendAction('edit', 'pages', 'pages/manage');

    	$cRequest = A::app()->getRequest();
        if($cRequest->getPost('act') == 'send'){
    		$this->_view->language = $cRequest->getPost('language'); 
    		$id = $cRequest->getPost('id'); 
    	}else{
	        $this->_view->language = A::app()->getLanguage();
       	}
		
		$page = $this->_checkPageAccess($id);

		$this->_view->page = $page;
       	$translationsArray = $page->selectTranslations();
        $this->_view->tagTitle = isset($translationsArray[$this->_view->language]) ? $translationsArray[$this->_view->language]['tag_title'] : '';
        $this->_view->tagKeywords = isset($translationsArray[$this->_view->language]) ? $translationsArray[$this->_view->language]['tag_keywords'] : '';
        $this->_view->tagDescription = isset($translationsArray[$this->_view->language]) ? $translationsArray[$this->_view->language]['tag_description'] : '';
        $this->_view->pageHeader = isset($translationsArray[$this->_view->language]) ? $translationsArray[$this->_view->language]['page_header'] : '';
        $this->_view->pageText = isset($translationsArray[$this->_view->language]) ? $translationsArray[$this->_view->language]['page_text'] : '';
        $this->_view->langList = Languages::model()->findAll('is_active = 1');
		$this->_view->shortCodes = $this->_getShortcodes();
		
    	$this->_view->render('pages/edit');
    }
    
    /**
     * Update page action handler
     */
    public function updateAction()
    {
        Website::prepareBackendAction('update', 'pages', 'pages/manage');

        $cRequest = A::app()->getRequest();
		$this->_view->shortCodes = $this->_getShortcodes();
    	$msg = '';
    	$msgType = '';
        
    	if($cRequest->getPost('act') == 'send'){
            $page = $this->_checkPageAccess($cRequest->getPost('id', 'int'));

	        $this->_view->tagTitle = $cRequest->getPost('tag_title');
	        $this->_view->tagKeywords = $cRequest->getPost('tag_keywords');
	        $this->_view->tagDescription = $cRequest->getPost('tag_description');
 	        $this->_view->pageHeader = $cRequest->getPost('page_header');
	        $this->_view->pageText = $cRequest->getPost('page_text');
	        $page->publish_status = $cRequest->getPost('publish_status');
	        $this->_view->langList = Languages::model()->findAll('is_active = 1');
	        $this->_view->language = $cRequest->getPost('language');
	        $page->is_homepage = ($cRequest->isPostExists('is_homepage')) ? $cRequest->getPost('is_homepage', '', 0) : 1;
	        $page->sort_order = $cRequest->getPost('sort_order');
    		$this->_view->page = $page;
    
    		$result = CWidget::create('CFormValidation', array(
				'fields'=>array(
					'page_header'    =>array('title'=>A::t('cms', 'Page Header'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>255)),
					'page_text'		 =>array('title'=>A::t('cms', 'Page Text'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>10000)),
					'tag_title' 	 =>array('title'=>A::t('cms', 'Tag TITLE'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255)),
					'tag_keywords' 	 =>array('title'=>A::t('cms', 'Tag KEYWORDS'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255)),
					'tag_description'=>array('title'=>A::t('cms', 'Tag DESCRIPTION'), 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>255)),
					'publish_status' =>array('title'=>A::t('cms', 'Published'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array(0,1))),
					'language'		 =>array('title'=>A::t('cms', 'Language'), 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array_keys($this->_view->langList))),
					'is_homepage'    =>array('title'=>A::t('cms', 'Home Page'), 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1))),
					'sort_order'     =>array('title'=>A::t('cms', 'Sort Order'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>5)),
				),
    		));
    		if($result['error']){
    			$msg = $result['errorMessage'];
    			$msgType = 'validation';    
    			$this->_view->errorField = $result['errorField'];
    		}else{
    			// update modified_at time to current time
    			$page->modified_at = LocalTime::currentDateTime();
    			
    			$translationsArray[$this->_view->language] = array(
                    'tag_title' => $this->_view->tagTitle,
                    'tag_keywords' => $this->_view->tagKeywords,
                    'tag_description' => $this->_view->tagDescription,
					'page_header' => $this->_view->pageHeader,
					'page_text' => $this->_view->pageText,
				);
    			 
   				// save changes and update data in translation table
				$page->setTranslationsArray($translationsArray);
				if($page->save()){
					if($page->getError()){
	    				$msg = A::t('cms', 'Page Update Error Message');
	    				$msgType = 'warning';
	    			}else{
						$msg = A::t('cms', 'Page Update Success Message');
						$msgType = 'success';	    					
	    			}
					$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
                    if($cRequest->isPostExists('btnUpdateClose')){
                        $this->_view->render('pages/manage');
                        return;
                    }   
				}else{
					$this->_view->errorField = '';
					if(APPHP_MODE == 'demo'){
						$msg = CDatabase::init()->getErrorMessage();
						$msgType = 'warning';
					}else{
						$msg = A::t('cms', 'Page Update Error Message');
						$msgType = 'error';
					}
    			}
    		}
    		if(!empty($msg)){
    			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
    		}    			
    		$this->_view->render('pages/edit');    			
    	}else{
    		$this->redirect('pages/manage');
    	}
    }
    
    /**
     * Delete page action handler
     * @param int $id the page id
     */
    public function deleteAction($id = 0)
    {
        Website::prepareBackendAction('delete', 'pages', 'pages/manage');
		$page = $this->_checkPageAccess($id);

        $msg = '';
    	$msgType = '';
    
        if($page->delete()){				
			$msg = A::t('cms', 'Page Delete Success Message');
			$msgType = 'success';	
		}else{
			if(APPHP_MODE == 'demo'){
				$msg = CDatabase::init()->getErrorMessage();
				$msgType = 'warning';
		   	}else{
				$msg = A::t('cms', 'Page Delete Error Message');
				$msgType = 'error';
		   	}			
		}
		if(!empty($msg)){
			$this->_view->actionMessage = CWidget::create('CMessage', array($msgType, $msg, array('button'=>true)));
		}
		$this->_view->render('pages/manage');
    }   

	/**
	 * View action handler - views the page in front-end
	 * @param int $id the page id, use 0 for homepage 
	 */
	public function viewAction($id = 0)
	{
        // set frontend mode
        Website::setFrontend();
    	 	
		$this->_view->isHomePage = true;
		$this->_view->title = '';
		$this->_view->text = '';
		$this->_view->_activeMenu = 'pages/view';
				
		// view regular page
		if(!empty($id)){
			$pageModel = Pages::model()->findByPk((int)$id, 'publish_status = 1');
			if(!empty($pageModel)){
				$this->_view->isHomePage = false;
				$this->_view->title = $pageModel->page_header;
				$this->_view->text = $pageModel->page_text;
				
                $totalLanguages = Languages::model()->count("is_active = 1 AND used_on IN ('front-end', 'global')");
                if($totalLanguages){
                    // Use this for multi-language sites
					$basePath = A::app()->getRequest()->getBasePath();
					$requestUri = A::app()->getRequest()->getRequestUri();

					if( $basePath == '/'){
						// Don't replace all $basePath if it equals single slash "/", otherwise it replaces all slashes in URI
						$this->_view->_activeMenu = ltrim($requestUri, '/');	
					}else{
						$this->_view->_activeMenu = str_ireplace($basePath, '', $requestUri);	
					}					
                }else{
                    // Use this for single-language sites
                    ///$this->_view->_activeMenu = 'pages/view/id/'.$pageModel->id;
                    $this->_view->_activeMenu = Website::prepareLinkByFormat('cms', 'page_link_format', $pageModel->id, $pageModel->page_header);
                }

				$render	= 'pages/view';
				// set meta tags
				if($pageModel->tag_title != '') $this->_view->setMetaTags('title', $pageModel->tag_title);
				if($pageModel->tag_keywords != '') $this->_view->setMetaTags('keywords', $pageModel->tag_keywords);
				if($pageModel->tag_description != '') $this->_view->setMetaTags('description', $pageModel->tag_description);
			}else{
				$this->redirect('error/index/code/404');	
			}
		}else{				
			// id == 0 or page is not found
			// view home page
			if($homepage = Pages::model()->find('is_homepage = 1')){
				$this->_view->isHomePage = true;
				$this->_view->title = $homepage->page_header;
				$this->_view->text = $homepage->page_text;								
				// set meta tags
				if($homepage->tag_title != '') $this->_view->setMetaTags('title', $homepage->tag_title);
				if($homepage->tag_keywords != '') $this->_view->setMetaTags('keywords', $homepage->tag_keywords);
				if($homepage->tag_description != '') $this->_view->setMetaTags('description', $homepage->tag_description);
			}
		}

		// draw short codes of modules		
		$modulesCodes = ModulesSettings::model()->getShortcodes();		
		if(is_array($modulesCodes)){
			$moduleText = $this->_view->text;
			foreach($modulesCodes as $key => $val){
                // prepare params
                $params = explode('!', trim($val['value'], '{}'));
                array_shift($params);
                
                if(preg_match('/'.$val['value'].'/i', $moduleText)){
                    if(empty($val['class_code'])){
    					$moduleName = ucfirst($key);
                    }else{
                        $moduleName = $val['class_code'];
                    }
                    if(method_exists($moduleName.'Component', 'drawShortcode')){
                        $moduleText = preg_replace('/'.$val['value'].'/i', call_user_func_array($moduleName.'Component::drawShortcode', array($params)), $moduleText, 1);    
                    }else{
                        CDebug::addMessage('errors', 'component-error', A::t('core', 'Component or method does not exist: {component}', array('{component}'=>$moduleName.'Component::drawShortcode()')));
                    }                    
                }
			}
			$this->_view->text = $moduleText;
		}
		
		$this->_view->render('pages/view');	
	}

    /**
     * Prepares cms module tabs
     * @param string $activeTab : default - pages
     */
    private function _prepareTab($activeTab = 'pages')
    {
    	return CWidget::create('CTabs', array(
			'tabsWrapper'=>array('tag'=>'div', 'class'=>'title'),
			'tabsWrapperInner'=>array('tag'=>'div', 'class'=>'tabs'),
			'contentWrapper'=>array(),
			'contentMessage'=>'',
			'tabs'=>array(
				A::t('cms', 'Settings') => array('href'=>'modules/settings/code/cms', 'id'=>'tabSettings', 'content'=>'', 'active'=>false, 'htmlOptions'=>array('class'=>'modules-settings-tab')),
				A::t('cms', 'Pages')  	=> array('href'=>'pages/manage', 'id'=>'tabPages', 'content'=>'', 'active'=>($activeTab == 'pages' ? true : false)),
			),
			'events'=>array(
				//'click'=>array('field'=>$errorField)
			),
			'return'=>true,
    	));
    }
    
	/**
	 * Check if passed page with given ID exists
	 * @param int $pageId
	 */
	private function _checkPageAccess($pageId = 0)
	{        
		$page = Pages::model()->findByPk((int)$pageId);
		if(!$page){
			$this->redirect('pages/manage');
		}
        return $page;       
	}    

    /**
     * Returns all available shortcodes
     */
    private function _getShortcodes()
    {
		$shortCodes = array();
		$modulesCodes = ModulesSettings::model()->getShortcodes();		
		if(is_array($modulesCodes)){			
			foreach($modulesCodes as $key => $val){
				$shortCodes[] = array('value'=>$val['value'], 'description'=>$val['description']);
			}
		}
		
		return $shortCodes;
	}    
  
}
