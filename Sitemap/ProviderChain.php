<?php

namespace Avalanche\Bundle\SitemapBundle\Sitemap;

use Symfony\Component\Process\Process;

use Avalanche\Bundle\SitemapBundle\Sitemap;

class ProviderChain implements Provider
{
    private $providers = array();

    public function add($id, /* Provider or PagingProvider */ $provider)
    {
        $this->providers[$id] = $provider;
    }

    public function populate(Sitemap $sitemap)
    {
        foreach ($this->providers as $serviceId => $provider) {
        		if($provider instanceof PagingProvider) {
        			//Run each page on a different process to avoid memory leaks issues:
        			$pages = $provider->getPages($sitemap);
        			
        			echo "Running " . count($pages) . " pages...\n";
        			
        			foreach($pages as $page) {
        				$time = time();
        				$process = new Process(__DIR__ . '/../../../../../../app/console sitemap:generate --service "' . $serviceId . '" --page ' . $page);
        				$process->run();
        				$duration = time() - $time;
        				echo "Page run in {$duration}s : $serviceId - $page: " . $process->getOutput() . "\n";
        			}
        		}
        		else {
        			$provider->populate($sitemap);
        		}
        }
    }
}
