<?php


namespace Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI;

use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\Exception\StatsAPIException;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\GuzzleException;

class StatsAPIClient implements StatsAPIClientInterface
{
    private $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getIncomeByUserId(string $id): array
    {
        try {
            $response = $this->httpClient->request('GET', 'users/'. $id);
            return json_decode($response->getBody()->getContents(), true);
        } catch (GuzzleException $e) {
            throw new StatsAPIException($e->getMessage());
        }
    }
}
