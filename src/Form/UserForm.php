<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'E-mail',
                'attr' => [
                    'class' => 'form-field',
                ],
            ]);
        $builder->add('plainPassword', PasswordType::class, [
            'label' => 'Mot de passe',
            'attr' => [
                'class' => 'form-field',
            ],
        ]);
        $builder->add('roles', ChoiceType::class, [
            'label' => 'Rôle',
            'choices' => [
                'Utilisateur' => 'ROLE_USER',
                'Administrateur' => 'ROLE_ADMIN',
                'Super Admin' => 'ROLE_SUPER_ADMIN',
            ],
            'expanded' => true, // select (si true = checkboxes)
            'multiple' => true, // un utilisateur peut avoir plusieurs rôles
            'attr' => [
                'class' => 'role-field',
            ],
            'choice_attr' => function ($choice, $key, $value) {
                // $key = label (ex: "Utilisateur")
                // $value = valeur (ex: "ROLE_USER")
                switch ($value) {
                    case 'ROLE_USER':
                        return ['class' => 'cyan'];
                    case 'ROLE_ADMIN':
                        return ['class' => 'jaune'];
                    case 'ROLE_SUPER_ADMIN':
                        return ['class' => 'magenta'];
                    default:
                        return [];
                }
            },
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
