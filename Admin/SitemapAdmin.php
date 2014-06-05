<?php
namespace Avalanche\Bundle\SitemapBundle\Admin;

use Exporter\Source\DoctrineORMQuerySourceIterator;

use Doctrine\ODM\MongoDB\DocumentManager;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;
use Longtale\ListingBundle\Common\Manager\BroadcastManager;

use SaadTazi\GChartBundle\DataTable;

class SitemapAdmin extends Admin {

    protected $baseRouteName = 'sitemap_url_min';
    protected $baseRoutePattern = '/longtale/sitemap/url';

	protected $datagridValues = array(
			'_sort_order' => 'DESC', // Descendant ordering (default = 'ASC')
			'_sort_by' => 'lastmod'
	);

	protected function configureFormFields(FormMapper $formMapper) {
			$formMapper->add('loc')->add('priority')
                ->add('changefreq')
                ->add('images')
				->add('provider')
                ->add('lastmod')
				;
	}
	
	
	protected function configureShowFields(ShowMapper $showMapper) {
		$showMapper->add('loc')->add('priority')
            ->add('changefreq')
            ->add('images')
            ->add('provider')
            ->add('lastmod')
        ;
	}

	protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
		$datagridMapper->add('loc')->add('provider');
	}
	

	protected function configureListFields(ListMapper $listMapper) {
		$listMapper
						->add('loc', 'string', array('template' => 'AvalanceSitemapBundle:Admin:field_sitemap_url.html.twig'))
						->add('provider' )
						->add('changefreq')
                        ->add('priority')

						->add('lastmod')
						->add('_action', 'actions', array(
								'actions' => array(
										'edit' => array(),
										'view' => array(),
										'delete' => array(),
								)
						))
						;
	}
	
	public function getExportFields() {
		return array(
				'id','loc', 'changefreq', 'priority', 'lastmod'
        );
	}
	

}
