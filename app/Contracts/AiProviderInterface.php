<?php

namespace App\Contracts;

interface AiProviderInterface
{
    /**
     * Generate plain text from a prompt.
     */
    public function complete(string $prompt, array $options = []): string;

    /**
     * Optional provider name for logging.
     */
    public function name(): string;

    /**
     * Model currently in use.
     */
    public function model(): string;
}
