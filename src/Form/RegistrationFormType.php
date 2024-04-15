<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('username', null, [
                'label' => 'PSEUDO',
                'attr' => [
                    'placeholder' => 'Votre pseudo',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Merci d'entrer un nom d'utilisateur",
                    ]),
                    new Length([
                        'min' => 3,
                        'minMessage' => "Votre nom d'utilisateur doit comporter au moins {{ limit }} caractères",
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'EMAIL',
                'attr' => [
                    'placeholder' => 'Votre adresse eMail',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un email',
                    ]),
                    new Email([
                        'message' => "S'il vous plaît, mettez une adresse email valide",
                    ]),
                ],
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => [
                    'label' => 'MOT DE PASSE', 
                    'attr' => [
                        'placeholder' => 'Votre mot de passe',
                        'class' => 'password-field'
                    ]
                ],
                'second_options' => [
                    'label' => 'CONFIRMER MOT DE PASSE',
                    'attr' => [
                        'placeholder' => 'Répétez votre nouveau mot de passe',
                        'class' => 'password-field' 
                    ]
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                        'max' => 4096,
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
