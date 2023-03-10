<?php
    $this->_pageTitle = $listingName;
    $this->_pageKeywords = $listingKeywords;
    A::app()->getClientScript()->registerScriptFile('js/modules/directory/directory.js', 0);
    A::app()->getClientScript()->registerScriptFile('js/vendors/fancybox/jquery.fancybox.js', 2);
    A::app()->getClientScript()->registerCssFile('js/vendors/fancybox/jquery.fancybox.css');
?>
<article id="listing-<?php echo CHtml::encode($listingId); ?>" class="listing-<?php echo CHtml::encode($listingId); ?> type-listing status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo $listingName; ?></span>
        </h1>
        <?php
        $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
        foreach($parentCategories as $parentCategory){
            $breadCrumbLinks[] = array('label'=>$parentCategory['name'], 'url'=>'categories/view/id/'.$parentCategory['id']);
        }
        $breadCrumbLinks[] = array('label' => $listingName);
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
        if($listingExpired){
            echo '<div class="label-listing"><span class="label label-red">'.A::t('directory', 'Expired').'</span></div>';
        }else if($listingPending){
            if($planPrice == 0){
                echo '<div class="label-listing"><span class="label label-yellow">'.A::t('directory', 'Pending').'</span></div>';
            }else{
                echo '<div class="label-listing"><span class="label label-gray">'.A::t('directory', 'Pending').'</span></div>';
            }
        }else if($listingCanceled){
            echo '<div class="label-listing"><span class="label label-red">'.A::t('directory', 'Canceled').'</span></div>';
        }
    ?>
    <div class="entry-content clearfix">
    <?php
        if(!empty($listingImage) || !empty($listingImagesMiniature)){
    ?>
        <div class="listing-images">
        <?php
            if($listingImage){
        ?>
            <div class="item-image">
                <a href="images/modules/directory/listings/<?php echo CHtml::encode($listingImage); ?>" rel="listing-fancybox" class="listing-fancybox" title="<?php echo A::t('directory', 'Item image'); ?>" />
                    <img src="images/modules/directory/listings/<?php echo CHtml::encode($listingImage); ?>" alt="<?php echo A::t('directory', 'Item image'); ?>" />
                </a>
            </div>
        <?php
            }
            if($listingImagesMiniature){
                echo '<div class="item-image-miniature">';
                for($i = 0; $i < count($listingImagesMiniature); $i++){
                    echo '<a href="images/modules/directory/listings/'.CHtml::encode($listingImagesMiniature[$i]['image']).'" rel="listing-fancybox" class="listing-fancybox" title="'.A::te('directory', 'Item image miniature').' #'.($i+1).'" />';
                    echo '<img src="images/modules/directory/listings/thumbs/'.$listingImagesMiniature[$i]['thumb'].'" alt="'.A::t('directory', 'Item image miniature').' #'.($i+1).'" />';
                    echo '</a>';
                }
                echo '</div>';
            }
    ?>
        </div>
    <?php
        }
        if($listingDescription){
            echo '<p>'.$listingDescription.'</p>';
        }
        if(!$listingExpired && !$listingPending && !$listingCanceled){
        ?>
        <a href="inquiries/firstStep/listingId/<?php echo $listingId; ?>" class="button"><?php echo A::t('directory', 'Submit Inquiry'); ?></a>
        <?php
            }
        ?>
    </div>
<?php
    if(count($categoriesListing) > 1){
?>
    <h1 class="entry-title">
        <span><?php echo A::t('directory', 'Categories'); ?></span>
    </h1>
    <ul class="entry-content list-categories clearfix">
<?php
        foreach($categoriesListing as $category){
            echo '<li>';
            echo '<img class="icon" src="images/modules/directory/categories/'.(!empty($category['icon']) ? CHtml::encode($category['icon']) : 'no_image.png').'">';
            echo '<a href="categories/view/id/'.CHtml::encode($category['category_id']).'">'.$category['name'].'</a>';
            echo '</li>';
        }
?>
    </ul>
<?php
    }
