<?php


use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\Exception\StatsAPIException;
use Chip\InterestAccount\Infrastructure\ExternalData\StatsAPI\StatsAPIClient;
use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class StatsAPIClientTest extends TestCase
{
    /**
     * ::getIncomeByUserId
     */
    public function test_should_get_user_income_by_id()
    {
        $mock = new MockHandler([
            new Response(200, [], '{
                "id" : "88224979-406e-4e32-9458-55836e4e1f95",
                "income" : 499999
            }'),
        ]);
        $handlerStack = HandlerStack::create($mock);
        $subject = new StatsAPIClient(new HttpClient(['handler' => $handlerStack]));

        $response = $subject->getIncomeByUserId("88224979-406e-4e32-9458-55836e4e1f95");

        $this->assertIsArray($response);
        $this->assertEquals("88224979-406e-4e32-9458-55836e4e1f95", $response['id']);
        $this->assertEquals(499999, $response['income']);
    }

    /**
     * ::getIncomeByUserId
     */
    public function test_should_throw_stats_api_exception_in_case_of_error()
    {
        $mock = new MockHandler([
            new RequestException('Error message', new Request('GET', '/users/1'))
        ]);
        $handlerStack = HandlerStack::create($mock);
        $subject = new StatsAPIClient(new HttpClient(['handler' => $handlerStack]));

        $this->expectException(StatsAPIException::class);
        $this->expectExceptionMessage('Error message');

        $subject->getIncomeByUserId("1");
    }
}
