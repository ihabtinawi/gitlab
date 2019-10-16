<?php

namespace AppBundle\Controller;

use AppBundle\Api\GitLabClient;
use AppBundle\Services\GitLabCommitsService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GitlabWebhookController extends Controller
{
    /**
     * @Route("/gitlab/webhook/mergerequest", name="app.git_lab.merge_request_hook")
     * @Method({"POST"})
     */

    public function mergerequestHookAction(Request $request)
    {
        $mergerequestEvent = json_decode($request->getContent(), true);

        $gitlabClient = $this->get(GitLabClient::class);
        $commits = $gitlabClient->getCommitsForMergeRequest(
            $mergerequestEvent['project']['id'],
            $mergerequestEvent['object_attributes']['iid']
        );

        $gitLabService = $this->get(GitLabCommitsService::class);
        $links = $gitLabService->generateRedmineLinks($commits);
        if ($links !== '') {
            $gitlabClient->addRedmineLinksToMergeRequestDescription(
                $mergerequestEvent['project']['id'],
                $mergerequestEvent['object_attributes']['iid'],
                $links
            );
        }

        return new JsonResponse(['result' => 'ok']);
    }
}
