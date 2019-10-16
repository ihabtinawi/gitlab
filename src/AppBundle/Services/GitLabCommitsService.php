<?php

namespace AppBundle\Services;

class GitLabCommitsService
{
    const SEGMENT_PATTERN =  '/#(\d{5,6})/';
    /**
     * @param $gitLabResponse
     * @return string
     */
    public function generateRedmineLinks($gitLabResponse)
    {
        $redmineLinks = [];

        $referenceNumbers = array_filter(array_unique($this->getReferenceNumbers($gitLabResponse)));

        foreach ($referenceNumbers as $referenceNumber) {
            $redmineLinks [] = sprintf('http://redmine.exozet.com/issues/%d<br>', $referenceNumber);
        }
        return implode($redmineLinks);
    }

    /**
     * @param $gitLabResponse
     * @return array
     */
    private function getReferenceNumbers($gitLabResponse)
    {
        $referenceNumbers = [];

        $commits = $this->getCommits($gitLabResponse);

        foreach ($commits as $commit) {
            $referenceNumbers [] = $this->regEx($commit);
        }
        return $referenceNumbers;
    }

    /**
     * @param $gitLabResponse
     * @return array
     */
    private function getCommits($gitLabResponse)
    {
        $commits = [];
        for ($i=0; $i< count($gitLabResponse); $i++) {
            $commits [] = $gitLabResponse[$i]['message'];
        }
        return $commits;
    }

    /**
     * @param $commit
     * @return mixed|string
     */
    private function regEx($commit)
    {
        preg_match(self::SEGMENT_PATTERN, $commit, $referenceNumber);
        return isset($referenceNumber[1])? $referenceNumber[1]: '';
    }
}
