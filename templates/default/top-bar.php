<div class="topbar clearfix">
    <div id="tagLineHolder">
        <div class="defaultContentWidth clearfix">
			<?php
				if($phoneVisible || $emailVisible){
					echo '<p class="left info">'.($phoneVisible ? CHtml::encode($phone).' ' : '').($emailVisible ? CHtml::encode($email) : '').'</p>';
				}
			?>
  
            <?php if(!empty($socialNetworks)){ ?>
				<ul class="social-icons right clearfix">
					<?php foreach($socialNetworks as $social){ ?>
					<li class="left">
						<a href="<?php echo CHtml::encode($social['link']); ?>">
							<img src="images/modules/directory/siteinfo/socialnetworks/<?php echo CHtml::encode($social['icon']); ?>" height="24" width="24" alt="<?php echo CHtml::encode($social['name']); ?>" title="<?php echo CHtml::encode($social['name']); ?>">
						</a>
					</li>
					<?php } ?>
				</ul>
			<?php } ?>

			<div id="language-selector">
				<?php
					echo Languages::drawSelector(array(
						'forceDrawing'  => true,
						'display'       => 'icons',
						'class'         => 'dropdown-menu',
					));
				?>
			</div>

        </div>
    </div>
</div>
