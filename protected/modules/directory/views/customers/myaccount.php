<?php
    $this->_pageTitle = A::t('directory', 'My Account');
    $this->_activeMenu = 'customers/myAccount';
?>
<article id="page-myaccount" class="page-myaccount page-customers type-page status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'My Account'); ?></span>
        </h1>
        <?php
            $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
            $breadCrumbLinks[] = array('label'=> A::t('directory', 'Dashboard'), 'url'=>'customers/dashboard');
            $breadCrumbLinks[] = array('label' => A::t('directory', 'My Account'));
            CWidget::create('CBreadCrumbs', array(
                'links' => $breadCrumbLinks,
                'wrapperClass' => 'category-breadcrumb clearfix',
                'linkWrapperTag' => 'span',
                'separator' => '&nbsp;/&nbsp;',
                'return' => false
            ));
        ?>
    </header>
    <div class="block-body">
        <div id="my-account">
        <?php
            echo $actionMessage;

            $personalInformation = '';
            if($fieldFirstName !== 'no' || $fieldLastName !== 'no') $personalInformation .= '<p class="'.($customer->first_name.$customer->last_name ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Name').':</span></label><label class="right-label">'.$customer->first_name.' '.$customer->last_name.'</label></p>';
            if($fieldBirthDate !== 'no') $personalInformation .= '<p class="'.($birthDate ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Birth Date').':</span></label><label class="right-label">'.$birthDate.'</label></p>';
            if($fieldWebsite !== 'no') $personalInformation .= '<p class="'.($customer->website ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Website').':</span></label><label class="right-label">'.$customer->website.'</label></p>';
            if($fieldCompany !== 'no') $personalInformation .= '<p class="'.($customer->company ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Company').':</span></label><label class="right-label">'.$customer->company.'</label></p>';
            if($personalInformation) echo '<fieldset><legend>'.A::t('directory', 'Personal Information').'</legend><div class="row">'.$personalInformation.'</div></fieldset>';

            $contactInformation = '';
            if($fieldPhone !== 'no') $contactInformation .= '<p class="'.($customer->phone ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Phone').':</span></label><label class="right-label">'.$customer->phone.'</label></p>';
            if($fieldFax !== 'no') $contactInformation .= '<p class="'.($customer->fax ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Fax').':</span></label><label class="right-label">'.$customer->fax.'</label></p>';
            if($contactInformation) echo '<fieldset><legend>'.A::t('directory', 'Contact Information').'</legend><div class="row">'.$contactInformation.'</div></fieldset>';

            $addressInformation = '';
            if($fieldAddress !== 'no') $addressInformation .= '<p class="'.($customer->address ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Address').':</span></label><label class="right-label">'.$customer->address.'</label></p>';
            if($fieldAddress2 !== 'no') $addressInformation .= '<p class="'.($customer->address_2 ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Address (line 2)').':</span></label><label class="right-label">'.$customer->address_2.'</label></p>';
            if($fieldCity !== 'no') $addressInformation .= '<p class="'.($customer->city ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'City').':</span></label><label class="right-label">'.$customer->city.'</label></p>';
            if($fieldZipCode !== 'no') $addressInformation .= '<p class="'.($customer->zip_code ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Zip Code').':</span></label><label class="right-label">'.$customer->zip_code.'</label></p>';
            if($fieldCountry !== 'no') $addressInformation .= '<p class="'.($countryName ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Country').':</span></label><label class="right-label">'.$countryName.'</label></p>';
            if($fieldState !== 'no') $addressInformation .= '<p class="'.($stateName ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'State/Province').':</span></label><label class="right-label">'.$stateName.'</label></p>';
            if($addressInformation) echo '<fieldset><legend>'.A::t('directory', 'Address Information').'</legend><div class="row">'.$addressInformation.'</div></fieldset>';

            $accountIformation = '';
            if($fieldEmail !== 'no') $accountIformation .= '<p class="'.($customer->email ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Email').':</span></label><label class="right-label">'.$customer->email.'</label></p>';
            $accountIformation .= '<p class="'.($customer->username ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Username').':</span></label><label class="right-label">'.$customer->username.'</label></p>';
            $accountIformation .= '<p class="full"><label class="left-label"><span>'.A::t('directory', 'Password').':</span></label><label class="right-label">*****</label></p>';
            if(count($groups)) $accountIformation .= '<p class="'.($customer->group_name ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Group').':</span></label><label class="right-label">'.$customer->group_name.'</label></p>';
            $accountIformation .= '<p class="'.($langName ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Preferred Language').':</span></label><label class="right-label">'.$langName.'</label></p>';
            if($accountIformation) echo '<fieldset><legend>'.A::t('directory', 'Account Information').'</legend><div class="row">'.$accountIformation.'</div></fieldset>';


            $otherIformation = '';
            $otherIformation .= '<p class="'.($createdAt ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Created at').':</span></label><label class="right-label">'.$createdAt.'</label></p>';
            $otherIformation .= '<p class="'.($lastVisitedAt ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Last visit at').':</span></label><label class="right-label">'.$lastVisitedAt.'</label></p>';
            $otherIformation .= '<p class="'.($notifications ? 'full' : 'empty').'"><label class="left-label"><span>'.A::t('directory', 'Notifications').':</span></label><label class="right-label">'.$notifications.'</label></p>';

            $otherIformation .= '<p class="full"><label class="left-label"><span>'.A::t('directory', 'Remove Account').':</span></label><label class="right-label"><a class="button button-remove white" href="customers/removeAccount">'.A::t('directory', 'Remove').'</a></p>';
            if($otherIformation) echo '<fieldset><legend>'.A::t('directory', 'Other').'</legend><div class="row">'.$otherIformation.'</div></fieldset>';

        ?>
        <a class="button button-edit-account" href="customers/editAccount"><?php echo A::t('directory', 'Edit Account'); ?></a>
        </div>
    </div>
</article>

