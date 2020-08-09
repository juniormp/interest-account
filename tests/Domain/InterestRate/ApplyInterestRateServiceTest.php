<?php

use Chip\InterestAccount\Domain\InterestRate\ApplyInterestRateService;
use Chip\InterestAccount\Domain\InterestRate\InterestRatePolicyBuilder;
use Chip\InterestAccount\Domain\InterestRate\Policy\OneZeroTwoYearlyInterestRate;
use Chip\InterestAccount\Domain\InterestRate\Policy\ZeroNinetyThreeYearlyInterestRate;
use Chip\InterestAccount\Domain\User\User;
use PHPUnit\Framework\TestCase;

class ApplyInterestRateServiceTest extends TestCase
{
    private $policyOne;
    private $policyTwo;
    private $policyThree;

    public function setUp(): void
    {
        parent::setUp();
        $this->policyOne = Mockery::spy(ZeroNinetyThreeYearlyInterestRate::class);
        $this->policyTwo = Mockery::spy(ZeroNinetyThreeYearlyInterestRate::class);
        $this->policyThree = Mockery::spy(OneZeroTwoYearlyInterestRate::class);
    }

    public function test_should_apply_interest_rate_to_a_given_user()
    {
        $interestRatePolicyBuilder = $this->createMock(InterestRatePolicyBuilder::class);
        $policies = $this->buildPolicies();
        $user = $this->createMock(User::class);
        $interestRatePolicyBuilder->method('build')->willReturn($policies);
        $subject = new ApplyInterestRateService($interestRatePolicyBuilder);

        $result = $subject->apply($user);

        $this->policyOne->shouldHaveReceived('apply')->with($user)->once();
        $this->policyTwo->shouldHaveReceived('apply')->with($user)->once();
        $this->policyThree->shouldHaveReceived('apply')->with($user)->once();

        $this->assertEquals($user, $result);
        Mockery::close();
    }

    public function buildPolicies(): array
    {
        return [
            $this->policyOne,
            $this->policyTwo,
            $this->policyThree
        ];
    }
}