?>
    <hr />
    <?php if($listingAddress || $listingMap || $listingPhone || $listingFax || $listingEmail || $listingWebsite || $listingVideo){ ?>
    <div class="item-info">
        <dl class="item-address">
        <?php
            if($listingAddress){
                echo '<dt class="address">'.A::t('directory', 'Address').':</dt>';
                echo '<dd class="data">'.$listingAddress.'</dd>';
            }
            if($listingMap){
                echo '<dt class="gps">'.A::t('directory', 'GPS').':</dt>';
                echo '<dd class="data">'.$listingLongitude.', '.$listingLatitude.'</dd>';
            }
            if($listingPhone){
                echo '<dt class="phone">'.A::t('directory', 'Phone').':</dt>';
                echo '<dd class="data">'.$listingPhone.'</dd>';
            }
            if($listingFax){
                echo '<dt class="fax">'.A::t('directory', 'Fax').':</dt>';
                echo '<dd class="data">'.$listingFax.'</dd>';
            }
            if($listingEmail){
                echo '<dt class="email">'.A::t('directory', 'Email').':</dt>';
                echo '<dd class="data"><a href="mailto:'.CHtml::encode($listingEmail).'">'.$listingEmail.'</a></dd>';
            }
            if($listingWebsite){
                echo '<dt class="website">'.A::t('directory', 'Website').':</dt>';
                echo '<dd class="data"><a href="'.$listingWebsite.'">'.$listingWebsite.'</a></dd>';
            }
            if($listingVideo){
                echo '<dt class="video">'.A::t('directory', 'Video Link').':</dt>';
                echo '<dd class="data"><a href="'.$listingVideo.'">'.$listingVideo.'</a></dd>';
            }
        ?>
        </dl>
        <!--dl class="item-hours">
            <dt class="heading-title">Opening Hours</dt>
            <dt class="day">Monday:</dt>
            <dd class="data">8am - 8pm</dd>
            <dt class="day">Tuesday:</dt>
            <dd class="data">8am - 8pm</dd>
            <dt class="day">Wednesday:</dt>
            <dd class="data">8am - 8pm</dd>
            <dt class="day">Thursday:</dt>
            <dd class="data">8am - 8pm</dd>
            <dt class="day">Friday:</dt>
            <dd class="data">8am - 8pm</dd>
            <dt class="day">Saturday:</dt>
            <dd class="data">8am - 8pm</dd>
            <dt class="day">Sunday:</dt>
            <dd class="data">8am - 8pm</dd>
        </dl-->
    </div>
    <?php
        if($listingMap){
            echo '<div class="item-map clearfix">';
            echo '</div>';
        }
    ?>
    <hr />
    <?php } ?>
