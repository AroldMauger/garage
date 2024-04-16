<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints\NotBlank;

class NewAppointmentDTO
{
    public function __construct(
        #[DateTime]
        public readonly \DateTimeImmutable $date,
        #[NotBlank]
        public readonly string $customer,
        #[NotBlank]
        public readonly string $phone,
        #[NotBlank]
        public readonly string $car,
        #[NotBlank]
        public readonly string $reason
    )
    {

    }
}