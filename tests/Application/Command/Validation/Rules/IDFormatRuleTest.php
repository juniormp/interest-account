<?php


use Chip\InterestAccount\Application\Command\Validation\Rules\IDFormatRule;
use Chip\InterestAccount\Application\Command\Validation\ValidationError;
use PHPUnit\Framework\TestCase;

class IDFormatRuleTest extends TestCase
{
    /**
     * @covers ::validate
     */
    public function test_should_validate_id_format_to_uuidv4()
    {
        $validID = "3f950b7d-1f8f-4f86-87cb-ab819ad6cabd";

        IDFormatRule::validate($validID);

        // TODO How to assert that an exceptions was not thrown ?
        $success = true;
        $this->assertTrue($success);
    }

    /**
     * @covers ::validate
     */
    public function test_should_throw_exception_for_invalid_id()
    {
        $invalidID = "invalid-id";

        $this->expectException(ValidationError::class);
        $this->expectExceptionMessage("ID SHOULD BE UUIDv4 FORMAT");

        IDFormatRule::validate($invalidID);
    }
}
