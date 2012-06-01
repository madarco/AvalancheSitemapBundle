<?php

namespace Avalanche\Bundle\SitemapBundle\Command;

use Symfony\Component\Console\Input\InputOption;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Avalanche\Bundle\SitemapBundle\Sitemap\Provider;

class GenerateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('sitemap:generate')
            ->setDescription('Generate sitemap, using its data providers.');
        
        $this->addOption('service', null, InputOption::VALUE_OPTIONAL, 'The Service Id of the specific sitemap to populate');
        $this->addOption('page', null, InputOption::VALUE_OPTIONAL, 'When --sitemap is specified, this indicate the page to pupulate (usefull for very large sitemaps, ie: > 20.000 urls)');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $sitemap = $this->getContainer()->get('sitemap');

        if($input->getOption('service')) {
        	$sitemapCmd = $this->getContainer()->get($input->getOption('service'));
        	$sitemap->setServiceId($input->getOption('service'));
        	$sitemapCmd->populatePage($sitemap, $input->getOption('page'));
        }
        else {
        	$this->getContainer()->get('sitemap.provider.chain')->populate($sitemap);
        }

        $output->write('<info>Sitemap was sucessfully populated!</info>', true);
    }
}
