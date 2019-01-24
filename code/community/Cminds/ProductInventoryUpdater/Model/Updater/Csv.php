<?php

/**
 * Cminds Product Inventory Updater CSV parser
 *
 * @category    Cminds
 * @package     Cminds_ProductInventoryUpdater
 * @author      Wojtek Kaminski <wojtek.kaminski@gmail.com>
 */
abstract class Cminds_ProductInventoryUpdater_Model_Updater_Csv
	extends	Cminds_ProductInventoryUpdater_Model_Updater_Abstract
{
	/**
	 * @var string default feed link
	 */
	protected $_feedUrl;
	protected $_matchingIndex;
	protected $_matchingPos;
	protected $_columnPos;
	protected $_matchingColumnIndex;
	protected $matchingAttribute;
	/**
	 * @var string default delimiter
	 */
	protected $delimiter;

	/**
	 * @return string Parsing CSV from feed url
	 */
	protected function parse() {
		if(!$this->_feedUrl) {
			return false;
		}

		if (!$this->_matchingIndex) {
			return false;
		}

		$content = file_get_contents($this->_feedUrl) or die ("error");

		$nPos = strpos($content,"\n");
		$headers = substr($content, 0, $nPos);
		$headers = explode($this->getDelimiter(), $headers);

		$i = 0;
		foreach($headers as $header) {
			$header = str_replace('"', '', $header);

			if(strtolower($header) == strtolower($this->_matchingIndex)) {
				$this->_matchingPos = $i;
			}

			if($this->_matchingColumnIndex) {
				if (strtolower($header) == strtolower($this->_matchingColumnIndex)) {
					$this->_columnPos = $i;
				}
			}
			$i++;
		}
		$content = substr($content, $nPos, strlen($content));

		$lbl = explode("\n", $content);

		$data = array();

		foreach ($lbl as $line) {
			$cols = explode($this->getDelimiter(), $line);
			$data[] = $cols;
		}

		$this->setParsedData($data);
	}

	protected function getDelimiter() {
		$delimiter = $this->delimiter;

		if(!$delimiter) {
			$delimiter = ',';
		}

		return $delimiter;
	}
}
