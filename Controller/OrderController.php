<?php
/*
 * Copyright (c) 2014 Eltrino LLC (http://eltrino.com)
 *
 * Licensed under the Open Software License (OSL 3.0).
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://opensource.org/licenses/osl-3.0.php
 *
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@eltrino.com so we can send you a copy immediately.
 */
namespace Eltrino\OroCrmAmazonBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Eltrino\OroCrmAmazonBundle\Entity\Order;

/**
 * @Route("/order")
 */
class OrderController extends Controller
{
    /**
     * @Route("/{id}", name="eltrino_amazon_order_index")
     * @Template
     */
    public function indexAction(Channel $channel)
    {
        return ['channelId' => $channel->getId()];
    }

    /**
     * @Route("/view/{id}", name="eltrino_amazon_order_view", requirements={"id"="\d+"}))
     * @Template
     */
    public function viewAction(Order $order)
    {
        return ['entity' => $order];
    }

    /**
     * @Route("/info/{id}", name="eltrino_amazon_order_widget_info", requirements={"id"="\d+"}))
     * @Template
     */
    public function infoAction(Order $order)
    {
        return ['entity' => $order];
    }

    /**
     * @Route("/widget/grid/{id}", name="eltrino_amazon_order_widget_items", requirements={"id"="\d+"}))
     * @Template
     */
    public function itemsAction(Order $order)
    {
        return ['entity' => $order];
    }
}
