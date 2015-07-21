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

namespace OroCRM\Bundle\AmazonBundle\Controller;

use Oro\Bundle\IntegrationBundle\Entity\Transport;
use OroCRM\Bundle\AmazonBundle\Amazon\AmazonRestClientFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Oro\Bundle\IntegrationBundle\Entity\Channel;
use Oro\Bundle\IntegrationBundle\Provider\ConnectorInterface;

use OroCRM\Bundle\AmazonBundle\Entity\AmazonRestTransport;

/**
 * @Route("/rest")
 */
class AmazonRestController extends Controller
{
    /**
     * @Route("/check", name="orocrm_amazon_rest_check")
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function checkAction(Request $request)
    {
        $transport = $this->get('orocrm_amazon.amazon_rest_transport');
        $data      = null;

        if ($id = $request->get('id', false)) {
            $data = $this->get('doctrine.orm.entity_manager')->find($transport->getSettingsEntityFQCN(), $id);
        }

        $form = $this->get('form.factory')
            ->createNamed('rest-check', $transport->getSettingsFormType(), $data, ['csrf_protection' => false]);
        $form->submit($request);
        /**
         * @var Transport $transportEntity
         */
        $transportEntity = $form->getData();
        $transport->init($transportEntity);

        return new JsonResponse(['success' => $transport->getStatus()]);
    }
}
