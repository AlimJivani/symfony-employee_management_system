<?php

namespace App\Form;

use App\Entity\EmpDetails;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class EmployeeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('jobTitle', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter jobtitle',
                    ]),
                ]
            ])
            ->add('jobDes', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter Job description',
                    ]),
                ]
            ])
            ->add('userImg', FileType::class, [

                'mapped' => false,

                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,

                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/pjpeg',
                            'image/png',
                            'image/x-png',
                            // 'image/gif'
                        ],
                        'mimeTypesMessage' => 'The file must be of JPEG or PNG format',
                    ]),
                    new NotBlank([
                        'message' => 'Select profile picture',
                    ]),
                ],
            ])
            ->add('linkdinLink', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter linkdinlink',
                    ]),
                ]
            ])
            ->add('jobExp', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter job experience',
                    ]),
                ]
            ])
            ->add('companyName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Enter company name',
                    ]),
                ]
            ])
            // ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EmpDetails::class,
        ]);
    }
}
