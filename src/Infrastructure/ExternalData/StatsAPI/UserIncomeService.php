<?php

namespace Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI;

class UserIncomeService
{
    private $statsAPIClient;

    public function __construct(StatsAPIClientInterface $statsAPIClient)
    {
        $this->statsAPIClient = $statsAPIClient;
    }

    public function getIncome(string $id): float
    {
        $json = $this->statsAPIClient->getIncomeByUserId($id);

        return $json['income'];
    }
}