</article>
<div id="ait-rating-system" class="rating-system">
<?php
    if($showFormReview){
?>
    <h3><?php echo A::t('directory', 'Leave a review'); ?></h3>
    <?php
        $errorMsg = (APPHP_MODE == 'demo') ? A::t('core', 'This operation is blocked in Demo Mode!') : A::t('directory', 'An error occurred while sending email! Please try again later.');
    ?>
    <p id="messageSuccess" class="alert alert-success success" style="display:none"><?php echo A::t('directory', 'Thanks for your review!'); ?></p>
    <p id="messageError" class="alert alert-error" style="display:none"><?php echo $errorMsg; ?></p>
    <?php echo CHtml::openForm('reviews/sendComment', 'post', array('class'=>'rating-send-form')); ?>
        <div class="rating-ipnuts">
            <div class="rating-details">
            <?php
                if(!$customerLogin){
            ?>
                <div class="detail">
                    <input id="rating-name" name="" data-required="1" placeholder="<?php echo A::t('directory', 'Your Name'); ?>" type="text" />
                    <p id="reviewsNameError" class="error" style="display:none"><?php echo A::t('directory', 'The field name cannot be empty!'); ?></p>
                </div>
                <div class="detail">
                    <input id="rating-email" name="" data-required="1" placeholder="<?php echo A::t('directory', 'Your Email'); ?>" type="text" />
                    <p id="reviewsEmailErrorEmpty" class="error" style="display:none"><?php echo A::t('directory', 'The field email cannot be empty!'); ?></p>
                    <p id="reviewsEmailErrorValid" class="error" style="display:none"><?php echo A::t('directory', 'You must provide a valid email address!'); ?></p>
                </div>
            <?php
                }
            ?>
                <div class="detail">
                    <textarea id="rating-description" data-required="1" placeholder="<?php echo A::t('directory', 'Description'); ?>" name="rating-description" maxLength=2048 rows="4"></textarea>
                    <p id="reviewsDescriptionErrorEmpty" class="error" style="display:none"><?php echo A::t('directory', 'The field description cannot be empty!'); ?></p>
                    <p id="reviewsDescriptionErrorValid" class="error" style="display:none"><?php echo A::t('directory', 'Message must be at least 10 characters in length!'); ?></p>
                </div>
                <div class="detail">
                    <?php echo CWidget::create('CCaptcha', array('type'=>'math', 'required'=>true, 'return' => true, 'name'=>'reviewsCaptchaResult', 'id'=>'reviews_captcha_validation')); ?>
                    <p class="error" style="display:none" id="reviewsCaptchaError"><?php echo A::t('directory', 'The field captcha cannot be empty!'); ?></p>
                </div>
                <button type="button" data-sending="<?php echo A::te('directory', 'Sending...'); ?>" data-send="<?php echo A::te('directory', 'Submit'); ?>" data-text-not-select-rating="<?php echo A::te('directory', 'You didn\'t select any rating. Are you sure you want to submit your review without rating?'); ?>" onclick='javascript:reviews_SendComment(this, <?php echo $listingId; ?>)' class="send-rating"><?php echo A::t('directory', 'Submit'); ?></button>
                <div class="message error"><?php echo A::t('directory', 'Please fill out all fields!'); ?></div>
                <div class="message success"><?php echo A::t('directory', 'Your review has been successfully sent'); ?></div>
            </div>
            <div class="ratings">
                <div class="rating clearfix" data-rating-id="1" data-rated-value="0">
                    <div class="rating-title"><?php echo A::t('directory', 'Price'); ?></div>
                    <div class="stars clearfix">
                        <div class="star" data-star-id="1"></div>
                        <div class="star" data-star-id="2"></div>
                        <div class="star" data-star-id="3"></div>
                        <div class="star" data-star-id="4"></div>
                        <div class="star" data-star-id="5"></div>
                    </div>
                </div>
                <div class="rating clearfix" data-rating-id="2" data-rated-value="0">
                <div class="rating-title"><?php echo A::t('directory', 'Location'); ?></div>
                    <div class="stars clearfix">
                        <div class="star" data-star-id="1"></div>
                        <div class="star" data-star-id="2"></div>
                        <div class="star" data-star-id="3"></div>
                        <div class="star" data-star-id="4"></div>
                        <div class="star" data-star-id="5"></div>
                    </div>
                </div>
                <div class="rating clearfix" data-rating-id="3" data-rated-value="0">
                    <div class="rating-title"><?php echo A::t('directory', 'Staff'); ?></div>
                    <div class="stars clearfix">
                        <div class="star" data-star-id="1"></div>
                        <div class="star" data-star-id="2"></div>
                        <div class="star" data-star-id="3"></div>
                        <div class="star" data-star-id="4"></div>
                        <div class="star" data-star-id="5"></div>
                    </div>
                </div>
                <div class="rating clearfix" data-rating-id="4" data-rated-value="0">
                    <div class="rating-title"><?php echo A::t('directory', 'Services'); ?></div>
                    <div class="stars clearfix">
                        <div class="star" data-star-id="1"></div>
                        <div class="star" data-star-id="2"></div>
                        <div class="star" data-star-id="3"></div>
                        <div class="star" data-star-id="4"></div>
                        <div class="star" data-star-id="5"></div>
                    </div>
                </div>
            </div><!-- .ratings -->
        </div><!-- .rating-inputs -->
<?php
        echo CHtml::closeForm();
    }
    echo $actionMessage;
