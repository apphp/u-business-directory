<?php
/**
 * Webforms component for building web-forms dynamically
 *
 * PUBLIC (static):         PRIVATE:
 * -----------              ------------------
 * drawShortcode
 *
 */

class WebFormsComponent extends CComponent
{

    const NL = "\n";

    /**
     *  Returns the instance of object
     *  @return current class
     */
    public static function init()
    {
        return parent::init(__CLASS__);
    }

    /**
     * Draws web form
     * $param array $params
     */
    public static function drawShortcode($params = array())
    {
        $output = '';
        $fieldName = '';
        $fieldEmail = '';
        $fieldPhone = '';
        $fieldMessage = '';
        $fieldCompany = '';
        $fieldCaptcha = '';

        $output .= CHtml::openTag('div', array('id'=>'contact-form'));
        $output .= CHtml::openForm('Webforms/submit', 'post', array('name'=>'form-contact')).self::NL;
        $output .= CHtml::tag('p', array('class'=>'alert alert-success', 'style'=>'display:none', 'id'=>'messageSuccess'), A::t('webforms', 'Thanks for your message! We will get back to you ASAP!'));
        $output .= CHtml::openTag('div', array('class'=>'contact-form-content'));
        $errorMsg = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('webforms', 'An error occurred while sending email! Please try again later.');
        $output .= CHtml::tag('p', array('style'=>'display:none', 'id'=>'messageError', 'class'=>'alert alert-error'), $errorMsg);

        $fName = ModulesSettings::model()->param('webforms', 'field_name');
        if($fName == 'show-optional' || $fName == 'show-required'){
            $required = ($fName == 'show-required') ? true : false;
            $requiredMark = ($fName == 'show-required') ? ' &#42; ' : '';
            $fieldName .= CHtml::openTag('div', array('class'=>'row'));
            $fieldName .= CHtml::tag('label', array(), $requiredMark.A::t('webforms', 'Name')).self::NL;
            $fieldName .= CHtml::textField('contact_name', '', array('data-required'=>($required ? 'true' : 'false'), 'maxlength'=>'125', 'autocomplete'=>'off', 'placeholder'=>A::t('webforms', 'Add your name'))).self::NL;
            if($required) $fieldName .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'nameError'), A::t('webforms', 'The field name cannot be empty!'));
            $fieldName .= CHtml::closeTag('div').self::NL;
        }

        $fEmail = ModulesSettings::model()->param('webforms', 'field_email');
        if($fEmail == 'show-optional' || $fEmail == 'show-required'){
            $required = ($fEmail == 'show-required') ? true : false;
            $requiredMark = ($fEmail == 'show-required') ? ' &#42; ' : '';
            $fieldEmail .= CHtml::openTag('div', array('class'=>'row'));
            $fieldEmail .= CHtml::tag('label', array(), $requiredMark.A::t('webforms', 'Email')).self::NL;
            $fieldEmail .= CHtml::textField('contact_email', '', array('data-required'=>($required ? 'true' : 'false'), 'maxlength'=>'255', 'autocomplete'=>'off', 'placeholder'=>A::t('webforms', 'Add a valid email'))).self::NL;
            if($required){
                $fieldEmail .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorEmpty'), A::t('webforms', 'The field email cannot be empty!'));
            }
            $fieldEmail .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'emailErrorValid'), A::t('webforms', 'You must provide a valid email address!'));
            $fieldEmail .= CHtml::closeTag('div').self::NL;
        }

        $fPhone = ModulesSettings::model()->param('webforms', 'field_phone');
        if($fPhone == 'show-optional' || $fPhone == 'show-required'){
            $required = ($fPhone == 'show-required') ? true : false;
            $requiredMark = ($fPhone == 'show-required') ? ' &#42; ' : '';
            $fieldPhone .= CHtml::openTag('div', array('class'=>'row'));
            $fieldPhone .= CHtml::tag('label', array(), $requiredMark.A::t('webforms', 'Phone')).self::NL;
            $fieldPhone .= CHtml::textField('contact_phone', '', array('data-required'=>($required ? 'true' : 'false'), 'maxlength'=>'125', 'autocomplete'=>'off', 'placeholder'=>A::t('webforms', 'Add a valid phone number'))).self::NL;
            if($required) $fieldPhone .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'phoneError'), A::t('webforms', 'The field phone number cannot be empty!'));
            $fieldPhone .= CHtml::closeTag('div').self::NL;
        }

        $fCompany = ModulesSettings::model()->param('webforms', 'field_company');
        if($fCompany == 'show-optional' || $fCompany == 'show-required'){
            $required = ($fCompany == 'show-required') ? true : false;
            $requiredMark = ($fCompany == 'show-required') ? ' &#42; ' : '';
            $fieldCompany .= CHtml::openTag('div', array('class'=>'row'));
            $fieldCompany .= CHtml::tag('label', array(), $requiredMark.A::t('webforms', 'Company')).self::NL;
            $fieldCompany .= CHtml::textField('contact_company', '', array('data-required'=>($required ? 'true' : 'false'), 'maxlength'=>'255', 'autocomplete'=>'off', 'placeholder'=>A::t('webforms', 'Add a company name'))).self::NL;
            if($required) $fieldCompany .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'companyError'), A::t('webforms', 'The field company name cannot be empty!'));
            $fieldCompany .= CHtml::closeTag('div').self::NL;
        }

        $fMessage = ModulesSettings::model()->param('webforms', 'field_message');
        if($fMessage == 'show-optional' || $fMessage == 'show-required'){
            $required = ($fMessage == 'show-required') ? true : false;
            $requiredMark = ($fMessage == 'show-required') ? ' &#42; ' : '';
            $fieldMessage .= CHtml::openTag('div', array('class'=>'row'));
            $fieldMessage .= CHtml::tag('label', array(), $requiredMark.A::t('webforms', 'Message')).self::NL;
            $fieldMessage .= CHtml::textArea('contact_message', '', array('data-required'=>($required ? 'true' : 'false'), 'maxength'=>'2048', 'placeholder'=>A::t('webforms', 'Add your message'))).self::NL;
            if($required) $fieldMessage .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'messageErrorEmpty'), A::t('webforms', 'The field message cannot be empty!'));
            $fieldMessage .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'messageErrorValid'), A::t('webforms', 'Message must be at least 10 characters in length!'));
            $fieldMessage .= CHtml::closeTag('div').self::NL;
        }

        $fCaptcha = ModulesSettings::model()->param('webforms', 'field_captcha');
        if($fCaptcha == 'show'){
            $fieldCaptcha .= CHtml::openTag('div', array('class'=>'row'));
            $fieldCaptcha .= CWidget::create('CCaptcha', array('type'=> 'math', 'required'=>true, 'name'=>'captchaWebformResult', 'return' => true));
            if($required) $fieldCaptcha .= CHtml::tag('p', array('class'=>'error', 'style'=>'display:none', 'id'=>'captchaError'), A::t('webforms', 'The field captcha cannot be empty!'));
            $fieldCaptcha .= CHtml::closeTag('div').self::NL;
        }

        $output .= $fieldName;
        $output .= $fieldEmail;
        $output .= $fieldPhone;
        $output .= $fieldCompany;
        $output .= $fieldMessage;
        $output .= $fieldCaptcha;

        $output .= CHtml::tag('button', array('type'=>'button', 'class'=>'button', 'data-sending'=>A::t('webforms', 'Sending...'), 'data-send'=>A::t('webforms', 'Send'), 'onclick'=>'javascript:webforms_SubmitForm(this)'), A::t('webforms', 'Send')).self::NL;
        $output .= CHtml::tag('div', array('class'=>'spacer'), '').self::NL;
        $output .= CHtml::closeTag('div').self::NL; /* contact-form-content */
        $output .= CHtml::closeForm().self::NL;
        $output .= CHtml::closeTag('div').self::NL; /* contact-form */

        // Register module javascript css
        A::app()->getClientScript()->registerScriptFile('js/modules/webforms/webforms.js');
        A::app()->getClientScript()->registerCssFile('css/modules/webforms/webforms.css');

        return $output;
    }

}
