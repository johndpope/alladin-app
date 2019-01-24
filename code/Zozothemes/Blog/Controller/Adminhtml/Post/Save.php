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
namespace Zozothemes\Blog\Controller\Adminhtml\Post;

use Zozothemes\Blog\Model\Post;
/**
 * Blog post save controller
 */
class Save extends \Zozothemes\Blog\Controller\Adminhtml\Post
{
    /**
     * Before model save
     * @param  \Zozothemes\Blog\Model\Post $model
     * @param  \Magento\Framework\App\Request\Http $request
     * @return void
     */
    protected function _beforeSave($model, $request)
    {
        /* prepare publish date */
        $dateFilter = $this->_objectManager->create('Magento\Framework\Stdlib\DateTime\Filter\Date');
        $data = $model->getData();

        $inputFilter = new \Zend_Filter_Input(
            ['publish_time' => $dateFilter],
            [],
            $data
        );
        $data = $inputFilter->getUnescaped();
        $model->setData($data);

        /* prepare relative links */
        if ($links = $request->getPost('links')) {

            $jsHelper = $this->_objectManager->create('Magento\Backend\Helper\Js');

            $links = is_array($links) ? $links : [];
            $linkTypes = ['relatedposts', 'relatedproducts'];
            foreach ($linkTypes as $type) {

                if (isset($links[$type])) {
                    $links[$type] = $jsHelper->decodeGridSerializedInput($links[$type]);

                    $model->setData($type.'_links', $links[$type]);
                }
            }
        }

        /* prepare featured image */
        $imageField = 'featured_img';
        $fileSystem = $this->_objectManager->create('Magento\Framework\Filesystem');
        $mediaDirectory = $fileSystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);

        if (!empty($_FILES['post']['name'][$imageField])) {

            $uploader = $this->_objectManager->create('Magento\MediaStorage\Model\File\UploaderFactory');
            $uploader = $uploader->create(['fileId' => 'post[featured_img]']);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);

            $result = $uploader->save(
                $mediaDirectory->getAbsolutePath(Post::BASE_MEDIA_PATH)
            );
            $model->setData($imageField, Post::BASE_MEDIA_PATH . $result['file']);
        } else {
            if (isset($data[$imageField]) && isset($data[$imageField]['value'])) {
                if (isset($data[$imageField]['delete'])) {
                    unlink($mediaDirectory->getAbsolutePath() . $data[$imageField]['value']);
                    $model->setData($imageField, '');
                } else {
                    $model->unsetData($imageField);
                }
            }
        }


    }

}
