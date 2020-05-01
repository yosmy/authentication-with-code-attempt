<?php

namespace Yosmy;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.pre_finish_authentication_with_code',
 *     ]
 * })
 */
class AnalyzePreFinishAuthenticationWithCodeToControlAttempt implements AnalyzePreFinishAuthenticationWithCode
{
    /**
     * @var IncreaseAttempt
     */
    private $increaseAttempt;

    /**
     * @param IncreaseAttempt $increaseAttempt
     */
    public function __construct(
        IncreaseAttempt $increaseAttempt
    ) {
        $this->increaseAttempt = $increaseAttempt;
    }

    /**
     * {@inheritDoc}
     */
    public function analyze(
        string $device,
        string $country,
        string $prefix,
        string $number
    ) {
        unset($device);

        try {
            $this->increaseAttempt->increase(
                'yosmy.finish_authentication_with_code',
                sprintf('phone-%s-%s-%s', $country, $prefix, $number),
                3,
                '1 day'
            );
        } catch (ExceededAttemptException $e) {
            throw new DeniedAuthenticationException('Has excedido los intentos');
        }
    }
}
