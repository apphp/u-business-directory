<?php
    $this->_pageTitle = A::t('directory', 'Customer Registration');

    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
?>
<article id="page-registration" class="page-registration page-customers type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Customer Registration'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label' => A::t('directory', 'Customer Registration'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
        <?php
            echo CHtml::openTag('div', array('style'=>'display:none', 'id'=>'messageSuccess'));
            echo CHtml::tag('p', array('class'=>'alert alert-success'), $messageSuccess);
            echo CHtml::tag('p', array('class'=>'alert alert-info'), $messageInfo);
            echo CHtml::closeTag('div');


            echo CHtml::openTag('div', array('class'=>'registration-form-content'));
            echo CHtml::openForm('directory/registration', 'post', array('name'=>'registration-form', 'id'=>'registration-form')) ;
            echo CHtml::hiddenField('act', 'send');

            //echo CHtml::tag('p',array(),A::t('directory', 'Please fill out the form below to perform registration.'));
            $errorMsg = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('directory', 'An error occurred while registration process! Please try again later.');
            echo CHtml::tag('p', array('style'=>'display:none', 'class'=>'alert alert-error','id'=>'messageError'), $errorMsg);


            $personalInfo = '';
            if($fieldFirstName == 'allow-optional' || $fieldFirstName == 'allow-required'){
                $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
                $personalInfo .= CHtml::tag('label', array(), (($fieldFirstName == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'First Name'));
                $personalInfo .= CHtml::textField('first_name', '', array('data-required'=>($fieldFirstName == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'32', 'autocomplete'=>'off'));
                if($fieldFirstName == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'firstNameErrorEmpty'), A::t('directory', 'The field first name cannot be empty!'));
                $personalInfo .= CHtml::closeTag('div');
            }
            if($fieldLastName == 'allow-optional' || $fieldLastName == 'allow-required'){
                $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
                $personalInfo .= CHtml::tag('label', array(), (($fieldLastName == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Last Name'));
                $personalInfo .= CHtml::textField('last_name', '', array('data-required'=>($fieldLastName == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'32', 'autocomplete'=>'off'));
                if($fieldLastName == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'lastNameErrorEmpty'), A::t('directory', 'The field last name cannot be empty!'));
                $personalInfo .= CHtml::closeTag('div');
            }
            if($fieldBirthDate == 'allow-optional' || $fieldBirthDate == 'allow-required'){
                $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
                $personalInfo .= CHtml::tag('label', array(), (($fieldBirthDate == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Birth Date'));
                $personalInfo .= CHtml::textField('birth_date', '', array('data-required'=>($fieldBirthDate == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'10', 'autocomplete'=>'off'));
                if($fieldBirthDate == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'birthDateErrorEmpty'), A::t('directory', 'The field birth date cannot be empty!'));
                $personalInfo .= CHtml::closeTag('div');

                $format = 'yy-mm-dd';
                A::app()->getClientScript()->registerCssFile('js/vendors/jquery/jquery-ui.min.css');
                A::app()->getClientScript()->registerScriptFile('js/vendors/jquery/jquery-ui.min.js', 1);
                /* formats: dd/mm/yy | d M, y | mm/dd/yy  | yy-mm-dd  | */
                A::app()->getClientScript()->registerScript(
                    'datepicker',
                    '$("#birth_date").datepicker({
                        showOn: "button",
                        buttonImage: "js/vendors/jquery/images/calendar.png",
                        buttonImageOnly: true,
                        showWeek: false,
                        firstDay: 1,
                        maxDate: -1,
                        dateFormat: "'.$format.'",
                        changeMonth: true,
                        changeYear: true,
                        appendText : "'.A::t('directory', 'Format').': yyyy-mm-dd"
                    });'
                );
            }
            if($fieldWebsite == 'allow-optional' || $fieldWebsite == 'allow-required'){
                $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
                $personalInfo .= CHtml::tag('label', array(), (($fieldWebsite == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Website'));
                $personalInfo .= CHtml::textField('website', '', array('data-required'=>($fieldWebsite == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'255', 'autocomplete'=>'off'));
                if($fieldWebsite == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'websiteErrorEmpty'), A::t('directory', 'The field website cannot be empty!'));
                $personalInfo .= CHtml::closeTag('div');
            }
            if($fieldCompany == 'allow-optional' || $fieldCompany == 'allow-required'){
                $personalInfo .= CHtml::openTag('div', array('class'=>'row'));
                $personalInfo .= CHtml::tag('label', array(), (($fieldCompany == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Company'));
                $personalInfo .= CHtml::textField('company', '', array('data-required'=>($fieldCompany == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
                if($fieldCompany == 'allow-required') $personalInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'companyErrorEmpty'), A::t('directory', 'The field company cannot be empty!'));
                $personalInfo .= CHtml::closeTag('div');
            }
            if(!empty($personalInfo)){
                echo CHtml::openTag('fieldset');
                echo CHtml::tag('legend', '', A::t('directory', 'Personal Information'));
                echo $personalInfo;
                echo CHtml::closeTag('fieldset');
            }


            $contactInfo = '';
            if($fieldPhone == 'allow-optional' || $fieldPhone == 'allow-required'){
                $contactInfo .= CHtml::openTag('div', array('class'=>'row'));
                $contactInfo .= CHtml::tag('label', array(), (($fieldPhone == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Phone'));
                $contactInfo .= CHtml::textField('phone', '', array('data-required'=>($fieldPhone == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
                if($fieldPhone == 'allow-required') $contactInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'phoneErrorEmpty'), A::t('directory', 'The field phone cannot be empty!'));
                $contactInfo .= CHtml::closeTag('div');
            }
            if($fieldFax == 'allow-optional' || $fieldFax == 'allow-required'){
                $contactInfo .= CHtml::openTag('div', array('class'=>'row'));
                $contactInfo .= CHtml::tag('label', array(), (($fieldFax == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Fax'));
                $contactInfo .= CHtml::textField('fax', '', array('data-required'=>($fieldFax == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
                if($fieldFax == 'allow-required') $contactInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'faxErrorEmpty'), A::t('directory', 'The field fax cannot be empty!'));
                $contactInfo .= CHtml::closeTag('div');
            }
            if(!empty($contactInfo)){
                echo CHtml::openTag('fieldset');
                echo CHtml::tag('legend', '', A::t('directory', 'Contact Information'));
                echo $contactInfo;
                echo CHtml::closeTag('fieldset');
            }


            $addressInfo = '';
            if($fieldAddress == 'allow-optional' || $fieldAddress == 'allow-required'){
                $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
                $addressInfo .= CHtml::tag('label', array(), (($fieldAddress == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Address'));
                $addressInfo .= CHtml::textField('address', '', array('data-required'=>($fieldAddress == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
                if($fieldAddress == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'addressErrorEmpty'), A::t('directory', 'The field address cannot be empty!'));
                $addressInfo .= CHtml::closeTag('div');
            }
            if($fieldAddress2 == 'allow-optional' || $fieldAddress2 == 'allow-required'){
                $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
                $addressInfo .= CHtml::tag('label', array(), (($fieldAddress2 == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Address (line 2)'));
                $addressInfo .= CHtml::textField('address_2', '', array('data-required'=>($fieldAddress2 == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
                if($fieldAddress2 == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'address2ErrorEmpty'), A::t('directory', 'The field address (line 2) cannot be empty!'));
                $addressInfo .= CHtml::closeTag('div');
            }
            if($fieldCity == 'allow-optional' || $fieldCity == 'allow-required'){
                $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
                $addressInfo .= CHtml::tag('label', array(), (($fieldCity == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'City'));
                $addressInfo .= CHtml::textField('city', '', array('data-required'=>($fieldCity == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
                if($fieldCity == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'cityErrorEmpty'), A::t('directory', 'The field city cannot be empty!'));
                $addressInfo .= CHtml::closeTag('div');
            }
            if($fieldZipCode == 'allow-optional' || $fieldZipCode == 'allow-required'){
                $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
                $addressInfo .= CHtml::tag('label', array(), (($fieldZipCode == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Zip Code'));
                $addressInfo .= CHtml::textField('zip_code', '', array('data-required'=>($fieldZipCode == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
                if($fieldZipCode == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'zipcodeErrorEmpty'), A::t('directory', 'The field zip code cannot be empty!'));
                $addressInfo .= CHtml::closeTag('div');
            }
            if($fieldCountry == 'allow-optional' || $fieldCountry == 'allow-required'){
                $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
                $addressInfo .= CHtml::tag('label', array(), (($fieldCountry == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Country'));
                $onchange = ($fieldState == 'allow-optional' || $fieldState == 'allow-required') ? "customers_ChangeCountry('registration-form',this.value)" : '';
                $addressInfo .= CHtml::dropDownList('country_code', $defaultCountryCode, $countries, array('data-required'=>($fieldCountry == 'allow-required' ? 'true' : 'false'), 'onchange'=>$onchange));
                if($fieldCountry == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'countrycodeErrorEmpty'), A::t('directory', 'The field country cannot be empty!'));
                $addressInfo .= CHtml::closeTag('div');
            }
            if($fieldState == 'allow-optional' || $fieldState == 'allow-required'){
                $addressInfo .= CHtml::openTag('div', array('class'=>'row'));
                $addressInfo .= CHtml::tag('label', array(), (($fieldState == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'State/Province'));
                $addressInfo .= CHtml::textField('state', '', array('data-required'=>($fieldState == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'64', 'autocomplete'=>'off'));
                if($fieldState == 'allow-required') $addressInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'stateErrorEmpty'), A::t('directory', 'The field state cannot be empty!'));
                $addressInfo .= CHtml::closeTag('div');
            }
            if($addressInfo){
                echo CHtml::openTag('fieldset');
                echo CHtml::tag('legend', '', A::t('directory', 'Address Information'));
                echo $addressInfo;
                echo CHtml::closeTag('fieldset');
            }


            $accountInfo = '';
            if($fieldEmail == 'allow-optional' || $fieldEmail == 'allow-required'){
                $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
                $accountInfo .= CHtml::tag('label', array(), (($fieldEmail == 'allow-required') ? ' &#42; ' : '').A::t('directory', 'Email'));
                $accountInfo .= CHtml::textField('email', '', array('data-required'=>($fieldEmail == 'allow-required' ? 'true' : 'false'), 'maxlength'=>'128', 'autocomplete'=>'off'));
                if($fieldEmail == 'allow-required') $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorEmpty'), A::t('directory', 'The field email cannot be empty!'));
                $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorValid'), A::t('directory', 'You must provide a valid email address!'));
                $accountInfo .= CHtml::closeTag('div');
            }
            if($fieldUsername == 'allow'){
                $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
                $accountInfo .= CHtml::tag('label', array(), (($fieldUsername == 'allow') ? ' &#42; ' : '').A::t('directory', 'Username'));
                $accountInfo .= CHtml::textField('username', '', array('data-required'=>'true', 'maxlength'=>'25', 'autocomplete'=>'off'));
                $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'usernameErrorEmpty'), A::t('directory', 'The field username cannot be empty!'));
                $accountInfo .= CHtml::closeTag('div');
            }
            if($fieldPassword == 'allow'){
                $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
                $accountInfo .= CHtml::tag('label', array(), (($fieldPassword == 'allow') ? ' &#42; ' : '').A::t('directory', 'Password'));
                $accountInfo .= CHtml::passwordField('password', '', array('data-required'=>'true', 'maxlength'=>'20', 'autocomplete'=>'off'));
                $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'passwordErrorEmpty'), A::t('directory', 'The field password cannot be empty!'));
                $accountInfo .= CHtml::closeTag('div');
            }
            if($fieldConfirmPassword == 'allow'){
                $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
                $accountInfo .= CHtml::tag('label', array(), (($fieldConfirmPassword == 'allow') ? ' &#42; ' : '').A::t('directory', 'Confirm Password'));
                $accountInfo .= CHtml::passwordField('confirm_password', '', array('data-required'=>'true', 'maxlength'=>'20', 'autocomplete'=>'off'));
                $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'confpasswordErrorEmpty'), A::t('directory', 'The field confirm password cannot be empty!'));
                $accountInfo .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'confpasswordErrorEqual'), A::t('directory', 'The password field must match the password confirmation field!'));
                $accountInfo .= CHtml::closeTag('div');
            }
            if($fieldNotifications == 'allow'){
                $accountInfo .= CHtml::openTag('div', array('class'=>'row'));
                $accountInfo .= CHtml::tag('label', array('for'=>'notifications'), A::t('directory', 'Send Notifications'));
                $accountInfo .= CHtml::checkBox('notifications', false, array());
                $accountInfo .= CHtml::closeTag('div');
            }
            if($accountInfo){
                echo CHtml::openTag('fieldset');
                echo CHtml::tag('legend', '', A::t('directory', 'Account Information'));
                echo $accountInfo;
                echo CHtml::closeTag('fieldset');
            }


            if($fieldCaptcha == 'allow'){
                echo CHtml::openTag('fieldset');
                echo CHtml::tag('legend', '', A::t('directory', 'Human Verification'));
                echo CHtml::openTag('div', array('class'=>'row'));
                echo CWidget::create('CCaptcha', array('math', true, array('return'=>true)));
                echo CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'captchaError'), A::t('directory', 'The field captcha cannot be empty!'));
                echo CHtml::closeTag('div');
                echo CHtml::closeTag('fieldset');
            }

            echo CHtml::openTag('div', array('class'=>'row'));
            echo CHtml::tag('button', array('type'=>'button', 'class'=>'button', 'data-sending'=>A::t('directory', 'Sending...'), 'data-send'=>A::t('directory', 'Send'), 'onclick'=>'javascript:customers_RegistrationForm(this)'), A::t('directory', 'Register'));
            echo CHtml::closeTag('div');
            echo CHtml::closeForm();
            echo CHtml::closeTag('div');


            if($fieldCountry !== 'no' && $fieldState !== 'no'){
                A::app()->getClientScript()->registerScript(
                    'customersChangeCountry',
                    '$(document).ready(function(){
                        customers_ChangeCountry(
                            "registration-form","'.$defaultCountryCode.'"
                        );
                    });'
                );
            }
        ?>
</article>
