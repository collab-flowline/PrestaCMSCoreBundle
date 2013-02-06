<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Presta\CMSCoreBundle\Block;

use Symfony\Component\HttpFoundation\Response;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\BlockBundle\Model\BlockInterface;

use Presta\CMSCoreBundle\Block\BaseBlockService;

/**
 * Block Editor
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageChildrenBlockService extends BaseBlockService
{
    /**
     * @var \Presta\CMSCoreBundle\Model\PageManager
     */
    protected $pageManager;

    public function setPageManager($pageManager)
    {
        $this->pageManager = $pageManager;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(BlockInterface $block, Response $response = null)
    {
        $settings = array_merge($this->getDefaultSettings(), $block->getSettings());

        return $this->renderResponse('PrestaCMSCoreBundle:Block:block_page_children.html.twig', array(
            'block'     => $block,
            'settings'  => $settings,
            'page'      => $this->pageManager->getCurrentPage()
        ), $response);
    }

    /**
     * {@inheritdoc}
     */
    public function buildEditForm(FormMapper $formMapper, BlockInterface $block)
    {
        $formMapper
            ->with($this->trans($block->getType()))
                ->add('settings', 'sonata_type_immutable_array', array(
                    'keys' => array(
                        array('title', 'text', array('required' => false, 'label' => $this->trans('form.label_title'))),
                        array('content', 'textarea', array('attr' => array(), 'label' => $this->trans('form.label_content'))),
                    ),
                    'label' => $this->trans('form.label_settings')
                ))
            ->end();
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultSettings()
    {
        return array(
            'title' => 'page children title',
            'content' => 'Insert your custom content here',
        );
    }
}
