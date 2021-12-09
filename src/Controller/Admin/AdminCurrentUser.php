<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;

class AdminCurrentUser extends AdminBaseController
{

    public function index() {
        $userName = $this->getUser()->getName();
        $userSurname = $this->getUser()->getSurname();
        return $userName . " " . $userSurname;
    }
}