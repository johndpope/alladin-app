<?php

class Zozoconcepts_Dailydeal_Block_Sidebar_Right extends Zozoconcepts_Dailydeal_Block_Sidebar_Abstract
{
	public function getSidebarDeals()
	{
		return Mage::getModel('dailydeal/dailydeal')->getRightSidebar();
	}

	public function getTitle()
	{
		return Mage::helper('dailydeal')->getConfig()->getRightTitle();
	}

	public function getType()
	{
		return 'right';
	}
}