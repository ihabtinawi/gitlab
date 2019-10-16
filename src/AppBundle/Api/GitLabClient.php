<?php


namespace AppBundle\Api;

use Gitlab\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Yaml\Yaml;

class GitLabClient
{
    /**
     * @var Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client->create('http://git.exozet.com')
            ->authenticate('DqMyxiDf7dznHWyZhMvG', $client::AUTH_URL_TOKEN);
    }

    /**
     * @param $projectId
     * @param $objectAttributeId
     * @return mixed
     * @throws GuzzleException
     */
    public function getCommitsForMergeRequest($projectId, $objectAttributeId)
    {
        $response = $this->client->api('merge_requests')->commits($projectId, $objectAttributeId);
        return $response;
    }

    /**
     * @param $projectId
     * @param $objectAttributeId
     * @param $description
     * @throws GuzzleException
     */
    public function addRedmineLinksToMergeRequestDescription($projectId, $objectAttributeId, $description)
    {
        return $this->client->api('merge_requests')->update($projectId, $objectAttributeId, [
            'description' => $description
        ]);
    }
}
