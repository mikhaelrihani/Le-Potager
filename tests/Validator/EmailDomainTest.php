<?php

namespace App\Tests\Validator;

use App\Validator\EmailDomain;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Exception\ConstraintDefinitionException;
use Symfony\Component\Validator\Exception\MissingOptionsException;

class EmailDomainTest extends TestCase
{
    // php bin/phpunit tests/Validator/EmailDomainTest.php --testdox
    public function testRequiredParameters(): void
    {
        $this->expectException(MissingOptionsException::class);

        new EmailDomain();
    }

    public function testBlockedParameters(){
        $this->expectException(ConstraintDefinitionException::class);
        new EmailDomain(["blocked"=>"test"]);
    }

    public function testOptionIsSetAsProperty(){
        $domain = new EmailDomain(["blocked" => ["a","b"]]);
        $this->assertEquals(["a","b"],$domain->blocked);
    }
}
