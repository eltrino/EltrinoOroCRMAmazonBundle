<?php

namespace OroCRM\Bundle\AmazonBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class AmazonController extends Controller
{
    /**
     * @Route("/check-status", name="orocrm_amazon_check_status")
     * @param Request $request
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Doctrine\ORM\TransactionRequiredException
     */
    public function checkStatusAction(Request $request)
    {
        $transport = $this->get('orocrm_amazon.amazon_rest_transport');
        $data      = null;

        if ($id = $request->get('id', false)) {
            $data = $this->get('doctrine.orm.entity_manager')->find($transport->getSettingsEntityFQCN(), $id);
        }

        $form = $this->get('form.factory')
            ->createNamed('check-status', $transport->getSettingsFormType(), $data, ['csrf_protection' => false]);
        $form->submit($request);
        $transportEntity = $form->getData();
        $transport->init($transportEntity);

        return new JsonResponse(['success' => $transport->getStatus()]);
    }
}
