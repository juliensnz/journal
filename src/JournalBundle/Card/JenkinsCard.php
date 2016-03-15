<?php

namespace JournalBundle\Card;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Jenkins card
 */
class JenkinsCard extends BaseCard
{
    const BASE_URL = 'http://ci-master.akeneo.com/view/%s/api/json';

    protected $versions = [
        'Main - 1.2',
        'Main - 1.3',
        'Main - Master',
    ];

    /**
     * {@inheritdoc}
     */
    public function getData(array $context = [])
    {
        return array_replace_recursive(
            $this->data,
            $this->getJenkinsContent()
        );
    }

    protected function getJenkinsContent()
    {
        $context = stream_context_create(
            ['http' => ['header'  => 'Authorization: Basic ' . base64_encode('akeneo:mak2Laem')]]
        );

        $result = [];
        foreach ($this->versions as $version) {
            $jobs = json_decode(file_get_contents(
                sprintf(self::BASE_URL, $version),
                true,
                $context
            ), true)['jobs'];

            foreach ($jobs as $job) {
                $result = array_replace_recursive(
                    $result,
                    $this->addToMatrix($job)
                );
            }
        }

        return ['jobs' => $result];
    }

    protected function addToMatrix(array $job)
    {
        $name = explode('-', $job['name']);

        $version = $name[0] === '1.0' ? '1.2' : $name[0];
        $color = explode('_', $job['color'])[0];
        $edition = $name[2];
        $edition = $edition === 'enterprise' ? 'EE' : $edition;
        $edition = $edition === 'community'  ? 'CE' : $edition;

        $matrix = [];
        if ($name[3] === 'static' ||
            $name[3] === 'spec'
        ) {
            $jobType = $name[3];
        } elseif ($name[3] === 'coverage') {
            return [];
        } else {
            $name[4] = !isset($name[4]) ? 'orm' : $name[4];
            $jobType = $name[3] . ' ' . $name[4];
        }

        $jobTitle = $edition . ' ' . $jobType;
        $matrix[$jobTitle] = [];
        $matrix[$jobTitle][$version] = $color;

        return $matrix;
    }
}
