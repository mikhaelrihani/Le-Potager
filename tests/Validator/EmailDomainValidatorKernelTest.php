<?php

namespace App\Tests\Validator;

use App\Validator\EmailDomain;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EmailDomainValidatorKernelTest extends KernelTestCase
{
    // php bin/phpunit tests/Validator/EmailDomainValidatorKernelTest.php --testdox
    private ValidatorInterface $validator;

    protected function setUp(): void
    {
        parent::setUp();

        self::bootKernel();
        $this->validator = static::getContainer()->get('validator');
    }

    private function assertEmailValidation(string $email, bool $shouldBeValid): void
    {
        $constraint = new EmailDomain(["blocked" => ["badDomain.fr", "test.com"]]);
        $violationList = $this->validator->validate($email, $constraint);

        if ($shouldBeValid) {
            $this->assertCount(0, $violationList);
        } else {
            $this->assertCount(1, $violationList);
        }
    }

    public function testCatchBadDomains(): void
    {
        $this->assertEmailValidation("test@badDomain.fr", false);
    }

    public function testCatchGoodDomains(): void
    {
        $this->assertEmailValidation("test@goodDomain.fr", true);
    }
}
