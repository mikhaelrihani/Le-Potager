<?php

namespace App\Tests\mail;

use App\Service\MyMailerService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport\NullTransport;


class MyMailerServiceTest extends KernelTestCase
{

    // php bin/phpunit tests/mail/MyMailerServiceTest.php --testdox
    public function testSendEmail()
    {
        
         // Create a transport using the NullTransport
         $transport = new NullTransport();

         // Create a Mailer using the transport
         $mailer = new Mailer($transport);

        // Create an instance of MyMailerService using the real mailer
        $mailerService = new MyMailerService($mailer, 'test@example.com');

        // Define test data
        $subject = "Test Email";
        $templatePath = __DIR__ . '/test_template.html.twig'; // Path to the HTML template file
        $context = ["variable" => "value"];
        $recipient = "recipient@example.com";

        // Load the HTML template content from the file
        $template = file_get_contents($templatePath);

        // Call the send method of MyMailerService
        $result = $mailerService->send($subject, $template, $context, $recipient);

        // Assert that the send method of MyMailerService returns true (or your preferred success indicator)
        $this->assertTrue($result);
    
    }

}
