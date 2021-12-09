<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Введите имя'
            ])
            ->add('surname', TextType::class, [
                'label' => 'Введите фамилию'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Введите email'
            ])
            ->add('phone', TelType::class, [
                'label' => 'Введите номер телефона'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' =>  PasswordType::class,
                    'first_options' => [
                        'label' => 'Пароль',
                    ],
                    'second_options' => [
                      'label' =>  'Повторите пароль'
                    ]
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Сохранить пользователя',
                'attr' => [
                    'class' => 'btn-success float-left mr-2'
                ]
            ])
            ->add('delete', SubmitType::class, [
                'label' => 'Удалить пользователя',
                'attr' => [
                    'class' => 'btn-danger'
                ]
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
