<?php

use Chip\InterestAccount\Application\OpenAccount;
use Chip\InterestAccount\Domain\InterestRate\ApplyInterestRateService;
use Chip\InterestAccount\Domain\InterestRate\InterestRatePolicyBuilder;
use Chip\InterestAccount\Domain\User\UserFactory;
use Chip\InterestAccount\Infrastructure\Repository\User\UserProvider;

return [
    OpenAccount::class => function () {
        return new OpenAccount(
            new ApplyInterestRateService(new InterestRatePolicyBuilder()),
            new UserFactory(),
            UserProvider::getInstance()
        );
    },
];
