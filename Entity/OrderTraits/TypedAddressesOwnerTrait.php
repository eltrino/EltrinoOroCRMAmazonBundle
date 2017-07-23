<?php

namespace Eltrino\OroCrmAmazonBundle\Entity\OrderTraits;

use Oro\Bundle\AddressBundle\Entity\AddressType;
use Oro\Bundle\AddressBundle\Entity\AbstractTypedAddress;

trait TypedAddressesOwnerTrait
{
    
    /**
     * @param ArrayCollection|AbstractTypedAddress[] $addresses
     * @return $this
     */
    public function resetAddresses($addresses)
    {
        $this->addresses->clear();

        foreach ($addresses as $address) {
            $this->addAddress($address);
        }

        return $this;
    }

    /**
     * @param AbstractTypedAddress $address
     * @return $this
     */
    public function addAddress(AbstractTypedAddress $address)
    {
        if (!$this->addresses->contains($address)) {
            $this->addresses->add($address);
        }

        return $this;
    }

    /**
     * @param AbstractTypedAddress $address
     * @return $this
     */
    public function removeAddress(AbstractTypedAddress $address)
    {
        if ($this->addresses->contains($address)) {
            $this->addresses->removeElement($address);
        }

        return $this;
    }

    /**
     * @return ArrayCollection|AbstractTypedAddress[]
     */
    public function getAddresses()
    {
        return $this->addresses;
    }

    /**
     * @param AbstractTypedAddress $address
     * @return bool
     */
    public function hasAddress(AbstractTypedAddress $address)
    {
        return $this->getAddresses()->contains($address);
    }

    /**
     * @return AbstractTypedAddress
     */
    public function getBillingAddress()
    {
        $addresses = $this->getAddresses()->filter(
            function (AbstractTypedAddress $address) {
                return $address->hasTypeWithName(AddressType::TYPE_BILLING);
            }
        );

        return $addresses->first();
    }

    /**
     * @return AbstractTypedAddress
     */
    public function getShippingAddress()
    {
        $addresses = $this->getAddresses()->filter(
            function (AbstractTypedAddress $address) {
                return $address->hasTypeWithName(AddressType::TYPE_SHIPPING);
            }
        );

        return $addresses->first();
    }
}