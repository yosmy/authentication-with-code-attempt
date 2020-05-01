<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;
use LogicException;

class AnalyzePreStartAuthenticationWithCodeToControlAttemptTest extends TestCase
{
    public function testAnalyze()
    {
        $device = 'device';
        $country = 'country';
        $prefix = 'prefix';
        $number = 'number';

        $increaseAttempt = $this->createMock(Yosmy\IncreaseAttempt::class);

        $increaseAttempt->expects($this->once())
            ->method('increase')
            ->with(
                'yosmy.start_authentication_with_code',
                sprintf('phone-%s-%s-%s', $country, $prefix, $number),
                3,
                '1 day'
            );

        $analyzeStartPreAuthenticationWithCodeToControlAttempt = new Yosmy\AnalyzePreStartAuthenticationWithCodeToControlAttempt(
            $increaseAttempt
        );

        try {
            $analyzeStartPreAuthenticationWithCodeToControlAttempt->analyze(
                $device,
                $country,
                $prefix,
                $number
            );
        } catch (Yosmy\DeniedAuthenticationException $e) {
            throw new LogicException();
        }
    }

    /**
     * @throws Yosmy\DeniedAuthenticationException
     */
    public function testAnalyzeHavingExceededAttemptException()
    {
        $device = 'device';
        $country = 'country';
        $prefix = 'prefix';
        $number = 'number';

        $increaseAttempt = $this->createMock(Yosmy\IncreaseAttempt::class);

        $increaseAttempt->expects($this->once())
            ->method('increase')
            ->with(
                'yosmy.start_authentication_with_code',
                sprintf('phone-%s-%s-%s', $country, $prefix, $number),
                3,
                '1 day'
            )
            ->willThrowException(new Yosmy\BaseExceededAttemptException());

        $analyzeStartPreAuthenticationWithCodeToControlAttempt = new Yosmy\AnalyzePreStartAuthenticationWithCodeToControlAttempt(
            $increaseAttempt
        );

        $this->expectException(Yosmy\DeniedAuthenticationException::class);

        $analyzeStartPreAuthenticationWithCodeToControlAttempt->analyze(
            $device,
            $country,
            $prefix,
            $number
        );
    }
}