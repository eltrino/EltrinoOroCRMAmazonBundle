<?php

namespace OroCRM\Bundle\AmazonBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AmazonRestTransportSettingFormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('wsdlUrl', 'text', ['label' => 'orocrm.amazon.transport.endpoint_url', 'required' => true]);
        $builder->add('keyId', 'text', ['label' => 'orocrm.amazon.transport.access_key_id']);
        $builder->add('secret', 'text', ['label' => 'orocrm.amazon.transport.secret_access_key']);
        $builder->add('merchantId', 'text', ['label' => 'orocrm.amazon.transport.merchant_id']);
        $builder->add('marketplaceId', 'text', ['label' => 'orocrm.amazon.transport.marketplace_id']);

        $date          = new \DateTime('2007-01-01', new \DateTimeZone('UTC'));
        $syncStartDate = $date->format('Y-m-d');

        $builder->add(
            'syncStartDate',
            'oro_date',
            [
                'label'      => 'orocrm.amazon.transport.sync_start_date',
                'required'   => true,
                'tooltip'    => 'orocrm.amazon.transport.tooltip.sync_start_date',
                'empty_data' => $syncStartDate
            ]
        );

        $builder->add('checkamazonchannel', 'button', ['label' => 'orocrm.amazon.transport.check_connection']);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(['data_class' => 'OroCRM\Bundle\AmazonBundle\Entity\AmazonRestTransport']);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'orocrm_amazon_rest_transport_setting_form_type';
    }
}
