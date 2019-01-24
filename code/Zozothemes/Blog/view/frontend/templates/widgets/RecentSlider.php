<?php

/* 
 * Zozothemes.
 * 
 * NOTICE OF LICENSE
 * 
 * This source file is subject to the Zozothemes.com license that is
 * available through the world-wide-web at this URL:
 * http://www.zozothemes.com/license-agreement.html
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 * 
 * @category   Zozothemes
 * @package    Zozothemes_
 * @copyright  Copyright (c) 2014 Zozothemes (http://www.zozothemes.com/)
 * @license    http://www.zozothemes.com/LICENSE-1.0.html
 */
?>
<?php
	$_postCollection = $block->getPostCollection();
?>
<?php if ($_postCollection->count()) { ?>
<!--<div class="widget block block-recent-posts" data-bind="scope: 'recemt-posts'">
    <div class="block-title">
        <strong><?php //echo __('Recent Posts') ?></strong>
    </div>
    <div class="block-content">-->
    	<?php foreach ($_postCollection as $_post) { ?>
            <div class="blog-item">
                <article class="blog blog-item blog-list-item">
					<div class="entry-image-wrap">
						<div class="featured-image">
							<img src="<?php echo $block->escapeHtml($_post->getFeaturedImage()) ?>" alt="" class="img-responsive"/>
						</div>
					</div>
                	<div class="entry-detail-wrap">
						<div class="entry-blog"> 
							<div class="entry-header">  
								<span class="meta-details meta-category"></span>
								<span class="meta-details entry-date"><p><i class="varmo-icon-watch"></i><?php echo date('F j, Y', strtotime($_post->getPublishTime())); ?></p></span>
								<h3 class="blog-title"><a href="<?php echo $_post->getPostUrl() ?>"><?php echo $block->escapeHtml($_post->getTitle()) ?></a></h3>
                        	</div>
                            <div class="entry-content">
                                <p><?php echo $_post->getContent() ?></p>                                                                   
                            </div>
                        	<div class="post-footer clearfix">
                                <div class="blog-left pull-left ">
                                    <span class="meta-readmore">
                                    	<a class="btn-link" href="<?php echo $_post->getPostUrl() ?>">Continue Reading</a>
                                	</span>
                            	</div>
                                <div class="blog-right pull-right">
                                    <div class="blog-share-button">
                                        <ul class="social-icons social-icons-bg social-icons-circle">
                                            <li>
                                                <a href="#" onclick="window.open('http://www.facebook.com/sharer.php?u=http://magento.zozothemes.com/varmo_2/american-style','Facebook','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" class="facebook"><span class="varmo-icon-facebook"></span></a>
                                            </li>
                                            <li>
                                                <a href="#" onclick="window.open('http://twitter.com/share?url=http://magento.zozothemes.com/varmo_2/american-style&amp;text=American Style','Twitter share','width=600,height=300,left='+(screen.availWidth/2-300)+',top='+(screen.availHeight/2-150)+''); return false;" class="twitter"><span class="varmo-icon-twitter"></span></a>
                                            </li>
                                            <li>
                                                <a href="#" onclick="window.open('https://plus.google.com/share?url=http://magento.zozothemes.com/varmo_2/american-style','Google plus','width=585,height=666,left='+(screen.availWidth/2-292)+',top='+(screen.availHeight/2-333)+''); return false;" class="g-puls"><span class="varmo-icon-google"></span></a>
                                            </li>
                                        </ul>
                               		</div>
                               </div>         
							</div>
						</div>
					</div>
				</article>
			</div>

        <?php } ?>
    <!--</div>
</div>-->
<?php } ?>