<?php

namespace App\Services;

use Elasticsearch\ClientBuilder;

class ElasticsearchService
{
    private $client = null;

    public function getClient()
    {
        if ($this->client == null) {
            $config = config('search.elasticsearch');
            $builder = ClientBuilder::create()
                ->setHosts($config['hosts']);
            if ($config['auth'] === 'basic') {
                $builder->setBasicAuthentication($config['username'], $config['password']);
            }
            $this->client = $builder->build();
        }
        return $this->client;
    }
}
