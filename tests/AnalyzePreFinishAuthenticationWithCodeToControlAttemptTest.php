<?php

namespace Yosmy\Test;

use Yosmy;
use PHPUnit\Framework\TestCase;
use LogicException;

class AnalyzePreFinishAuthenticationWithCodeToControlAttemptTest extends TestCase
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
                'yosmy.finish_authentication_with_code',
                sprintf('phone-%s-%s-%s', $country, $prefix, $number),
                3,
                '1 day'
            );

        $analyzeFinishPreAuthenticationWithCodeToControlAttempt = new Yosmy\AnalyzePreFinishAuthenticationWithCodeToControlAttempt(
            $increaseAttempt
        );

        try {
            $analyzeFinishPreAuthenticationWithCodeToControlAttempt->analyze(
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
                'yosmy.finish_authentication_with_code',
                sprintf('phone-%s-%s-%s', $country, $prefix, $number),
                3,
                '1 day'
            )
            ->willThrowException(new Yosmy\BaseExceededAttemptException());

        $analyzeFinishPreAuthenticationWithCodeToControlAttempt = new Yosmy\AnalyzePreFinishAuthenticationWithCodeToControlAttempt(
            $increaseAttempt
        );

        $this->expectException(Yosmy\DeniedAuthenticationException::class);

        $analyzeFinishPreAuthenticationWithCodeToControlAttempt->analyze(
            $device,
            $country,
            $prefix,
            $number
        );
    }
}