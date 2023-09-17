<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

readonly class MessageRequest
{
    public function __construct(
        #[Assert\NotBlank()]
        public string $message,
    ) {
    }
}

