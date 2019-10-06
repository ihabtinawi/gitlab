<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GitlabWebhookController extends Controller
{
    /**
     * @Route("/gitlab/webhook/push", name="app.git_lab.push_hook")
     * @Method({"POST"})
     */

    public function pushHookAction(Request $request)
    {
        $pushEvent = json_decode($request->getContent(),true);

        $fp = fopen('pushrequest.txt', 'w+');
        fwrite($fp, print_r(sprintf('https://git.exozet.com/api/v4/projects/%d/merge_requests/%d/commits',$pushEvent['project']['id'], $pushEvent['object_attributes']['iid']), true));
        fclose($fp);
        $result = ['status' => 'ok'];
        return new JsonResponse($result);
    }
}
