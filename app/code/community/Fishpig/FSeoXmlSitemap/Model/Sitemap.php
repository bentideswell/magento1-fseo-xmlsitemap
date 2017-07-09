<?php
/**
 *
**/
class Fishpig_FSeoXmlSitemap_Model_Sitemap extends Mage_Sitemap_Model_Sitemap
{
	/**
	 * Force Magento to fire events
	**/
	protected $_eventPrefix = 'sitemap_sitemap';
	protected $_eventObject = 'sitemap';
}
