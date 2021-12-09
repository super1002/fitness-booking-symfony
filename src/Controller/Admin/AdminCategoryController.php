<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoryController extends AdminBaseController
{
    /**
     * @Route("/admin/category", name="admin_category")
     */
    public function index(EntityManagerInterface $entityManager) {
        $categories = $entityManager->getRepository(Category::class)->findAll();
        $forRender = parent::renderDefault();
        $forRender['title'] = 'Категории';
        $forRender['categories'] = $categories;
        return $this->render('admin/category/index.html.twig',  $forRender);
    }

    /**
     * @Route("/admin/category/create", name="admin_category_create")
     */
    public function create(Request $request, EntityManagerInterface $entityManager) {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setCreateAtValue();
            $category->setUpdateAtValue();
            $category->setIsPublished();
            $entityManager->persist($category);
            $entityManager->flush();
            $this->addFlash('success', 'Категория добавлена успешно');
            return $this->redirectToRoute('admin_category');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Создание категории';
        $forRender['form'] = $form->createView();
        return $this->render('admin/category/form.html.twig',  $forRender);
    }

    /**
     * @Route("/admin/category/update/{id}", name="admin_category_update")
     */
    public function update(int $id, Request $request, EntityManagerInterface $entityManager) {
        $category = $entityManager->getRepository(Category::class)->find($id);
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('save')->isClicked()) {
                $category->setUpdateAtValue();
                $this->addFlash('success', 'Категория обновлена');
            }
            if ($form->get('delete')->isClicked()) {
                $entityManager->remove($category);
                $this->addFlash('success', 'Категория удалена');
            }
            $entityManager->flush();
            return $this->redirectToRoute('admin_category');
        }

        $forRender = parent::renderDefault();
        $forRender['title'] = 'Редактирование категории';
        $forRender['form'] = $form->createView();
        return $this->render('admin/category/form.html.twig',  $forRender);
    }
}