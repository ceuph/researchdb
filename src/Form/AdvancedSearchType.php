<?php

namespace App\Form;

use App\Entity\DocumentProperty;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvancedSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Natural Products' => DocumentProperty::PROPERTY_NATPROD,
                    'School Year' => DocumentProperty::PROPERTY_SCHOOL_YEAR,
                    'International' => DocumentProperty::PROPERTY_INTERNATIONAL,
                    'Publication' => DocumentProperty::PROPERTY_PUBLICATION,
                    'Awards' => DocumentProperty::PROPERTY_AWARD,
                    'Presentation' => DocumentProperty::PROPERTY_PRESENTATION
                ]
            ])
            ->add('parameter', TextType::class, [
                'required' => false
            ])
            ->add('search', TextType::class, [
                'required' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
