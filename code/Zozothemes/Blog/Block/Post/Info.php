<?php
/** 
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
  * @package    Zozothemes_Blog
  * @copyright  Copyright (c) 2014 Zozothemes (http://www.zozothemes.com/)
  * @license    http://www.zozothemes.com/LICENSE-1.0.html
  */

namespace Zozothemes\Blog\Block\Post;

use Magento\Store\Model\ScopeInterface;

/**
 * Blog post info block
 */
class Info extends \Magento\Framework\View\Element\Template
{
	/**
     * Block template file
     * @var string
     */
    protected $_template = 'post/info.phtml';

    /**
     * Retrieve formated posted date
     * @var string
     * @return string
     */
    public function getPostedOn($format = 'Y-m-d H:i:s')
    {
        return date($format, strtotime($this->getPost()->getData('publish_time')));
    }

}
