<?php
/**
 *
**/
class Fishpig_FSeoXmlSitemap_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	  * @return bool
	 **/
	public function isEnabled()
	{
		return true;
	}
	/**
	  * 
	  * @param Mage_Core_Model_Store|int|null $store
	  *
	  * @return string
	  *
	 **/
	public function getDataFile($store = null)
	{
		if (!$store) {
			$store = Mage::app()->getStore()->getId();
		}
		else if (is_object($store)) {
			$store = $store->getId();
		}
		else {
			$store = (int)$store;
		}
		
		return $this->getDataFileDirectory() . DIRECTORY_SEPARATOR . sprintf('fseo-xml-sitemap-%d.xml', $store);
	}
	/**
	  *
	  * @return string
	  *
	 **/
	public function getDataFileDirectory()
	{
		return Mage::getBaseDir('var') . DIRECTORY_SEPARATOR . 'fseo';
	}
}
