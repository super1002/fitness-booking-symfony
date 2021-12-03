<?php

namespace App\Controller\Admin;
use Symfony\Component\Routing\Annotation\Route;

class AdminHomeController extends AdminBaseController
{
    /**
     * @Route("/admin", name="admin_home")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index() {
        $forRender = parent::renderDefault();
        return $this->render('admin/index.html.twig', $forRender);
    }
}