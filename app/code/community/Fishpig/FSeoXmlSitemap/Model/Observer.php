<?php
/**
 *
**/
class Fishpig_FSeoXmlSitemap_Model_Observer
{
	/**
	 * Record filter URLs in a file.
	 * These will be added to the XML sitemap later
	 *
	 * @param Varien_Event_Observer $observer
	 * @return $this
	**/
	public function fseoRouterMatchUrlObserver(Varien_Event_Observer $observer)
	{
		$helper = Mage::helper('fseoxmlsitemap');

		if (!$helper->isEnabled()) {
			return $this;
		}
		
		if (!($urlKey = $observer->getEvent()->getUrlKey())) {
			return $this;
		}


		$url = $urlKey;
		
		$cacheFile = $helper->getDataFile();
		
		if (!is_file($cacheFile)) {
			@mkdir(dirname($cacheFile));
			
			$urls = '';
		}
		else {
			$urls = file_get_contents($cacheFile);
		}
		
		if (strpos($urls, $url . "\n") === false) {
			file_put_contents($cacheFile, $url . "\n", FILE_APPEND);		
		}
		
		return $this;
	}

	/**
	 * Record filter URLs in a file.
	 * These will be added to the XML sitemap later
	 *
	 * @param Varien_Event_Observer $observer
	 * @return $this
	**/
	public function sitemapSitemapSaveBeforeObserver(Varien_Event_Observer $observer)
	{
		$sitemap = $observer->getEvent()->getSitemap();
		$helper = Mage::helper('fseoxmlsitemap');

		try {
			$emulationData = Mage::getSingleton('core/app_emulation')->startEnvironmentEmulation($sitemap->getStoreId());
			$sitemapFilename = Mage::getBaseDir() . '/' . ltrim($sitemap->getSitemapPath() . $sitemap->getSitemapFilename(), '/' . DS);
			
			if (!file_exists($sitemapFilename)) {
				return $this;
			}
			
			$xml = trim(file_get_contents($sitemapFilename));
			
			// Trim off trailing </urlset> tag so we can add more
			$xml = substr($xml, 0, -strlen('</urlset>'));

			$cacheFile = $helper->getDataFile($sitemap->getStoreId());
			
			if (!is_file($cacheFile)) {
				return $this;
			}
	
			$urls = explode("\n", trim(file_get_contents($cacheFile)));
	
			foreach($urls as $urlKey) {
				if (trim($urlKey) === '') {
					continue;
				}
				
				$xml .= sprintf(
					'<url><loc>%s</loc><lastmod>%s</lastmod><changefreq>%s</changefreq><priority>%.1f</priority></url>',
					htmlspecialchars(Mage::getUrl('', array('_direct' => $urlKey))),
					date('Y-m-d'),
					'monthly',
					'0.5'
				);
			}
			
			$xml .= '</urlset>';

			@file_put_contents($sitemapFilename, $xml);
		}
		catch (Exception $e) {
			Mage::logException($e);
		}
		
		Mage::getSingleton('core/app_emulation')->stopEnvironmentEmulation($emulationData);

		return $this;
	}
}
