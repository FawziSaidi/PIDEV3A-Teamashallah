<?php

namespace App\Form;

use App\Entity\Lesson;
use App\Entity\Course;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;

class LessonType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Lesson Name',
                'required' => true,
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('title', TextType::class, [
                'label' => 'Title',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Title is required']),
                    new Assert\Length(['max' => 255, 'maxMessage' => 'Title cannot be longer than 255 characters'])
                ]
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'required' => true,
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Duration (in minutes)',
                'required' => true,
            ])
            ->add('content', TextType::class)
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'title',
                'label' => 'Course',
                'placeholder' => 'Select a course',
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Please select a course'])
                ],
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Thumbnail (JPG or PNG file)',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Please upload a valid image file (JPG or PNG)'
                    ])
                ]
            ])
            ->add('videoUrl', UrlType::class, [
                'label' => 'Video URL',
                'required' => false,
                'constraints' => [
                    new Assert\Url(['message' => 'Please enter a valid URL'])
                ]
            ])
            ->add('pdf', FileType::class, [
                'label' => 'PDF File',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '50M',
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Please upload a valid PDF file'
                    ])
                ]
            ])
            ->add('ppt', FileType::class, [
                'label' => 'PPT File',
                'required' => false,
                'mapped' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '50M',
                        'mimeTypes' => [
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation'
                        ],
                        'mimeTypesMessage' => 'Please upload a valid PPT file'
                    ])
                ]
            ])
            ->add('hasQuizzes', CheckboxType::class, [
                'label' => 'Has Quizzes',
                'required' => false,
            ])
            ->add('quizUrl', UrlType::class, [
                'label' => 'Quiz URL',
                'required' => false,
                'constraints' => [
                    new Assert\Url(['message' => 'Please enter a valid URL']),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lesson::class,
        ]);
    }
}
