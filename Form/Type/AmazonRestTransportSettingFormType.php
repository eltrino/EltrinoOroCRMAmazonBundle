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
namespace Eltrino\OroCrmAmazonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Oro\Bundle\IntegrationBundle\Provider\TransportInterface;
use Oro\Bundle\FormBundle\Form\DataTransformer\ArrayToJsonTransformer;
use Oro\Bundle\IntegrationBundle\Manager\TypesRegistry;

class AmazonRestTransportSettingFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('wsdlUrl', 'text', ['label' => 'Endpoint url', 'required' => true]);
        $builder->add('keyId', 'text', ['label' => 'Acces Key ID']);
        $builder->add('secret', 'text', ['label' => 'Secret Access Key']);
        $builder->add('merchantId', 'text', ['label' => 'Merchant ID']);
        $builder->add('marketplaceId', 'text', ['label' => 'Marketplace ID']);

        $date = new \DateTime('2007-01-01', new \DateTimeZone('UTC'));
        $syncStartDate = $date->format('Y-m-d');

        $builder->add(
            'syncStartDate',
            'oro_date',
            [
                'label'      => 'Sync start date',
                'required'   => true,
                'tooltip'    => 'Provide the start date you wish to import data from.',
                'empty_data' => $syncStartDate
            ]
        );

        $builder->add('checkamazonchannel', 'button', ['label' => 'Check connection']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'Eltrino\OroCrmAmazonBundle\Entity\AmazonRestTransport']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'eltrino_amazon_rest_transport_setting_form_type';
    }
} 