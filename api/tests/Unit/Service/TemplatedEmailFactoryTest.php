<?php

declare(strict_types=1);


namespace App\Tests\Unit\Service;


use App\Service\TemplatedEmailFactory;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Mime\Exception\RfcComplianceException;

final class TemplatedEmailFactoryTest extends KernelTestCase
{
    public function testCreateEmailSuccess()
    {
        $email = TemplatedEmailFactory::create('test@app.test');

        $this->assertEquals('test@app.test', $email->getTo()['0']->getAddress());
        $this->assertEquals(TemplatedEmailFactory::EMAIL_FROM, $email->getFrom()['0']->getAddress());
        $this->assertEquals(TemplatedEmailFactory::NAME_FROM, $email->getFrom()['0']->getName());
        $this->assertEquals(TemplatedEmailFactory::SUBJECT, $email->getSubject());
        $this->assertEquals(TemplatedEmailFactory::HTML_TEMPLATE, $email->getHtmlTemplate());
    }

    public function testCreateEmailFail()
    {

        $this->expectException(RfcComplianceException::class);
        $this->expectWarningMessage('Email "not email" does not comply with addr-spec of RFC 2822.');
        TemplatedEmailFactory::create('not email');
    }
}