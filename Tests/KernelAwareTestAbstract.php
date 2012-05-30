<?php

namespace Avalanche\Bundle\SitemapBundle\Tests;

use Doctrine\ODM\MongoDB\DocumentManager;

use Doctrine\Common\DataFixtures\Executor\MongoDBExecutor;

use Doctrine\Common\DataFixtures\Purger\MongoDBPurger;

use Doctrine\Common\DataFixtures\ReferenceRepository;

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\DoctrineFixturesBundle\Common\DataFixtures\Loader as DataFixturesLoader;
use InvalidArgumentException;

require_once dirname(__DIR__).'/../../../../../app/AppKernel.php';

/**
 * Test case class helpful with Entity tests requiring the database interaction.
 * This class require a config_test.yml file with the different settings for the tests (for example: a different database)
 * For regular entity tests it's better to extend standard \PHPUnit_Framework_TestCase instead.
 */
abstract class KernelAwareTestAbstract extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Symfony\Component\HttpKernel\AppKernel
     */
    protected $kernel;

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $entityManager;

    /**
     * @var Symfony\Component\DependencyInjection\Container
     */
    protected $container;

    /**
     * Fixture reference repository
     * @var ReferenceRepository
     */
    protected $referenceRepository;
    
    /**
     * @return null
     */
    public function setUp()
    {
        $this->kernel = new \AppKernel('test', true);
        $this->kernel->boot();
        
        $this->container = $this->kernel->getContainer();

        parent::setUp();
    }
    
    protected function loadFixtures() {

        $dm = $this->getDocumentManager();

        $paths = array();
        foreach ($this->kernel->getBundles() as $bundle) {
          $paths[] = $bundle->getPath().'/DataFixtures/MongoDB';
        }
        
        $loader = new DataFixturesLoader($this->getContainer());
        foreach ($paths as $path) {
        	if (is_dir($path)) {
        		$loader->loadFromDirectory($path);
        	}
        }
        
        $fixtures = $loader->getFixtures();
        if (!$fixtures) {
        	throw new InvalidArgumentException(
        			sprintf('Could not find any fixtures to load in: %s', "\n\n- ".implode("\n- ", $paths))
        	);
        }
        
        $purger = new MongoDBPurger($dm);
        $executor = new MongoDBExecutor($dm, $purger);

        $executor->execute($fixtures);
        $this->referenceRepository = $executor->getReferenceRepository();
    }
    
    /**
     * @return null
     */
    public function tearDown()
    {
        $this->kernel->shutdown();

        parent::tearDown();
    }

    /**
     * @return \Doctrine\Common\DataFixtures\ReferenceRepository
     */
    protected function getReferenceRepository() {
    	return $this->referenceRepository;
    }
    
    protected function getContainer() {
    	return $this->kernel->getContainer();
    }
    
    protected function get($serviceName) {
    	return $this->getContainer()->get($serviceName);
    }

    /**
     * @return \Doctrine\ODM\MongoDB\DocumentManager
     */
    protected function getDocumentManager() {
    	return $this->get('doctrine.odm.mongodb.document_manager');
    }
}