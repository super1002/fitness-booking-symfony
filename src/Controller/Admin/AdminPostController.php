<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Entity\Post;
use App\Form\PostType;
use App\Service\FileManagerServiceInterface;
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
    public function create(Request $request, EntityManagerInterface $entityManager, FileManagerServiceInterface $fileManagerService) {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $image = $form->get('image')->getData();
            if ($image) {
                $fileName = $fileManagerService->imagePostUpload($image);
                $post->setImage($fileName);
            }
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
    public function update(int $id, Request $request, EntityManagerInterface $entityManager, FileManagerServiceInterface  $fileManagerService) {
        $post = $entityManager->getRepository(Post::class)->find($id);
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $image = $form->get('image')->getData();
                $imageOld = $post->getImage();
                if ($image) {
                    if ($imageOld) {
                        $fileManagerService->imagePostRemove($imageOld);
                    }
                    $fileName = $fileManagerService->imagePostUpload($image);
                    $post->setImage($fileName);
                }
                $post->setUpdateAtValue();
                $this->addFlash('success', 'Пост обновлён');
            }
            if ($form->get('delete')->isClicked()) {
                $image = $post->getImage();
                if ($image) {
                    $fileManagerService->imagePostRemove($image);
                }
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