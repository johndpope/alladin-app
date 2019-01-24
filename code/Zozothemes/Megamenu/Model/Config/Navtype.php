<?php
namespace Zozothemes\Megamenu\Model\Config;

class Navtype implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [
            ['value' => 'default', 'label' => __('Default')],
            ['value' => 'sidemenu', 'label' => __('Side Menu')],
        ];
    }

    public function toArray()
    {
        return [
            'default' => __('Default'),
            'sidemenu' => __('Side Menu'),
        ];
    }
}