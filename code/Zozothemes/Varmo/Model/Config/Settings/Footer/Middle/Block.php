<?php
namespace Zozothemes\Varmo\Model\Config\Settings\Footer\Middle;

class Block implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [['value' => '', 'label' => __('Do not show')], ['value' => 'custom', 'label' => __('Custom Block')], ['value' => 'twitter', 'label' => __('Twitter Feeds')]];
    }

    public function toArray()
    {
        return ['' => __('Do not show'), 'custom' => __('Custom Block'), 'twitter' => __('Twitter Feeds')];
    }
}
