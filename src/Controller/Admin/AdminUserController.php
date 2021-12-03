<?php

namespace App\Controller\Admin;
use App\Entity\User;
use App\Form\UserType;
use  Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminUserController extends AdminBaseController
{
    /**
     * @Route("/admin/users", name="admin_users")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index(EntityManagerInterface $entityManager) {
        $users = $entityManager->getRepository(User::class)->findAll();
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Пользователи';
        $forRender['users'] = $users;
        return $this->render('admin/user/index.html.twig', $forRender);
    }

    /**
     * @Route("/admin/users/create", name="admin_user_create")
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param EntityManagerInterface $entityManager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $entityManager) {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if (($form->isSubmitted()) && ($form->isValid())) {
            $password = $passwordHasher->hashPassword($user,  $user->getPlainPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_ADMIN']);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('admin_users');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Форма регистрации пользователя';
        $forRender['form'] = $form->createView();
        return $this->render('admin/user/form.html.twig', $forRender);
    }
}