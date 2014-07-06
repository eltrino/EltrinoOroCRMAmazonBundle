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
namespace Eltrino\OroCrmAmazonBundle\EventListener;

use Doctrine\ORM\EntityManager;

use Knp\Menu\ItemInterface;

use Symfony\Component\Routing\RouterInterface;

use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\NavigationBundle\Event\ConfigureMenuEvent;
use Eltrino\OroCrmAmazonBundle\Provider\AmazonChannelType;

/**
 * Class AmazonNavigationListener.
 * @package Eltrino\OroCrmAmazonBundle\EventListener
 */
class AmazonNavigationListener
{
    const ORDER_MENU_ITEM = 'amazon_order';

    protected static $map = [
        'order'    => [
            'parent'       => 'sales_tab',
            'parent_item'  => 'magento_order',
            'prefix'       => self::ORDER_MENU_ITEM,
            'label'        => 'Amazon',
            'route'        => 'eltrino_amazon_order_index',
            'extra_routes' => '/^eltrino_amazon_order_(index|view)$/'
        ]
    ];

    /** @var EntityManager */
    protected $em;

    /** @var RouterInterface */
    protected $router;

    public function __construct(EntityManager $em, RouterInterface $router)
    {
        $this->em     = $em;
        $this->router = $router;
    }
    /**
     * Adds dynamically menu entries depends on configured channels
     *
     * @param ConfigureMenuEvent $event
     */
    public function onNavigationConfigure(ConfigureMenuEvent $event)
    {
        $repository = $this->em->getRepository('OroIntegrationBundle:Channel');
        $channels   = $repository->getConfiguredChannelsForSync(AmazonChannelType::TYPE);

        if ($channels) {
            $entries = [];
            /** @var Channel $channel */
            foreach ($channels as $channel) {
                if ($channel->getConnectors()) {
                    foreach ($channel->getConnectors() as $connector) {
                        if (!isset($entries[$connector])) {
                            $entries[$connector] = [];
                        }
                        $entries[$connector][] = ['id' => $channel->getId(), 'label' => $channel->getName()];
                    }
                }
            }

            // walk trough prepared array
            foreach ($entries as $key => $items) {
                if (isset(self::$map[$key])) {
                    /** @var ItemInterface $reportsMenuItem */
                    $salesMenuItem = $event->getMenu()->getChild(self::$map[$key]['parent'])->getChild(self::$map[$key]['parent_item']);
                    if ($salesMenuItem) {
                        foreach ($items as $entry) {
                            $salesMenuItem->addChild(
                                implode([self::$map[$key]['prefix'], $entry['id']]),
                                [
                                    'route' => self::$map[$key]['route'],
                                    'routeParameters' => ['id' => $entry['id']],
                                    'label' => $entry['label'],
                                    'check_access' => false,
                                    'extras' => ['routes' => self::$map[$key]['extra_routes']]
                                ]
                            );
                        }
                    }
                }
            }
        }
    }
}
