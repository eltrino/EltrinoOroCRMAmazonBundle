<?php

namespace Eltrino\OroCrmAmazonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Oro\Bundle\AddressBundle\Entity\Country;
use Oro\Bundle\EntityConfigBundle\Metadata\Annotation\Config;

use Eltrino\OroCrmAmazonBundle\Model\ExtendOrderAddress;

/**
 * @ORM\Table("eltrino_amazon_order_addr")
 * @ORM\HasLifecycleCallbacks()
 * @ORM\Entity
 * @Config(
 *       defaultValues={
 *          "entity"={
 *              "icon"="fa-map-marker"
 *          },
 *          "note"={
 *              "immutable"=true
 *          },
 *          "activity"={
 *              "immutable"=true
 *          },
 *          "attachment"={
 *              "immutable"=true
 *          }
 *      }
 * )
 */
class OrderAddress extends ExtendOrderAddress
{
    use OrderAddressTraits\CountryTextTrait;
    
    /**
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="Oro\Bundle\AddressBundle\Entity\AddressType")
     * @ORM\JoinTable(
     *     name="eltrino_amazon_order_addr_type",
     *     joinColumns={
     *          @ORM\JoinColumn(name="order_address_id", referencedColumnName="id", onDelete="CASCADE")
     *      },
     *     inverseJoinColumns={
     *          @ORM\JoinColumn(name="type_name", referencedColumnName="name")
     *      }
     * )
     */
    protected $types;
    
    /**
     * @var Order
     *
     * @ORM\ManyToOne(targetEntity="Order", inversedBy="addresses",cascade={"persist"})
     * @ORM\JoinColumn(name="owner_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $owner;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="name_prefix", type="string", length=255, nullable=true)
     */
    protected $namePrefix;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="first_name", type="string", length=255, nullable=true)
     */
    protected $firstName;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="middle_name", type="string", length=255, nullable=true)
     */
    protected $middleName;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="last_name", type="string", length=255, nullable=true)
     */
    protected $lastName;
    
    /**
     * @var string
     * 
     * @ORM\Column(name="name_suffix", type="string", length=255, nullable=true)
     */
    protected $nameSuffix;
    
    /**
     * @var string
     *
     * @ORM\Column(name="street", type="string", length=500, nullable=true)
     */
    protected $street;
    
    /**
     * @var string
     *
     * @ORM\Column(name="street2", type="string", length=500, nullable=true)
     */
    protected $street2;
    
    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;
    
    /**
     * @var string
     *
     * @ORM\Column(name="postal_code", type="string", length=255, nullable=true)
     */
    protected $postalCode;
    
    /**
     * @var Country
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\AddressBundle\Entity\Country")
     * @ORM\JoinColumn(name="country_code", referencedColumnName="iso2_code")
     */
    protected $country;
    
    /**
     * @var Region
     *
     * @ORM\ManyToOne(targetEntity="Oro\Bundle\AddressBundle\Entity\Region")
     * @ORM\JoinColumn(name="region_code", referencedColumnName="combined_code")
     */
    protected $region;
    
    /**
     * @return string
     */
    public function __toString()
    {
        return implode(', ', array_filter(array_map([
            $this->getStreet(),
            $this->getStreet2(),
            $this->getCity(),
            $this->getPostalCode(),
        ])));
    }
    
    /**
     * @param Order $owner
     *
     * @return OrderAddress
     */
    public function setOwner(Order $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return Order
     */
    public function getOwner()
    {
        return $this->owner;
    }
}