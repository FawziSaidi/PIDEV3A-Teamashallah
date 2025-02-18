<?php

namespace App\Form;

use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class CourseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $universities = [
            'Harvard' => 'Harvard',
            'MIT' => 'MIT',
            'Stanford' => 'Stanford',
            'Oxford' => 'Oxford',
            'Cambridge' => 'Cambridge',
            'Yale' => 'Yale',
            'Princeton' => 'Princeton',
            'Columbia' => 'Columbia',
            'Caltech' => 'Caltech',
            'UCLA' => 'UCLA',
            'University of Chicago' => 'University of Chicago',
            'University of Pennsylvania' => 'University of Pennsylvania',
            'Johns Hopkins University' => 'Johns Hopkins University',
            'University of California, Berkeley' => 'University of California, Berkeley',
            'University of Michigan' => 'University of Michigan',
            'University of Washington' => 'University of Washington',
            'University of Toronto' => 'University of Toronto',
            'University of Tokyo' => 'University of Tokyo',
            'National University of Singapore' => 'National University of Singapore',
            'Other' => 'Other',
        ];

        $builder
            ->add('title', TextType::class, [
                'label' => 'Title',
                'required' => true,
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('about', TextType::class, [
                'label' => 'About',
                'required' => true,
            ])
            ->add('teacher', TextType::class, [
                'label' => 'Teacher',
                'required' => true,
            ])
            ->add('category', ChoiceType::class, [
                'choices' => [
                    'Science' => 'science',
                    'Arts' => 'arts',
                    'Technology' => 'technology',
                    'Business' => 'business',
                    'Health' => 'health',
                    'Mathematics' => 'mathematics',
                    'Engineering' => 'engineering',
                    'Humanities' => 'humanities',
                    'Social Sciences' => 'social_sciences',
                    'Education' => 'education',
                    'Law' => 'law',
                    'Other' => 'other',
                ],
                'label' => 'Category',
                'required' => true,
            ])
            ->add('personalizedCategory', TextType::class, [
                'label' => 'Personalized Category',
                'required' => false,
            ])
            ->add('university', ChoiceType::class, [
                'choices' => $universities,
                'label' => 'University',
                'required' => true,
            ])
            ->add('customUniversity', TextType::class, [
                'label' => 'Custom University',
                'required' => false,
            ])
            ->add('startDate', DateType::class, [
                'widget' => 'single_text',
                'label' => 'Start Date',
                'required' => true,
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration (in weeks)',
                'required' => true,
            ])
            ->add('isPaid', ChoiceType::class, [
                'choices' => [
                    'Free' => false,
                    'Paid' => true,
                ],
                'label' => 'Is Paid',
                'required' => true,
            ])
            ->add('price', IntegerType::class, [
                'label' => 'Price',
                'required' => false,
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Thumbnail (JPG or PNG file)',
                'mapped' => false,
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
            'is_new' => false,
        ]);
    }
}
