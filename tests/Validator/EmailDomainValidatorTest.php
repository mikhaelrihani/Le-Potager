<?php

namespace App\Tests\Validator;

use App\Validator\EmailDomain;
use App\Validator\EmailDomainValidator;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Component\Validator\Violation\ConstraintViolationBuilderInterface;

class EmailDomainValidatorTest extends TestCase
{
// php bin/phpunit tests/Validator/EmailDomainValidatorTest.php --testdox
    public function getValidator($expectedViolation = false)
    {
        $validator = new EmailDomainValidator();
        $context = $this->getMockBuilder(ExecutionContextInterface::class)->getMock();

        if ($expectedViolation) {
            $violation = $this->getMockBuilder(ConstraintViolationBuilderInterface::class)->getMock();
            $violation
                ->expects($this->any())
                ->method("setParameter")
                ->willReturn($violation);
            $violation
                ->expects($this->once())
                ->method("addViolation");
            $context
                ->expects($this->once())
                ->method("buildViolation")
                ->willReturn($violation);
        } else {
            $context
                ->expects($this->never())
                ->method("buildViolation");
        }

        $validator->initialize($context);

        return $validator;
    }

    public function testCatchBadDomains(): void
    {

        $constraint = new EmailDomain(["blocked" => ["badDomain.fr", "test.com"]]);

        $this->getValidator(true)->validate("test@badDomain.fr", $constraint);
    }
    public function testCatchGoodDomains(): void
    {

        $constraint = new EmailDomain(["blocked" => ["badDomain.fr", "test.com"]]);

        $this->getValidator(false)->validate("test@goodDomain.fr", $constraint);
    }
}