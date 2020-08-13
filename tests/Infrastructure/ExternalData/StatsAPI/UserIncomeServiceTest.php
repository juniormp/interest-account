<?php


use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\UserIncomeService;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\StatsAPIClientInterface;
use PHPUnit\Framework\TestCase;

class UserIncomeServiceTest extends TestCase
{
    /**
     * @covers ::getIncome
     */
    public function test_should_return_income_given_an_user_id()
    {
        $statsAPIClient = $this->createMock(StatsAPIClientInterface::class);
        $subject = new UserIncomeService($statsAPIClient);
        $id = "00000000-2b32-4964-aaeb-7d3c065bc0f0";
        $income = 5000;
        $statsAPIClient->method('getIncomeByUserId')->willReturn(["income" => $income]);

        $result = $subject->getIncome($id);

        $this->assertEquals($income, $result);
    }
}
