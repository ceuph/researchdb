<?php

namespace App\Form;

use App\Entity\Document;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use App\Form\DocumentAttachmentType;
use Symfony\Component\Validator\Constraints\File;

class DocumentType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('subject')
            ->add('body')
            ->add('yearCreated')
//            ->add('documentAttachments', CollectionType::class, [
//                'entry_type' => DocumentAttachmentType::class,
//                'entry_options' => ['label' => false],
//            ])
            ->add('abstract', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => new File([
                    'mimeTypes' => [
                        'application/pdf',
                        'application/x-pdf'
                    ]
                ])
            ])
            ->add('fulltext', FileType::class, [
                'required' => false,
                'mapped' => false,
                'constraints' => new File([
                    'mimeTypes' => [
                        'application/pdf',
                        'application/x-pdf'
                    ]
                ])
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Document::class,
        ]);
    }
}
