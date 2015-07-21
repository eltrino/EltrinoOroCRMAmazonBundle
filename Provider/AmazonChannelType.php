<?php

namespace OroCRM\Bundle\AmazonBundle\Provider;

use Oro\Bundle\IntegrationBundle\Provider\ChannelInterface;

class AmazonChannelType implements ChannelInterface
{
    const TYPE = 'amazon';

    /**
     * {@inheritdoc}
     */
    public function getLabel()
    {
        return 'Amazon';
    }
}
