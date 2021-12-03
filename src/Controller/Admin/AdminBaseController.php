<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBaseController extends AbstractController
{
    public function renderDefault() {
        return [
            'title' => 'Система учета клиентов фитнесс-центра админ панель'
        ];
    }
}