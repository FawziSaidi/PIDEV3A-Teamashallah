<?php

namespace App\Form;

use App\Entity\HRM;
use App\Entity\Offer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('duration')
            // ->add('publication_date', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('expiration_date', null, [
                'widget' => 'single_text',
            ])
            // ->add('type')
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Internship' => 'Internship',
                    'Job' => 'Job',
                ],
                'expanded' => true,
                'multiple' => false,
                'label' => 'Type :',
            ])
            ->add('desired_skills')
            ->add('hrm', EntityType::class, [
                'class' => HRM::class,
                'choice_label' => 'id',
            ])
            ->add('Save', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
