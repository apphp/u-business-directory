<?php
/**
 * Business directory Reviews controller
 * This controller intended to both Backend and Frontend modes
 *
 * PUBLIC:                  PRIVATE
 * -----------              ------------------
 * __construct              _checkReviewAccess
 * indexAction
 * manageAction
 * previewAction
 * deleteAction
 * sendCommentAction
 *
 */

class ReviewsController extends CController
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
            // set meta tags according to active business directory reviews
            Website::setMetaTags(array('title'=>A::t('directory', 'Reviews Management')));
            // set backend mode
            Website::setBackend();

            $this->_view->actionMessage = '';
            $this->_view->errorField = '';

            $this->_view->tabs = DirectoryComponent::prepareTabs('reviews');
        }

        $this->_view->dateTimeFormat  = Bootstrap::init()->getSettings()->datetime_format;
    }

    /**
     * Controller default action handler
     * @return void
     */
    public function indexAction()
    {
        $this->redirect('reviews/manage');
    }

    /**
     * Manage action handler
     * @param string $typeTab
     * @return void
     */
    public function manageAction($typeTab = '')
    {
        Website::prepareBackendAction('manage', 'directory');

        $typeTab  = (in_array($typeTab, array('pending', 'approved')) ? $typeTab : 'pending');
        $cRequest = A::app()->getRequest();
        $filter   = $cRequest->getQuery('but_filter', 'string');
        $isFilter = strtolower($filter) == 'filter' ? true : false;
        $getUrl   = '';

        $alert = A::app()->getSession()->getFlash('alert');
        $alertType = A::app()->getSession()->getFlash('alertType');

        if(!empty($alert)){
            $this->_view->actionMessage = CWidget::create('CMessage', array($alertType, $alert, array('button'=>true)));
        }

        // Save settings filter
        if($isFilter){
            $name      = $cRequest->getQuery('business_name', 'string');
            $customer  = $cRequest->getQuery('customer_name', 'string');
            $createdAt = $cRequest->getQuery('created_at', 'string');
            $published = $cRequest->getQuery('is_public', 'int');

            $getUrl = $name || $customer || $createdAt || $published
                ? '?'.($name      ? 'business_name='.$name.'&' : '')
                     .($customer  ? 'customer_name='.$customer.'&' : '')
                     .($createdAt ? 'region_id='.$createdAt.'&' : '')
                     .($published ? 'is_published='.$published.'&' : '')
                     .'but_filter=Filter'
                : '';
        }
        $this->_view->getUrl  = $getUrl;
        $this->_view->typeTab = $typeTab;
        $this->_view->render('reviews/manage');
    }

    /**
     * Preview Business Directory reviews action handler
     *
     * @param int $id
     * @param string $typeTab
     * @access public
     * @return void
     */
    public function previewAction($id = 0, $typeTab = '')
    {
        Website::prepareBackendAction('edit', 'directory', 'reviews/manage');
        // Join table 'listings' to reviews
        $review = $this->_checkReviewAccess($id);

        $typeTab = (in_array($typeTab, array('approved', 'pending')) ? $typeTab : 'pending');
        $this->_view->typeTab        = $typeTab;
        $this->_view->reviewId       = $review->id;
        $this->_view->listingName    = $review->listing_name;
        $this->_view->listingId      = $review->listing_id;
        $this->_view->customerName   = $review->customer_name;
        $this->_view->customerEmail  = $review->customer_email;
        $this->_view->message        = $review->message;
        $this->_view->dateCreated    = $review->created_at;
        $this->_view->ratingPrice    = $review->rating_price;
        $this->_view->ratingLocation = $review->rating_location;
        $this->_view->ratingServices = $review->rating_services;
        $this->_view->ratingStaff    = $review->rating_staff;
        $this->_view->isPublic       = $review->is_public;

        $this->_view->render('reviews/preview');
    }

    /**
     * Delete action handler
     * @param int $id
     * @param string $typeTab
     * @return void
     */
    public function deleteAction($id = 0, $typeTab = '')
    {
        $typeTab = (in_array($typeTab, array('approved', 'pending')) ? $typeTab : 'pending');

        Website::prepareBackendAction('delete', 'directory', 'reviews/manage/typeTab/'.$typeTab);
        $review = $this->_checkReviewAccess($id, $typeTab);

        $alert = '';
        $alertType = '';

        if($review->delete()){
            if($review->getError()){
                $alert     = A::t('directory', 'Delete Warning Message');
                $alertType = 'warning';
            }else{
                $alert     = A::t('directory', 'Delete Success Message');
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

        $this->_view->typeTab = $typeTab;
        $this->_view->getUrl = '';
        $this->_view->render('reviews/manage');
    }

    /**
     * Save Review action handler
     * @return json
     */
    public function sendCommentAction()
    {
        // Block access if this is not AJAX request
        $cRequest = A::app()->getRequest();
        if(!$cRequest->isAjaxRequest() || !$cRequest->isPostRequest()){
            $this->redirect(Website::getDefaultPage());
        }

        if($cRequest->getPost('act') != 'send'){
            $this->redirect(Website::getDefaultPage());
        }else if(APPHP_MODE == 'demo'){
            $arr[] = '"status": "0"';
        }else{
            // Perform validation
            $fields = array();
            $fields['listingId'] = array('title'=>A::t('directory', 'Listing'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'minValue'=>0));
            if(!CAuth::isLoggedIn() || CAuth::isLoggedInAsAdmin()){
                $fields['name'] = array('title'=>A::t('directory', 'Name'), 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>65));
                $fields['email'] = array('title'=>A::t('directory', 'Email'), 'validation'=>array('required'=>true, 'type'=>'email', 'maxLength'=>100));
            }
            $fields['description'] = array('title'=>A::t('directory', 'Description'), 'validation'=>array('required'=>true, 'type'=>'text', 'minLength'=>10, 'maxLength'=>2048));
            $fields['ratingPrice'] = array('title'=>A::t('directory', 'Rating Price'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>1, 'maxValue'=>5, 'minValue'=>0));
            $fields['ratingLocation'] = array('title'=>A::t('directory', 'Rating Location'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>1, 'maxValue'=>5, 'minValue'=>0));
            $fields['ratingStaff'] = array('title'=>A::t('directory', 'Rating Staff'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>1, 'maxValue'=>5, 'minValue'=>0));
            $fields['ratingServices'] = array('title'=>A::t('directory', 'Rating Services'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxLength'=>1, 'maxValue'=>5, 'minValue'=>0));
            $captcha = $cRequest->getPost('captcha');

            $result = CWidget::create('CFormValidation', array(
                'fields'=>$fields
            ));

            if($result['error']){
                $arr[] = '"status": "0"';
                $arr[] = '"error": "'.$result['errorMessage'].'"';
                $arr[] = '"error_field": "'.$result['errorField'].'"';
            }else if($captcha === ''){
                $arr[] = '"status": "0"';
                $arr[] = '"error_field": "captcha_validation"';
                $arr[] = '"error": "'.A::t('directory', 'The field captcha cannot be empty!').'"';
            }else if($captcha != A::app()->getSession()->get('reviewsCaptchaResult')){
                $arr[] = '"status": "0"';
                $arr[] = '"error_field": "captcha_validation"';
                $arr[] = '"error": "'.A::t('directory', 'Sorry, the code you have entered is invalid! Please try again.').'"';
            }else{
                $listingId      = $cRequest->getPost('listingId');

                $accessLevel = (int)CAuth::isLoggedIn();
                $condition   = DirectoryComponent::getListingCondition('not_expired');
                $condition   = 'is_published = 1 AND is_approved = 1 AND access_level <= '.$accessLevel.' AND '.$condition;
                $listing     = Listings::model()->findByPk($listingId, $condition);

                if(empty($listing)){
                    $arr[] = '"status": "0"';
                    $arr[] = '"error": "'.A::t('directory', 'Access is denied').'"';
                }else{
                    $email = '';
                    $name = '';
                    $customerId = 0;

                    if(CAuth::isLoggedIn() && !CAuth::isLoggedInAsAdmin()){
                        $accountId = CAuth::getLoggedId();
                        $customer = Customers::model()->find('account_id = :account_id', array(':account_id'=>$accountId));
                        if(!empty($customer)){
                            $customerId = $customer->id;
                            $name       = $customer->first_name.' '.$customer->last_name;
                            $email      = $customer->email;
                        }
                    }else{
                        $name       = $cRequest->getPost('name');
                        $email      = $cRequest->getPost('email');
                    }
                    // User leave a comment on this listing
                    $review = Reviews::model()->find('listing_id = :listing_id AND customer_email = :email', array(':listing_id'=>$listingId, ':email'=>$email));
                    if(!empty($review)){
                        $arr[] = '"status": "0"';
                        $arr[] = '"error": "'.A::t('directory', 'You leave a review on this listing').'"';
                    }else{
                        $preModeartion = ModulesSettings::model()->param('directory', 'reviews_moderation') == 1 ? true : false;

                        $description    = $cRequest->getPost('description');
                        $ratingPrice    = $cRequest->getPost('ratingPrice');
                        $ratingLocation = $cRequest->getPost('ratingLocation');
                        $ratingStaff    = $cRequest->getPost('ratingStaff');
                        $ratingServices = $cRequest->getPost('ratingServices');
                        $ratingValue    = sprintf('%.2f', ($ratingPrice + $ratingLocation + $ratingStaff + $ratingServices)/4);

                        $createdAt      = LocalTime::currentDateTime();
                        $isPublic       = $preModeartion ? 0 : 1;

                        $reviews = new Reviews();
                        $reviews->listing_id      = $listingId;
                        $reviews->customer_id     = $customerId;
                        $reviews->customer_email  = $email;
                        $reviews->customer_name   = $name;
                        $reviews->message         = $description;
                        $reviews->rating_price    = $ratingPrice;
                        $reviews->rating_location = $ratingLocation;
                        $reviews->rating_staff    = $ratingStaff;
                        $reviews->rating_services = $ratingServices;
                        $reviews->rating_value    = $ratingValue;
                        $reviews->created_at      = $createdAt;
                        $reviews->is_public       = $isPublic;

                        if($reviews->save()){
                            if($preModeartion){
                                $alert = '<div class="alert alert-info">'.A::te('directory', 'Review is waiting for confirmation').'</div>';
                                $alert = str_replace('"', "'", $alert);
                                $result = str_replace(array("\r\n", "\n", "\t"), '', $alert);
                                $arr[] = '"status": "1"';
                                $arr[] = '"html": "'.$result.'"';
                            }else{
                                $comment = '
                                <div class="user-rating">
                                    <div class="user-values">
                                        <div class="rating clearfix">
                                            <div class="rating-title">'.A::t('directory', 'Price').'</div>
                                            <div class="user-stars clearfix">
                                                <div class="star'.($ratingPrice >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                                <div class="star'.($ratingPrice >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                                <div class="star'.($ratingPrice >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                                <div class="star'.($ratingPrice >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                                <div class="star'.($ratingPrice == 5 ? ' active' : '').'" data-star-id="5"></div>
                                            </div>
                                        </div>
                                        <div class="rating clearfix">
                                        <div class="rating-title">'.A::t('directory', 'Location').'</div>
                                            <div class="user-stars clearfix">
                                                <div class="star'.($ratingLocation >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                                <div class="star'.($ratingLocation >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                                <div class="star'.($ratingLocation >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                                <div class="star'.($ratingLocation >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                                <div class="star'.($ratingLocation == 5 ? ' active' : '').'" data-star-id="5"></div>
                                            </div>
                                        </div>
                                        <div class="rating clearfix">
                                            <div class="rating-title">'.A::t('directory', 'Staff').'</div>
                                            <div class="user-stars clearfix">
                                                <div class="star'.($ratingStaff >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                                <div class="star'.($ratingStaff >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                                <div class="star'.($ratingStaff >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                                <div class="star'.($ratingStaff >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                                <div class="star'.($ratingStaff == 5 ? ' active' : '').'" data-star-id="5"></div>
                                            </div>
                                        </div>

                                        <div class="rating clearfix">
                                            <div class="rating-title">'.A::t('directory', 'Services').'</div>
                                            <div class="user-stars clearfix">
                                                <div class="star'.($ratingServices >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                                <div class="star'.($ratingServices >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                                <div class="star'.($ratingServices >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                                <div class="star'.($ratingServices >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                                <div class="star'.($ratingServices == 5 ? ' active' : '').'" data-star-id="5"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="user-details">
                                        <div class="name">'.CHtml::encode($name).'</div>
                                        <div class="date">'.LocalTime::currentDateTime($this->_view->dateTimeFormat).'</div>
                                        <div class="value">
                                            <div class="star'.($ratingValue >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                            <div class="star'.($ratingValue >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                            <div class="star'.($ratingValue >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                            <div class="star'.($ratingValue >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                            <div class="star'.($ratingValue == 5 ? ' active' : '').'" data-star-id="5"></div>
                                        </div>
                                        <div class="description">'.CHtml::encode($description).'</div>
                                    </div>
                                </div>';

                                $comment = str_replace('"', "'", $comment);
                                $result = str_replace(array("\r\n", "\n", "\t"), '', $comment);
                                $arr[] = '"status": "1"';
                                $arr[] = '"html": "'.$result.'"';
                            }
                        }
                    }
                }
            }
        }

        if(empty($arr)){
            $arr[] = '"status": "0"';
        }

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Content-Type: application/json');

        echo '{';
        echo implode(',', $arr);
        echo '}';

        exit;
    }

    /**
     * View more hendler action
     * @return void
     */
    public function viewMoreAction()
    {
        // Block access if this is not AJAX request
        $cRequest = A::app()->getRequest();
        if(!$cRequest->isAjaxRequest() || !$cRequest->isPostRequest()){
            $this->redirect(Website::getDefaultPage());
        }

        if($cRequest->getPost('act') != 'send'){
            $this->redirect(Website::getDefaultPage());
        }else{
            // Perform validation
            $fields = array();
            $fields['listingId'] = array('title'=>A::t('directory', 'Listing'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'minValue'=>0));
            $fields['start'] = array('title'=>A::t('directory', 'Start Listing'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'minValue'=>0, 'maxLength'=>11));
            $fields['count'] = array('title'=>A::t('directory', 'Number Listings'), 'validation'=>array('required'=>true, 'type'=>'numeric', 'maxValue'=>20, 'minValue'=>1));

            $result = CWidget::create('CFormValidation', array(
                'fields'=>$fields
            ));

            if($result['error']){
                $arr[] = '"status": "0"';
                $arr[] = '"error": "'.$result['errorMessage'].'"';
                $arr[] = '"error_field": "'.$result['errorField'].'"';
            }else{
                $listingId = $cRequest->getPost('listingId');
                $start     = $cRequest->getPost('start');
                $count     = $cRequest->getPost('count');

                $accessLevel = (int)CAuth::isLoggedIn();
                $condition   = DirectoryComponent::getListingCondition('not_expired');
                $condition   = CConfig::get('db.prefix').'listings.is_published = 1 AND '.
                    CConfig::get('db.prefix').'listings.is_approved = 1 AND '.
                    CConfig::get('db.prefix').'listings.access_level <= '.$accessLevel.' AND '.$condition.' AND '.
                    CConfig::get('db.prefix').'reviews.listing_id = :listing_id AND '.
                    CConfig::get('db.prefix').'reviews.is_public = 1';
                Reviews::model()->setTypeRelations('listings');
                $reviews     = Reviews::model()->findAll(array(
                        'condition'=>$condition, 
                        'orderBy'=>'created_at DESC',
                        'limit'=>((int)$start.', '.((int)$count+1))                     // Learn 'count'+1 to make sure that there is still Reviews
                    ),
                    array(':listing_id'=>$listingId)
                );
                Reviews::model()->resetTypeRelations();

                if(empty($reviews)){
                    $arr[] = '"status": "0"';
                    $arr[] = '"error": "'.A::t('directory', 'Could not get reviews').'"';
                }else{
                    if(count($reviews) < ($count+1)){
                        $endReview = true;
                    }else{
                        array_pop($reviews);
                        $endReview = false;
                    }

                    $comment = '';
                    foreach($reviews as $review){
                        $ratingPrice    = $review['rating_price'];
                        $ratingLocation = $review['rating_location'];
                        $ratingStaff    = $review['rating_staff'];
                        $ratingServices = $review['rating_services'];
                        $ratingValue    = $review['rating_value'];
                        $name           = $review['customer_name'];
                        $date           = $review['created_at'];
                        $description    = $review['message'];
                        $comment .= '
                    <div class="user-rating">
                        <div class="user-values">
                            <div class="rating clearfix">
                                <div class="rating-title">'.A::t('directory', 'Price').'</div>
                                <div class="user-stars clearfix">
                                    <div class="star'.($ratingPrice >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                    <div class="star'.($ratingPrice >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                    <div class="star'.($ratingPrice >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                    <div class="star'.($ratingPrice >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                    <div class="star'.($ratingPrice == 5 ? ' active' : '').'" data-star-id="5"></div>
                                </div>
                            </div>
                            <div class="rating clearfix">
                            <div class="rating-title">'.A::t('directory', 'Location').'</div>
                                <div class="user-stars clearfix">
                                    <div class="star'.($ratingLocation >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                    <div class="star'.($ratingLocation >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                    <div class="star'.($ratingLocation >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                    <div class="star'.($ratingLocation >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                    <div class="star'.($ratingLocation == 5 ? ' active' : '').'" data-star-id="5"></div>
                                </div>
                            </div>
                            <div class="rating clearfix">
                                <div class="rating-title">'.A::t('directory', 'Staff').'</div>
                                <div class="user-stars clearfix">
                                    <div class="star'.($ratingStaff >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                    <div class="star'.($ratingStaff >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                    <div class="star'.($ratingStaff >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                    <div class="star'.($ratingStaff >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                    <div class="star'.($ratingStaff == 5 ? ' active' : '').'" data-star-id="5"></div>
                                </div>
                            </div>

                            <div class="rating clearfix">
                                <div class="rating-title">'.A::t('directory', 'Services').'</div>
                                <div class="user-stars clearfix">
                                    <div class="star'.($ratingServices >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                    <div class="star'.($ratingServices >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                    <div class="star'.($ratingServices >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                    <div class="star'.($ratingServices >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                    <div class="star'.($ratingServices == 5 ? ' active' : '').'" data-star-id="5"></div>
                                </div>
                            </div>
                        </div>
                        <div class="user-details">
                            <div class="name">'.CHtml::encode($name).'</div>
                            <div class="date">'.$date.'</div>
                            <div class="value">
                                <div class="star'.($ratingValue >= 1 ? ' active' : '').'" data-star-id="1"></div>
                                <div class="star'.($ratingValue >= 2 ? ' active' : '').'" data-star-id="2"></div>
                                <div class="star'.($ratingValue >= 3 ? ' active' : '').'" data-star-id="3"></div>
                                <div class="star'.($ratingValue >= 4 ? ' active' : '').'" data-star-id="4"></div>
                                <div class="star'.($ratingValue == 5 ? ' active' : '').'" data-star-id="5"></div>
                            </div>
                            <div class="description">'.CHtml::encode($description).'</div>
                        </div>
                    </div>';

                    }

                    $comment = str_replace('"', "'", $comment);
                    $result = str_replace(array("\r\n", "\n", "\t"), '', $comment);
                    $arr[] = '"status": "1"';
                    $arr[] = '"html": "'.$result.'"';
                    $arr[] = '"endReviews": "'.($endReview ? '1' : '0').'"';
                }
            }
        }

        if(empty($arr)){
            $arr[] = '"status": "0"';
        }

        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // date in the past
        header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
        header('Pragma: no-cache'); // HTTP/1.0
        header('Content-Type: application/json');

        echo '{';
        echo implode(',', $arr);
        echo '}';

        exit;
    }

    /**
     * Change status approved listing action handler
     * @param int $id the menu ID
     * @return void
     */
    public function changeStatusAction($id)
    {
        // set backend mode
        Website::setBackend();
        Website::prepareBackendAction('edit', 'directory', 'reviews/manage');

        $review = Reviews::model()->findbyPk((int)$id);
        if(!empty($review)){
            if(Reviews::model()->updateByPk($review->id, array('is_public'=>($review->is_public ? 0 : 1)))){
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status has been successfully changed!'));
                A::app()->getSession()->setFlash('alertType', 'success');
            }else{
                A::app()->getSession()->setFlash('alert', A::t('directory', 'Status changing error'));
                A::app()->getSession()->setFlash('alertType', 'error');
            }
        }
        $this->redirect('reviews/manage/');
    }

    /**
     * Check if passed record ID is valid
     * @param int $id
     * @param string $typeTab
     * @return Reviews
     */
    private function _checkReviewAccess($id = 0, $typeTab = '')
    {
        $review = Reviews::model()->findByPk($id);
        if(!$review){
            $fullTypeTab = !empty($typeTab) && in_array($typeTab, array('pending', 'approved')) ? '/typeTab/'.$typeTab : '';
            $this->redirect('reviews/manage'.$fullTypeTab);
        }
        return $review;
    }

}
