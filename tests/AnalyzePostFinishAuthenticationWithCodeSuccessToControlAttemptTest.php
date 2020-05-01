<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;

class AnalyzePostFinishAuthenticationWithCodeSuccessToControlAttemptTest extends TestCase
{
    public function testAnalyze()
    {
        $device = 'device';
        $country = 'country';
        $prefix = 'prefix';
        $number = 'number';

        $deleteAttempt = $this->createMock(Yosmy\DeleteAttempt::class);

        $deleteAttempt->expects($this->at(0))
            ->method('delete')
            ->with(
                'yosmy.start_authentication_with_code',
                sprintf('phone-%s-%s-%s', $country, $prefix, $number)
            );

        $deleteAttempt->expects($this->at(1))
            ->method('delete')
            ->with(
                'yosmy.finish_authentication_with_code',
                sprintf('phone-%s-%s-%s', $country, $prefix, $number)
            );

        $analyzeFinishPostAuthenticationWithCodeSuccessToControlAttempt = new Yosmy\AnalyzePostFinishAuthenticationWithCodeSuccessToControlAttempt(
            $deleteAttempt
        );

        $analyzeFinishPostAuthenticationWithCodeSuccessToControlAttempt->analyze(
            $device,
            $country,
            $prefix,
            $number
        );
    }
}