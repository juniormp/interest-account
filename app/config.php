<?php

use Chip\InterestAccount\Application\DepositFunds;
use Chip\InterestAccount\Application\ListTransactions;
use Chip\InterestAccount\Application\OpenAccount;
use Chip\InterestAccount\Domain\InterestRate\ApplyInterestRateService;
use Chip\InterestAccount\Domain\InterestRate\InterestRatePolicyBuilder;
use Chip\InterestAccount\Domain\User\UserFactory;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\GetUserIncomeService;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\StatsAPIClient;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use GuzzleHttp\Client as HttpClient;

return [

    OpenAccount::class => function () {
        return new OpenAccount(
            new ApplyInterestRateService(new InterestRatePolicyBuilder()),
            new UserFactory(),
            UserProvider::getInstance(),
            new GetUserIncomeService(new StatsAPIClient(new HttpClient([
                'base_uri' => 'https://virtserver.swaggerhub.com/juniormp/StatsAPI/1.0.0/',
                'defaults' => [
                    'headers' => ['Content-Type' => 'application/json'],
                ]
            ])))
        );
    },

    DepositFunds::class => function () {
        return new DepositFunds(
            UserProvider::getInstance()
        );
    },

    ListTransactions::class => function (){
        return new ListTransactions(
            UserProvider::getInstance()
        );
    }
];
