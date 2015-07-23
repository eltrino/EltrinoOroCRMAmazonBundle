<?php

namespace OroCRM\Bundle\AmazonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use OroCRM\Bundle\AmazonBundle\Entity\Order;

/**
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @Route("/", name="orocrm_amazon_order_index")
     * @Template
     * @return array
     */
    public function indexAction()
    {
        return [
            'entity_class' => $this->container->getParameter('orocrm_amazon.order.entity.class')
        ];
    }

    /**
     * @Route("/view/{id}", name="orocrm_amazon_order_view", requirements={"id"="\d+"}))
     * @Template
     * @param Order $order
     * @return array
     */
    public function viewAction(Order $order)
    {
        return ['entity' => $order];
    }

    /**
     * @Route("/info/{id}", name="orocrm_amazon_order_widget_info", requirements={"id"="\d+"}))
     * @Template
     * @param Order $order
     * @return array
     */
    public function infoAction(Order $order)
    {
        return ['entity' => $order];
    }

    /**
     * @Route("/widget/grid/{id}", name="orocrm_amazon_order_widget_items", requirements={"id"="\d+"}))
     * @Template
     * @param Order $order
     * @return array
     */
    public function itemsAction(Order $order)
    {
        return ['entity' => $order];
    }
}
