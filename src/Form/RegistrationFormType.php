<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\IntegerType; 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('email')
        ->add('role', ChoiceType::class, [
            'mapped' => false,
            'choices' => [
                'Student' => 'ROLE_STUDENT',
                'Teacher' => 'ROLE_TEACHER',
                'HRM Club' => 'ROLE_HRM_CLUB',
                'HRM Stage' => 'ROLE_HRM_STAGE',
            ],
            'required' => true,
            'label' => 'Role',
            'attr' => ['class' => 'form-control'],
        ])
        ->add('first_name', TextType::class, [
            'required' => false,
        ])
        ->add('last_name', TextType::class, [
            'required' => false,
        ])
        ->add('date_of_birth', DateType::class, [
            'mapped' => false,
            'required' => false,
            'widget' => 'single_text',
            'constraints' => [
                new NotBlank(['groups' => ['Student']]),
            ],
        ])
        ->add('year_of_study', IntegerType::class, [ 
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['Student']]),
            ],
        ])
        ->add('courses_enrolled', TextType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['Student']]),
            ],
        ])
        ->add('certifications', TextType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['Student']]),
            ],
        ])
        ->add('diplomas', TextType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['Student']]),
            ],
        ])
        ->add('agreeTerms', CheckboxType::class, [
            'mapped' => false,
            'constraints' => [
                new IsTrue([
                    'message' => 'You should agree to our terms.',
                ]),
            ],
        ])
        ->add('bio', TextareaType::class, [
            'required' => false,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('phone_number', TelType::class, [
            'required' => false,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('adress', TextType::class, [
            'required' => false,
            'attr' => ['class' => 'form-control'],
        ])
        ->add('specialization', TextType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['Teacher']]),
            ],
        ])
        ->add('years_of_experience', IntegerType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['Teacher']]),
            ],
        ])
        ->add('club', TextType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['HRMClub']]),
            ],
        ])
        ->add('company', TextType::class, [
            'mapped' => false,
            'required' => false,
            'constraints' => [
                new NotBlank(['groups' => ['HRMStage']]),
            ],
        ])
        ->add('plainPassword', PasswordType::class, [
            'mapped' => false,
            'attr' => [
                'autocomplete' => 'new-password',
                'class' => 'form-control'
            ],
            'label' => 'Password'
        ])
            ->add('avatar', FileType::class, [
                'label' => 'Profile Picture',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/png',
                        ],
                        'mimeTypesMessage' => 'Please upload a valid JPEG or PNG image',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
