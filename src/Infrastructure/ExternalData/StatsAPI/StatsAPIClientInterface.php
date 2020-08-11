<?php


namespace Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI;

interface StatsAPIClientInterface
{
    public function getIncomeByUserId(string $id): array;
}
