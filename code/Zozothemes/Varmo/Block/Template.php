<?php

namespace Zozothemes\Varmo\Block;

class Template extends \Magento\Framework\View\Element\Template {
    public $_coreRegistry;
    public $_request;
    /**
     * @var \Magento\Cms\Model\Template\FilterProvider
     */
    protected $_filterProvider;
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Cms\Model\Template\FilterProvider $filterProvider,
        \Magento\Cms\Model\BlockFactory $blockFactory,
        \Magento\Framework\App\Request\Http $request,
        /*\Magento\Store\Model\StoreManagerInterface $storeManager,*/  
        \Zozothemes\Varmo\Helper\Data $helper,
        array $data = []
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context, $data);
        $this->_filterProvider = $filterProvider;
        $this->_blockFactory = $blockFactory;
        $this->_request = $request;
        //$this->storeManager = $storeManager;
        $this->helper = $helper;
    }
    
   
    public function getConfig($config_path, $storeCode = null)
    {
        return $this->_scopeConfig->getValue(
            $config_path,
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE,
            $storeCode
        );
    }
    public function getCmsblocks($content){
        
        return $this->_filterProvider->getBlockFilter()
                ->setStoreId($this->storeManager->getStore()->getId())
                ->filter($content);
    }
    public function getFooterLogoSrc(){
        $folderName = \Zozothemes\Varmo\Model\Config\Backend\Image\Logo::UPLOAD_DIR;
        $storeLogoPath = $this->_scopeConfig->getValue(
            'varmo_settings/footer/footer_logo_src',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $path = $folderName . '/' . $storeLogoPath;
        $logoUrl = $this->_urlBuilder
                ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
        return $logoUrl;
    }
    
    public function getImageMediaPath(){
    	//return $this->getUrl('pub/media',['_secure' => $this->getRequest()->isSecure()]);
        return $this->_storeManager->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
    }
    public function isHomePage()
    {
        $currentUrl = $this->getUrl('', ['_current' => true]);
        $urlRewrite = $this->getUrl('*/*/*', ['_current' => true, '_use_rewrite' => true]);
        return $currentUrl == $urlRewrite;
    }
    
    public function getNewsletterLogoSrc(){
        $folderName = \Zozothemes\Varmo\Model\Config\Backend\Image\Newsletter::UPLOAD_DIR;
        $storeLogoPath = $this->_scopeConfig->getValue(
            'varmo_settings/newsletter_popup/show_logo',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        if(isset($storeLogoPath)){
            $path = $folderName . '/' . $storeLogoPath;
            $logoUrl = $this->_urlBuilder
                    ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
            return $logoUrl;
        }
    }
    public function getNewsletterbgSrc(){
        $folderName = \Zozothemes\Varmo\Model\Config\Backend\Image\Newsletter::UPLOAD_DIR;
        $storeLogoPath = $this->_scopeConfig->getValue(
            'varmo_settings/newsletter_popup/newsletter_bg_image',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
        $path = $folderName . '/' . $storeLogoPath;
        $bgUrl = $this->_urlBuilder
                ->getBaseUrl(['_type' => \Magento\Framework\UrlInterface::URL_TYPE_MEDIA]) . $path;
        return $bgUrl;
    }
    
}
?>