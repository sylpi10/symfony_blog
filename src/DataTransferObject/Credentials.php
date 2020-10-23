<?php

namespace App\DataTransferObject;

use Symfony\Component\Validator\Constraint as Assert;


/**
 * class Credentials
 * @package App\DataTransferObject
 */
class Credentials {

    /**
     * @var string|null
     * Assert\NotBlank
     */
    private ?string $username = null;
    
    /**
     * @var string|null
     * Assert\NotBlank
    */
    private ?string $password = null;

    public function __construct(?string $username)
    {
        $this->username = $username;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }


}