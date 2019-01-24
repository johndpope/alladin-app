<?php

namespace Zozothemes\Varmo\Model\Config\Settings\Footer\Middle\Twitter;

class Config implements \Magento\Framework\Option\ArrayInterface
{
    public function toOptionArray()
    {
        return [['value' => 'tw_widget', 'label' => __('Twitter Widget')], ['value' => 'tw_api', 'label' => __('Twitter Api')]];
    }

    public function toArray()
    {
        return ['tw_widget' => __('Twitter Widget'), 'tw_api' => __('Twitter Api')];
    }
}