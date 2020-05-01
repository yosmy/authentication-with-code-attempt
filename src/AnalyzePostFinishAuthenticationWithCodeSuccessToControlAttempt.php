<?php

namespace Yosmy;

/**
 * @di\service({
 *     tags: [
 *         'yosmy.post_finish_authentication_with_code_success',
 *     ]
 * })
 */
class AnalyzePostFinishAuthenticationWithCodeSuccessToControlAttempt implements AnalyzePostFinishAuthenticationWithCodeSuccess
{
    /**
     * @var DeleteAttempt
     */
    private $deleteAttempt;

    /**
     * @param DeleteAttempt $deleteAttempt
     */
    public function __construct(
        DeleteAttempt $deleteAttempt
    ) {
        $this->deleteAttempt = $deleteAttempt;
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

        $this->deleteAttempt->delete(
            'yosmy.start_authentication_with_code',
            sprintf('phone-%s-%s-%s', $country, $prefix, $number)
        );

        $this->deleteAttempt->delete(
            'yosmy.finish_authentication_with_code',
            sprintf('phone-%s-%s-%s', $country, $prefix, $number)
        );
    }
}
