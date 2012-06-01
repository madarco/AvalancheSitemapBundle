<?php

namespace Avalanche\Bundle\SitemapBundle;
use Avalanche\Bundle\SitemapBundle\Sitemap\Url;
use Avalanche\Bundle\SitemapBundle\Sitemap\UrlRepositoryInterface;

class Sitemap {
	private $repository;
	private $page;
	private $serviceId;

	public function __construct(UrlRepositoryInterface $repository) {
		$this->repository = $repository;
		$this->page = 1;
	}

	public function add(Url $url) {
		if($this->serviceId) {
			$url->setProvider($this->serviceId);
		}
		$this->repository->add($url);
	}

	public function remove(Url $url) {
		return $this->repository->remove($url);
	}

	public function all() {
		return $this->repository->findAllOnPage($this->page);
	}

	public function get($loc) {
		return $this->repository->findOneByLoc($loc);
	}

	public function pages() {
		return $this->repository->pages();
	}

	public function setPage($page) {
		$this->page = $page;
	}

	public function getPage() {
		return $this->page;
	}

	public function save() {
		$this->repository->flush();
	}

	public function lastmod($page = null) {
		return $this->repository->getLastmod($page);
	}

	public function getServiceId() {
		return $this->serviceId;
	}

	public function setServiceId($serviceId) {
		$this->serviceId = $serviceId;
	}

}
