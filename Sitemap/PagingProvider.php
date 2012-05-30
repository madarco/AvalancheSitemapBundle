<?php
namespace Avalanche\Bundle\SitemapBundle\Sitemap;

use Avalanche\Bundle\SitemapBundle\Sitemap;

use Symfony\Component\Process\Process;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Avalanche\Bundle\SitemapBundle\Sitemap\Provider;

interface PagingProvider {
	
	public function getPages(Sitemap $sitemap);

	public function populatePage(Sitemap $sitemap, $page);
	
}
