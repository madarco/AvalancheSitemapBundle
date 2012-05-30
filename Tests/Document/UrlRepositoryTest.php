<?php

namespace Longtale\EcommerceBundle\Tests\Repository;


use Avalanche\Bundle\SitemapBundle\Tests\KernelAwareTestAbstract;

use Avalanche\Bundle\SitemapBundle\Sitemap\Url;

use Symfony\Component\Validator\Constraints\DateTime;

use Avalanche\Bundle\SitemapBundle\Sitemap\UrlRepositoryInterface;

use Avalanche\Bundle\SitemapBundle\AvalancheSitemapBundle;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UrlRepositoryTest extends KernelAwareTestAbstract {
	
	public function setUp() {
		parent::setUp();
		$this->loadFixtures();
	}
	
	public function testAddUrlUpsert() {
		$dm = $this->getDocumentManager();
    
		/* @var $repo UrlRepositoryInterface */
		$repo = $this->get('sitemap.url.repository');
		
		$lastMod1 = new \DateTime('yesterday');
		$lastMod2 = new \DateTime();
		
		$url = new Url('/foo');
		$url->setLastmod($lastMod1);
		$url->setChangefreq('daily');
		$url->setPriority('0.7');
		
		$repo->add($url);
		$repo->flush();
		
		//Insert the url:
		$urlSaved = $repo->findOneByLoc('/foo');
		$this->_assertUrlEquals($url, $urlSaved);
		
		$url2 = new Url('/foo');
		$url2->setLastmod($lastMod2);
		$url2->setChangefreq('monthly');
		$url2->setPriority('0.9');
		$repo->add($url2);
		$repo->flush();
		
		//Overwrite the url:
		$urlSaved = $repo->findOneByLoc('/foo');
		$this->_assertUrlEquals($url2, $urlSaved);
	}
	
	protected function _assertUrlEquals(Url $url1, Url $url2) {
		$this->assertEquals($url1->getLoc(), $url2->getLoc());
		$this->assertEquals($url1->getLastmod(), $url2->getLastmod());
		$this->assertEquals($url1->getPriority(), $url2->getPriority());
		$this->assertEquals($url1->getChangefreq(), $url2->getChangefreq());
		$this->assertEquals(count($url1->all()), count($url2->all()));
	}
	
	
}