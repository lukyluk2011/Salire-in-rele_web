<?php

namespace App\Core;

use Nette;
use Nette\Security\SimpleIdentity;

class MyAuthenticator implements Nette\Security\Authenticator
{
    public function __construct()
    {
    }

    public function authenticate(string $username, string $password): SimpleIdentity
    {

        if ($password != "***") {
            throw new Nette\Security\AuthenticationException('Invalid password.');
        }

        return new SimpleIdentity(
            1,
            "user",
            ['sal'],
        );
    }
}
