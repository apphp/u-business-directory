<?php

    if(!empty($markers)){
        A::app()->getClientScript()->registerScript('mapMarkers', $markers, 2);
        A::app()->getClientScript()->registerCss('backgroundMap', "#directory-main-bar { background: url('templates/default/img/photo/bd2.png') no-repeat center top !important; height: 500px !important; }");
    }
    
?>
<article id="page-search-listings" class="page-search-listings listings type-listings status-publish hentry">
    <header class="entry-header">
        <h1 class="entry-title">
            <span><?php echo A::t('directory', 'Search') ?></span>
        </h1>
        <?php
        $breadCrumbLinks = array(array('label'=> A::t('directory', 'Home'), 'url'=>Website::getDefaultPage()));
        $breadCrumbLinks[] = array('label' => A::t('directory', 'Search'));
        CWidget::create('CBreadCrumbs', array(
            'links' => $breadCrumbLinks,
            'wrapperClass' => 'category-breadcrumb clearfix',
            'linkWrapperTag' => 'span',
            'separator' => '&nbsp;/&nbsp;',
            'return' => false
        ));
        ?>
    </header>

    <div class="search-listings listing-items clearfix">
    <?php echo $actionMessage; ?>
<?php
    if($listings){
?>
        <div class="dir-sorting clearfix">
            <div class="label"><?php echo A::t('directory', 'Showing {startEnd} from {all} Items', array('{startEnd}'=>$startEndItem, '{all}'=>$countListings)); ?></div>
            <form id='frmListingsCategory' enctype='get' action='listings/searchListings/'>
            <?php
                echo CHtml::hiddenField('s', $search, array());
                echo CHtml::hiddenField('categories', $categoryId, array());
                echo CHtml::hiddenField('locations', $locationId, array());
            ?>
                <div class="count">
                    <label><?php echo A::t('directory', 'Count') ?>:</label>
                    <select name="count" id="sorting-pagination" onchange="this.form.submit()">
                        <?php
                            $countsValue = array(5,10,20,30,40,50,100);
                            foreach($countsValue as $countValue){
                                echo '<option value="'.$countValue.'"'.($countValue == $count ? ' selected="selscted"' : '').'>'.$countValue.'</option>';
                            }
                        ?>
                    </select>
                </div>
                <div class="sortby">
                    <label><?php echo A::t('directory', 'Sort by') ?>:</label>
                    <select name="sortBy" id="sorting-sortby" onchange="this.form.submit()">
                    <?php
                        foreach($sortFields as $sort => $fields){
                            echo '<option value="'.$sort.'"'.($sortBy == $sort ? ' selected="selscted"' : '').'>'.$fields.'</option>';
                        }
                    ?>
                    </select>
                </div>
                <div class="sort">
                    <label><?php echo A::t('directory', 'Sort') ?></label>
                    <select name="sort" id="sorting-sort" onchange="this.form.submit()">
                        <option value="0"<?php if($sortType == 0) echo ' selected="selscted"' ?>>&and;</option>
                        <option value="1"<?php if($sortType == 1) echo ' selected="selscted"' ?>>&or;</option>
                    </select>
                </div>
            </form>
        </div>
<?php
        foreach($listings as $listing){
?>
        <ul class="items">
            <li class="item clear administrator<?php echo $listing['is_featured'] ? ' featured' : '' ?>">
                <div class="thumbnail">
                    <img src="images/modules/directory/listings/thumbs/<?php echo (!empty($listing['image_file_thumb']) ? CHtml::encode($listing['image_file_thumb']) : 'no_logo.jpg'); ?>" alt="Item thumbnail">
                    <div class="comment-count">0</div>
                </div>
                <div class="description">
                    <h3>
                        <a href="listings/view/id/<?php echo CHtml::encode($listing['listing_id']) ?>"><?php echo $listing['business_name'] ?></a>
                    </h3>
                    <p><?php echo $listing['business_description'] ?></p>
                </div>
            </li>
        </ul>
<?php   
        }
    }
    // Show page
    if(!empty($pagination)){
        echo $pagination;
    }
?>
    </div>
</article>
<?php

//        A::app()->getClientScript()->registerScript(
//            'customersChangeCountry',
//            'jQuery(document).ready(function(){
//                jQuery("html, body").animate({
//                    scrollTop: jQuery("#frmListingsCategory").offset().top
//                }, 1000);
//            });',
//            1
//        );
?>
