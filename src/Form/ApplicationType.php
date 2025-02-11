<?php

namespace App\Form;

use App\Entity\Application;
use App\Entity\Offer;
use App\Entity\Student;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ApplicationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('application_date', null, [
                'widget' => 'single_text',
            ])
            ->add('status')
            ->add('cover_letter')
            ->add('cv_path')
            // ->add('offer', EntityType::class, [
                // 'class' => Offer::class,
                // 'choice_label' => 'id',
            // ])
            // ->add('student', EntityType::class, [
            //     'class' => Student::class,
            //     'choice_label' => 'id',
            // ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Application::class,
        ]);
    }
}
