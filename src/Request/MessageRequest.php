<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\Validator\Constraints as Assert;

class MessageRequest
{
    public function __construct(
        #[Assert\NotBlank()]
        public readonly string $message,
    ) {
    }
}

