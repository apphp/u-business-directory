<?php
/**
 * Webforms controller
 *
 * PUBLIC:                  PRIVATE:
 * -----------              ------------------
 * __construct
 * submitAction
 *
 */

class WebformsController extends CController
{
	
    /**
	 * Class default constructor
     */
    public function __construct()
	{
        parent::__construct();
		
		// Block access if the module is not installed
        if(!Modules::model()->exists("code = 'webforms' AND is_installed = 1")){
        	if(CAuth::isLoggedInAsAdmin()){
        		$this->redirect('modules/index');
        	}else{
        		$this->redirect('index/index');
        	}
        }
		
		// Fetch site settings info
    	$this->_settings = Bootstrap::init()->getSettings();
    }
    
	/**
	 * Controller submit action handler
	 */
    public function submitAction()
	{
		$request = A::app()->getRequest();
		$name = $request->getPost('contact_name');
		$email = $request->getPost('contact_email');
		$phone = $request->getPost('contact_phone');
		$company = $request->getPost('contact_company');
		$message = $request->getPost('contact_message');
        $captcha = $request->getPost('contact_captcha');
        $arr = array();

        $fieldName = ModulesSettings::model()->param('webforms', 'field_name');
        $fieldEmail = ModulesSettings::model()->param('webforms', 'field_email');
        $fieldPhone = ModulesSettings::model()->param('webforms', 'field_phone');
        $fieldCompany = ModulesSettings::model()->param('webforms', 'field_company');
        $fieldMessage = ModulesSettings::model()->param('webforms', 'field_message');
        $fieldCaptcha = ModulesSettings::model()->param('webforms', 'field_captcha');
        
		if(!$request->isPostRequest()){			
			$this->redirect(CConfig::get('defaultController').'/');
		}else if(APPHP_MODE == 'demo'){
			$arr[] = '"status": "0"';
        }else if($fieldName == 'show-required' && empty($name)){    
			$arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'The field name cannot be empty!').'"';
        }else if($fieldEmail == 'show-required' && empty($email)){
			$arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'The field email cannot be empty!').'"';
        }else if(!empty($email) && !CValidator::isEmail($email)){
            $arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'You must provide a valid email address!').'"';
        }else if($fieldPhone == 'show-required' && empty($phone)){
			$arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'The field phone number cannot be empty!').'"';
        }else if($fieldCompany == 'show-required' && empty($company)){
			$arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'The field company name cannot be empty!').'"';
        }else if($fieldMessage == 'show-required' && empty($message)){
			$arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'The field message cannot be empty!').'"';
        }else if(!CValidator::validateMinLength($message, 10)){
			$arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'Message must be at least 10 characters in length!').'"';
        }else if($fieldCaptcha == 'show' && $captcha === ''){
            $arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'The field captcha cannot be empty!').'"';            
        }else if($fieldCaptcha == 'show' && $captcha != A::app()->getSession()->get('captchaWebformResult')){
            $arr[] = '"status": "0"';
            $arr[] = '"error": "'.A::t('webforms', 'Sorry, the code you have entered is invalid! Please try again.').'"';            
		}else{
            $contactEmail = ModulesSettings::model()->param('webforms', 'contact_email');

			// Send email
			$body  = '<b>'.A::t('webforms', 'Name').'</b>: '.strip_tags($name)."\n";
			$body .= '<b>'.A::t('webforms', 'Email').'</b>: '.strip_tags($email)."\n";
			if($phone) $body .= '<b>'.A::t('webforms', 'Phone').'</b>: '.strip_tags($phone)."\n";
			if($company) $body .= '<b>'.A::t('webforms', 'Company').'</b>: '.strip_tags($company)."\n";
			$body .= '<b>'.A::t('webforms', 'Message').'</b>: <br>'.strip_tags($message)."\n";
	
			CMailer::config(array(
				'mailer'=>$this->_settings->mailer,
                'smtp_auth'=>$this->_settings->smtp_auth,
				'smtp_secure'=>$this->_settings->smtp_secure,
				'smtp_host'=>$this->_settings->smtp_host,
				'smtp_port'=>$this->_settings->smtp_port,
				'smtp_username'=>$this->_settings->smtp_username,
				'smtp_password'=>CHash::decrypt($this->_settings->smtp_password, CConfig::get('password.hashKey')),
			));

			$result = CMailer::send($contactEmail, A::t('webforms', 'Request from visitor ('.CConfig::get('name').')'), $body, array('from'=>$this->_settings->general_email));
			if($result){
				$arr[] = '"status": "1"';
			}else{
				$arr[] = '"status": "0"';
                $arr[] = '"error": "'.A::t('webforms', 'An error occurred while sending email! Please try again later.').'"';            
			}
		}
		
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');   // Date in the past
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // Always modified
		header('Cache-Control: no-cache, must-revalidate'); // HTTP/1.1
		header('Pragma: no-cache'); // HTTP/1.0
		header('Content-Type: application/json');

		echo '{';
		echo implode(',', $arr);
		echo '}';
	
		exit;
    }	    
    
}