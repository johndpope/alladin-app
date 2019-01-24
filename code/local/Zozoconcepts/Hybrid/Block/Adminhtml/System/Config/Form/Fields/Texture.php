<?php

class Zozoconcepts_Hybrid_Block_Adminhtml_System_Config_Form_Fields_Texture extends Mage_Adminhtml_Block_System_Config_Form_Field
{
	/**
	 * Add texture preview
	 *
	 * @param Varien_Data_Form_Element_Abstract $element
	 * @return String
	 */
	protected function _getElementHtml(Varien_Data_Form_Element_Abstract $element)
	{
		$elementOriginalData = $element->getOriginalData();
		$texPath = '';
		if (isset($elementOriginalData['texture_path']))
		{
			$texPath = $elementOriginalData['texture_path'];
		}
		else
		{
			return 'Error: Texture path not specified in config.';
		}

		$html = $element->getElementHtml(); //Default HTML
		$jsUrl = $this->getJsUrl('hybrid/jquery/jquery-admin.min.js');
		$texUrl = Mage::getBaseUrl(Mage_Core_Model_Store::URL_TYPE_MEDIA) . $texPath;
		
		//Recreate ID of the background color picker which is related with this pattern
			//From the texture picker ID get the suffix beginning with '_texture'
			
			$fieldIdSuffix = strstr($element->getHtmlId(), '_texture');
			//Replace the suffix with suffix appropriate for the background color picker in the current options group
			
			 $bgcPickerId = str_replace($fieldIdSuffix, '_bg_color', $element->getHtmlId());
			
		//Create ID of the pattern preview box
		$previewId = $element->getHtmlId() . '-tex-preview';
		
		if (Mage::registry('jqueryLoaded') == false)
		{
			$html .= '
			<script type="text/javascript" src="'. $jsUrl .'"></script>
			<script type="text/javascript">jQuery.noConflict();</script>
			';
			Mage::register('jqueryLoaded', 1);
		}

		$html .= '
		<br/><div id="'. $previewId .'" style="width:280px; height:160px; margin:10px 0; background-color:transparent;"></div>
		<script type="text/javascript">
			jQuery(function($){
				var tex		= $("#'. $element->getHtmlId()	.'");
				var bgc		= $("#'. $bgcPickerId			.'");
				var preview	= $("#'. $previewId				.'");
				preview.css("background-color", bgc.attr("value"));
				
				tex.change(function() {
					preview.css({
						"background-color": bgc.css("background-color"),
						"background-image": "url('. $texUrl .'" + tex.val() + ".png)"
					});
				})
				.change();
				
				tex.ready(function() {
					var texturebg = document.getElementById("'.$bgcPickerId.'").value;
					preview.css({
						"background-color": texturebg						
					});
				})
				.ready();
			});
			
			
		</script>
		';
		
		return $html;
	}
}
