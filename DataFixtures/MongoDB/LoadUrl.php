<?php 
namespace Avalanche\Bundle\SitemapBundle\DataFixtures\MongoDB;

use Avalanche\Bundle\SitemapBundle\Sitemap\Url;

use Symfony\Component\Validator\Constraints\DateTime;

use Avalanche\Bundle\SitemapBundle\AvalancheSitemapBundle;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;

use Doctrine\Common\DataFixtures\AbstractFixture;

use Doctrine\Common\Persistence\ObjectManager;

use Doctrine\ODM\MongoDB\DocumentManager;

use Symfony\Component\DependencyInjection\ContainerInterface;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;

use Doctrine\Common\DataFixtures\FixtureInterface;

class LoadUrl extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
      $this->container = $container;
    }
    
    public function getOrder() {
    	return 1;
    }

    public function load(ObjectManager $manager)
    {
    	  $url = new Url('/bar');
    	  $url->setLastmod(new \DateTime());
    	  $url->setChangefreq('daily');
    	  $url->setPriority('0.5');

    		$manager->persist($url);
    		$manager->flush();
    		
    		$this->addReference('avalanche-sitemap-url', $url);
    }
}