<?php
    $this->_activeMenu = 'modules/settings/code/directory';
    $this->_breadCrumbs = array(
        array('label'=>A::t('directory', 'Modules'), 'url'=>'modules/'),
        array('label'=>A::t('directory', 'Business Directory'), 'url'=>'modules/settings/code/directory'),
        array('label'=>A::t('directory', 'Advertise Plans Management'), 'url'=>'plans/manage'),
        array('label'=>A::t('directory', 'Edit Advertise Plan')),
    );
?>

<h1><?php echo A::t('directory', 'Advertise Plans Management'); ?></h1>

<div class="bloc">
    <?php echo $tabs; ?>

    <div class="sub-title"><?php echo A::t('directory', 'Edit Advertise Plan'); ?></div>
    <div class="content">
        <?php
            echo $actionMessage;

            $fields = array();
            $fields['separatorContact'] = array();
            $fields['separatorContact']['separatorInfo']    = array('legend'=>A::t('directory', 'Contact Settings'));
            $fields['separatorContact']['email']            = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Email'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array());
            $fields['separatorContact']['phone']            = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Phone'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array());
            $fields['separatorContact']['fax']              = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Fax'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array());

            $fields['separatorPersonal'] = array();
            $fields['separatorPersonal']['separatorInfo']   = array('legend'=>A::t('directory', 'Personal Settings'));
            $fields['separatorPersonal']['website']         = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Website'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array());
            $fields['separatorPersonal']['video_link']      = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Video Link'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'set', 'source'=>array(0,1)), 'htmlOptions'=>array());

            $fields['separatorAddress'] = array();
            $fields['separatorAddress']['separatorInfo']    = array('legend'=>A::t('directory', 'Address Settings'));
            $fields['separatorAddress']['address']          = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Address'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
            $fields['separatorAddress']['map']              = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Map'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));

            $fields['separatorImages'] = array();
            $fields['separatorImages']['separatorInfo'] = array('legend'=>A::t('directory', 'Images Settings'));
            $fields['separatorImages']['logo']          = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Logo'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
            $fields['separatorImages']['images_count']  = array('type'=>'select', 'title'=>A::t('directory', 'The Number of Thumbnails'), 'default'=>0, 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>array(0,1,2,3)), 'data'=>array(-1=>A::t('directory', '- select -'),0=>0,1=>1,2=>2,3=>3));


            $fields['separatorOther'] = array();
            $fields['separatorOther']['separatorInfo']          = array('legend'=>A::t('directory', 'Other Settings'));
            $fields['separatorOther']['business_description']   = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Description'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
            $fields['separatorOther']['keywords_count']         = array('type'=>'select', 'title'=>A::t('directory', 'Keywords Count'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>range(0,15)), 'data'=>range(0,15), 'htmlOptions'=>array('style'=>'min-width:60px'));
            $fields['separatorOther']['inquiries_count']        = array('type'=>'select', 'title'=>A::t('directory', 'Inquiries Count'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>$validInquiries), 'data'=>$inquiries, 'htmlOptions'=>array('style'=>'min-width:90px'), 'appendCode'=>' / '.A::t('directory', 'per month'));
            $fields['separatorOther']['categories_count']       = array('type'=>'select', 'title'=>A::t('directory', 'Categories Count'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>$validCategories), 'data'=>$categories, 'htmlOptions'=>array('style'=>'min-width:90px'));
            $fields['separatorOther']['inquiry_button']         = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Inquiries Button'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
            $fields['separatorOther']['rating_button']          = array('type'=>'checkbox', 'title'=>A::t('directory', 'Display Rating Button'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
            $fields['separatorOther']['price']                  = array('type'=>'textbox', 'title'=>A::t('directory', 'Price'), 'default'=>'', 'tooltip'=>'', 'validation'=>array('required'=>false, 'type'=>'float', 'maxLength'=>13, 'minValue'=>'0', 'maxValue'=>'9999999999.99', 'format'=>'american'), 'htmlOptions'=>array('maxLength'=>13, 'class'=>'small'), 'appendCode'=>$currencySymbol);
            $fields['separatorOther']['open_comments']          = array('type'=>'checkbox', 'title'=>A::t('directory', 'Open Review'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));
            $fields['separatorOther']['duration']               = array('type'=>'select', 'title'=>A::t('directory', 'Duration'), 'default'=>0, 'tooltip'=>'', 'validation'=>array('required'=>true, 'type'=>'set', 'source'=>$validDurations), 'data'=> $durations);
            $fields['separatorOther']['is_default']             = array('type'=>'checkbox', 'title'=>A::t('directory', 'Default'), 'default'=>'', 'validation'=>array('type'=>'set', 'source'=>array(0, 1)));


            CWidget::create('CDataForm', array(
                'model'=>'Plans',
                'primaryKey'=>$id,
                'operationType'=>'edit',
                'action'=>'plans/edit/id/'.$id,
                'successUrl'=>'plans/manage/',
                'cancelUrl'=>'plans/manage/',
                'passParameters'=>false,
                'method'=>'post',
                'htmlOptions'=>array(
                    'name'=>'frmAdvertisePlansEdit',
                    'enctype'=>'multipart/form-data',
                    'autoGenerateId'=>true
                ),
                'requiredFieldsAlert'=>true,
                'fields'=>$fields,
                'translationInfo' => array('relation'=>array('id', 'advertise_plan_id'), 'languages'=>Languages::model()->findAll('is_active = 1')),
                'translationFields' => array(
                    'name' => array('type'=>'textbox', 'title'=>A::t('directory', 'Name'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>true, 'type'=>'text', 'maxLength'=>125), 'htmlOptions'=>array('maxLength'=>125, 'class'=>'large')),
                    'description' => array('type'=>'textarea', 'title'=>A::t('directory', 'Description'), 'tooltip'=>'', 'default'=>'', 'validation'=>array('required'=>false, 'type'=>'any', 'maxLength'=>2048), 'htmlOptions'=>array('maxLength'=>2048)),
                ),
                'buttons'=>array(
                    'submitUpdateClose' => array('type'=>'submit', 'value'=>A::t('directory', 'Update & Close'), 'htmlOptions'=>array('name'=>'btnUpdateClose')),
                    'submitUpdate' => array('type'=>'submit', 'value'=>A::t('directory', 'Update'), 'htmlOptions'=>array('name'=>'btnUpdate')),
                    'cancel' => array('type'=>'button', 'value'=>A::t('directory', 'Cancel'), 'htmlOptions'=>array('name'=>'', 'class'=>'button white')),
                ),
                'messagesSource'=>'core',
                'alerts'=>array('type'=>'flash'),
                'return'=>false,
            ));
        ?>

    </div>
</div>
