<?php
/**
 * @package		Zozoconcepts_Hybrid
 * @author		Zozoconcepts
 * @copyright	Copyright 2014 - 2015 Zozoconcepts
 */
$installer = $this;
$installer->startSetup();

//WYSIWYG hidden by default
Mage::getConfig()->saveConfig('cms/wysiwyg/enabled', 'hidden');

//Mage::getSingleton('hybrid/cssgen_generator')->generateCss('theme_css_',   NULL, NULL);

$installer->endSetup();