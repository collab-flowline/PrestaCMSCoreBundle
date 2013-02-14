<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Model;

use Sonata\AdminBundle\Model\ModelManagerInterface;
use Presta\CMSCoreBundle\Document\Website;
use Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RouteProvider;

/**
 * Website Manager
 *
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class WebsiteManager
{
    const WEBSITE_CLASS = 'Presta\CMSCoreBundle\Document\Website';

    /**
     * @var \Sonata\AdminBundle\Model\ModelManagerInterface
     */
    protected $modelManager;

    /**
     * @var \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RouteProvider
     */
    protected $routeProvider;

    /**
     * @var \Symfony\Cmf\Bundle\RoutingExtraBundle\Listener\IdPrefix
     */
    protected $routeListener;

    /**
     * @var array
     */
    protected $websites;

    /**
     * @var \Presta\CMSCoreBundle\Document\Website
     */
    protected $currentWebsite;

    /**
     * @var array
     */
    protected $hosts;

    public function __construct()
    {
        $this->websites = null;
        $this->currentWebsite  = null;
        $this->hosts = array();
    }

    /**
     * @param \Sonata\AdminBundle\Model\ModelManagerInterface $modelManager
     */
    public function setModelManager(ModelManagerInterface $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    /**
     * @return \Sonata\AdminBundle\Model\ModelManagerInterface
     */
    public function getModelManager()
    {
        return $this->modelManager;
    }

    /**
     * @param \Symfony\Cmf\Bundle\RoutingExtraBundle\Document\RouteProvider $routeProvider
     */
    public function setRouteProvider(RouteProvider $routeProvider)
    {
        $this->routeProvider = $routeProvider;
    }

    /**
     * @param $routeListener
     */
    public function setRouteListener($routeListener)
    {
        $this->routeListener = $routeListener;
    }

    /**
     * @return boolean
     */
    public function hasMultipleWebsite()
    {
        return (count($this->websites) > 1);
    }

    /**
     * Register a new website
     *
     * @param  array          $websiteConfiguration
     * @return WebsiteManager
     */
    public function registerWebsite($websiteConfiguration)
    {
        $path = $websiteConfiguration['path'];
        $this->websites[$path] = $websiteConfiguration;

        foreach ($websiteConfiguration['hosts'] as $env => $hosts) {
            foreach ($hosts as $hostConfiguration) {

            }
            $this->hosts[$hostConfiguration['host']] = $hostConfiguration;
            $this->hosts[$hostConfiguration['host']]['path'] = $path;
        }

        return $this;
    }

    /**
     * Check if a host is registered
     *
     * @param  string $hostCode
     * @return bool
     */
    public function hasHostRegistered($hostCode)
    {
        return isset($this->hosts[$hostCode]);
    }

    /**
     * Return current website
     *
     * @return \Application\Presta\CMSCoreBundle\Entity\Website
     */
    public function getCurrentWebsite()
    {
        return $this->currentWebsite;
    }

    /**
     * Set current website
     *
     * @param $website
     */
    public function setCurrentWebsite($website)
    {
        if (!$website instanceof Website) {
            return false;
        }
        $this->currentWebsite = $website;

        //Inject route prefix in Route Repository and listener
        $this->routeProvider->setPrefix($website->getRoutePrefix());
        $this->routeListener->setPrefix($website->getRoutePrefix());
    }

    /**
     * Get website
     *
     * @param  string  $websiteCode
     * @param  string  $locale
     * @return Website
     */
    public function getWebsite($websiteCode, $locale = null)
    {
        $website = $this->getModelManager()->find(self::WEBSITE_CLASS, $websiteCode);

        if (!$website instanceof Website) {
            return null;
        }
        if ($locale != null) {
            $website->setLocale($locale);
        }
        $this->setCurrentWebsite($website);

        return $website;
    }

    /**
     * Return available websites
     *
     * @return ArrayCollection
     */
    public function getAvailableWebsites()
    {
        return $this->getModelManager()->findBy(self::WEBSITE_CLASS, array());
    }

    /**
     * Load a website based by host
     *
     * @param  string  $host
     * @return Website
     */
    public function loadWebsiteByHost($host)
    {
        if (isset($this->hosts[$host])) {
            $website = $this->getWebsite($this->hosts[$host]['website'], $this->hosts[$host]['locale']);
        } else {
            $website = $this->getWebsite($this->defaultWebsiteCode);
        }
        $this->setCurrentWebsite($website);

        return $website;
    }
}
