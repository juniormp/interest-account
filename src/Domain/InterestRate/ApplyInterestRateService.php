<?php

namespace Chip\InterestAccount\Domain\InterestRate;

use Chip\InterestAccount\Domain\User\User;

class ApplyInterestRateService
{
    private $interestRatePolicyBuilder;

    public function __construct(InterestRatePolicyBuilder $interestRatePolicyBuilder)
    {
        $this->interestRatePolicyBuilder = $interestRatePolicyBuilder;
    }

    public function apply(User $user): User
    {
        $policies = $this->interestRatePolicyBuilder->build();

        array_map(function ($policy) use ($user) {
            $policy->apply($user);
        }, $policies);

        return $user;
    }
}
