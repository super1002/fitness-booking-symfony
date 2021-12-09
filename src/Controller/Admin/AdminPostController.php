<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminPostController extends AdminBaseController
{
    /**
     * @Route("/admin/post", name="admin_post")
     */
    public function index(EntityManagerInterface $entityManager) {
        $posts = $entityManager->getRepository(Post::class)->findAll();
        $checkCategory = $entityManager->getRepository(Category::class)->findAll();
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Посты';
        $forRender['posts'] = $posts;
        $forRender['check_category'] = $checkCategory;
        return $this->render('admin/post/index.html.twig',  $forRender);
    }

    /**
     * @Route("/admin/post/create", name="admin_post_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager) {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreateAtValue();
            $post->setUpdateAtValue();
            $post->setIsPublished();
            $entityManager->persist($post);
            $entityManager->flush();
            $this->addFlash('success', 'Пост добавлен успешно');
            return $this->redirectToRoute('admin_post');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание поста';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig',  $forRender);
    }

    /**
     * @Route("/admin/post/update/{id}", name="admin_post_update")
     */
    public function update(int $id, Request $request, EntityManagerInterface $entityManager) {
        $post = $entityManager->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $post->setUpdateAtValue();
                $this->addFlash('success', 'Пост обновлён');
            }
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($post);
                $this->addFlash('success', 'Пост удален');
            }
            $entityManager->flush();
            return $this->redirectToRoute('admin_post');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Редактирование поста';
        $forRender['form'] = $form->createView();
        return $this->render('admin/post/form.html.twig',  $forRender);
    }
}