?>
    <div class="user-ratings">
    <?php
    if(!empty($reviews)){
        foreach($reviews as $review){
    ?>
        <div class="user-rating<?php echo empty($review['is_public']) ? ' not-published' : ''; ?>">
            <div class="user-values">
                <div class="rating clearfix">
                    <div class="rating-title"><?php echo A::t('directory', 'Price'); ?></div>
                    <div class="user-stars clearfix">
                <?php
                    for($i = 1; $i <= 5; $i++){
                        echo '<div class="star'.($review['rating_price'] >= $i ? ' active' : '').'" data-star-id="'.$i.'"></div>';
                    }
                ?>
                    </div>
                </div>
                <div class="rating clearfix">
                <div class="rating-title"><?php echo A::t('directory', 'Location'); ?></div>
                    <div class="user-stars clearfix">
                <?php
                    for($i = 1; $i <= 5; $i++){
                        echo '<div class="star'.($review['rating_location'] >= $i ? ' active' : '').'" data-star-id="'.$i.'"></div>';
                    }
                ?>
                    </div>
                </div>
                <div class="rating clearfix">
                    <div class="rating-title"><?php echo A::t('directory', 'Staff'); ?></div>
                    <div class="user-stars clearfix">
                <?php
                    for($i = 1; $i <= 5; $i++){
                        echo '<div class="star'.($review['rating_staff'] >= $i ? ' active' : '').'" data-star-id="'.$i.'"></div>';
                    }
                ?>
                    </div>
                </div>

                <div class="rating clearfix">
                    <div class="rating-title"><?php echo A::t('directory', 'Services'); ?></div>
                    <div class="user-stars clearfix">
                <?php
                        for($i = 1; $i <= 5; $i++){
                            echo '<div class="star'.($review['rating_services'] >= $i ? ' active' : '').'" data-star-id="'.$i.'"></div>';
                        }
                ?>
                    </div>
                </div>
            </div>
            <div class="user-details">
                <div class="name"><?php echo $review['customer_name']; ?></div>
                <div class="date"><?php echo CLocale::date($dateTimeFormat, $review['created_at']); ?></div>
                <div class="value">
            <?php
                for($i = 1; $i <= 5; $i++){
                    echo '<div class="star'.(round($review['rating_value']) >= $i ? ' active' : '').'" data-star-id="'.$i.'"></div>';
                }
            ?>
                </div>
                <div class="description"><?php echo CHtml::encode($review['message']); ?></div>
            </div>
        </div>
    <?php
        }
    }
    ?>
    </div>
    <?php if($showViewMore){ ?>
    <div id="view-more" class="view-more" onclick="javascript: reviews_viewMoreComments(this, <?php echo CHtml::encode($listingId); ?>);"><?php echo A::t('directory', 'View More'); ?></div>
    <?php } ?>
</div>
<?php
if($listingMap){
    A::app()->getClientScript()->registerScript(
        'listingView',
        'jQuery(document).ready(function(){
            var $ = jQuery;
            '.$smallMap.';
        });'
    );
}
A::app()->getClientScript()->registerScript(
    'listingFancybox',
    'jQuery(document).ready(function(){
        jQuery("a.listing-fancybox").fancybox({
            "opacity"       : true,
            "overlayShow"   : false,
            "overlayColor"  : "#000",
            "overlayOpacity": 0.5,
            "titlePosition" : "inside", /* outside, inside or float */
            "cyclic" : true,
            "transitionIn"  : "elastic", /* fade or none*/
            "transitionOut" : "fade"
        });
    });'
);
?>
