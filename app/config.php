<?php

use Chip\InterestAccount\Application\CalculatePayout;
use Chip\InterestAccount\Application\ClosePayouts;
use Chip\InterestAccount\Application\DepositFunds;
use Chip\InterestAccount\Application\ListTransactions;
use Chip\InterestAccount\Application\OpenAccount;
use Chip\InterestAccount\Domain\InterestRate\ApplyInterestRateService;
use Chip\InterestAccount\Domain\InterestRate\InterestRatePolicyBuilder;
use Chip\InterestAccount\Domain\Payout\DepositPayoutService;
use Chip\InterestAccount\Domain\Payout\InterestRatePayoutService;
use Chip\InterestAccount\Domain\Payout\PayoutFactory;
use Chip\InterestAccount\Domain\User\UserFactory;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\UserIncomeService;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\StatsAPIClient;
use Chip\InterestAccount\Infrastructure\Repository\Payout\PayoutProvider;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;
use Chip\InterestAccount\Infrastructure\Scheduler\PayoutScheduler;
use Crunz\Schedule;
use GuzzleHttp\Client as HttpClient;

return [

    OpenAccount::class => function () {
        return new OpenAccount(
            new ApplyInterestRateService(new InterestRatePolicyBuilder()),
            new UserFactory(),
            UserProvider::getInstance(),
            new UserIncomeService(new StatsAPIClient(new HttpClient([
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

    ListTransactions::class => function () {
        return new ListTransactions(
            UserProvider::getInstance()
        );
    },

    CalculatePayout::class => function () {
        return new CalculatePayout(
            new InterestRatePayoutService(
                new PayoutProvider(),
                new PayoutFactory(),
            ),
            UserProvider::getInstance(),
            new PayoutProvider(),
            new DepositPayoutService(
                new PayoutProvider(),
                new PayoutFactory(),
            )
        );
    },

    ClosePayouts::class => function () {
        return new ClosePayouts(
            UserProvider::getInstance(),
            new CalculatePayout(
                new InterestRatePayoutService(
                    new PayoutProvider(),
                    new PayoutFactory(),
                ),
                UserProvider::getInstance(),
                new PayoutProvider(),
                new DepositPayoutService(
                    new PayoutProvider(),
                    new PayoutFactory(),
                )
            )
        );
    },

    PayoutScheduler::class => function () {
        return new PayoutScheduler(
            new Schedule(),
            new ClosePayouts(
                UserProvider::getInstance(),
                new CalculatePayout(
                    new InterestRatePayoutService(
                        new PayoutProvider(),
                        new PayoutFactory(),
                    ),
                    UserProvider::getInstance(),
                    new PayoutProvider(),
                    new DepositPayoutService(
                        new PayoutProvider(),
                        new PayoutFactory(),
                    )
                )
            )
        );
    }
];
