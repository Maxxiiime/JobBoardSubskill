<?php

namespace App\Form;

use App\Entity\Annonce;
use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Choice;

class AnnoncesFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'label' => false,

            ])
            ->add('createdAt', DateType::class, [
                'required' => false,
                'label' => false,
                'widget' => 'single_text',
            ])
            ->add('ville', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => [
                    'Paris' => 'Paris',
                    'Marseille' => 'Marseille',
                    'Lyon' => 'Lyon',
                    'Bordeaux' => 'Bordeaux'

                ]
            ])
            ->add('metier', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => [
                'Boulanger' => 'Boulanger',
                'Developpeur' => 'Developpeur',
                'Cuisinier' => 'Cuisinier',
                'Chef de projet' => 'Chef de projet',
                'Chauffeur' => 'Chauffeur',
                'Macon' => 'Macon',
                'Charpentier' => 'Charpentier',
                'Commercial' => 'Commercial'
               ]

            ])
            ->add('contrat', ChoiceType::class, [
                'required' => false,
                'label' => false,
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Alternance' => 'Alternance',
                    'Stage' => 'Stage'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            //'data_class' => Annonce::class,
            'method' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix()
    {
        return '';
    }
}
