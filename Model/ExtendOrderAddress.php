<?php

namespace Eltrino\OroCrmAmazonBundle\Model;

use Oro\Bundle\AddressBundle\Entity\AbstractTypedAddress;

class ExtendOrderAddress extends AbstractTypedAddress
{
    public function __construct()
    {
        parent::__construct();
    }
}
