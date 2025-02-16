<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\Offer;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // ->add('application_date', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('status')
            ->add('cv_path', FileType::class, [
                'label' => 'CV (PDF file)',
                'mapped' => false,
                'required' => true,
                'data_class' => null,
                'constraints' => [
                    new NotBlank(['message' => 'You must upload a CV file.']),
                ],
            ])
            ->add('cover_letter')
            // ->add('offer', EntityType::class, [
                // 'class' => Offer::class,
                // 'choice_label' => 'id',
            // ])
            // ->add('student', EntityType::class, [
            //     'class' => Student::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
