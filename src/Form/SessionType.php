<?php

namespace App\Form;

use App\Entity\Session;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SessionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la session',
                'attr' => ['placeholder' => 'Entrez le nom de la session']
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Date de la session',
                'attr' => ['class' => 'date-picker']
            ])
            ->add('seatCount', NumberType::class, [
                'label' => 'Nombre de places',
                'attr' => ['placeholder' => 'Nombre de places maximum']
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Session::class,
        ]);
    }
}
