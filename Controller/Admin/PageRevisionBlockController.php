<?php
/**
 * This file is part of the Presta Bundle project.
 *
 * @author Nicolas Bastien <nbastien@prestaconcept.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace PrestaCMS\CoreBundle\Controller\Admin;

use PrestaSonata\AdminBundle\Controller\Admin\Controller as AdminController;
use Sonata\AdminBundle\Controller\CRUDController;

/**
 * Page revision block administration controller
 * 
 * @package    PrestaCMS
 * @subpackage CoreBundle
 * @author     Nicolas Bastien <nbastien@prestaconcept.net>
 */
class PageRevisionBlockController extends CRUDController
{
    /**
     * Render a block
     * 
     * @param  integer $id
     * @return Response 
     */
    public function renderAction($id)
    {
        return $this->render('PrestaCMSCoreBundle:Admin/PageRevision:render_block.html.twig', array(
            'block' => $this->admin->getObject($id)
        ));
    }    
}