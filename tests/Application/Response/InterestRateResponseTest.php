<?php


use Chip\InterestAccount\Application\Response\InterestRateResponse;
use Chip\InterestAccount\Domain\InterestRate\InterestRate;
use Chip\InterestAccount\Tests\Support\InterestRateSupportFactory;
use PHPUnit\Framework\TestCase;

class InterestRateResponseTest extends TestCase
{
    public function test_should_convert_interest_rate_to_json()
    {
        $interestRate = $this->buildInterestRate();
        $expect = $this->json();

        $result = InterestRateResponse::toJson($interestRate);

        $this->assertSame($expect, $result);
    }

    private function buildInterestRate(): InterestRate
    {
        return InterestRateSupportFactory::getInstance()::withRate(1.02)::build();
    }

    private function json(): array
    {
        return [
            "interestRate" => [
                "annualRate" => 1.02
            ]
        ];
    }
}
