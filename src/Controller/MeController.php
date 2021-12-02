<?php

namespace App\Controller;

use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Security\Core\Security;

#[AsController]
class MeController
{

    public function __construct(private Security $security)
    {
    }

    public function __invoke()
    {
        $user = $this->security->getUser();
        return $user;
    }
}
