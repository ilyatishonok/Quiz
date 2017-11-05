<?php

declare(strict_types=1);

namespace AppBundle\Choices;

class EmailChoice
{
    private $email;

    public function setEmail(string $email): EmailChoice
    {
        $this->email = $email;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }
}
