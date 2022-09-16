<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Country;
use App\Entity\State;
use App\Entity\City;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use App\Repository\StateRepository;
use App\Repository\CityRepository;
use Symfony\Component\Form\CallbackTransformer;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstName')
            ->add('email')
            ->add('lastName')
            ->add('gender', ChoiceType::class, [
                'choices'  => [
                    'Male' => 'Male',
                    'Female' => 'Female',
                ]
            ])
            ->add('password', RepeatedType::class, [
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),

                    new Regex([
                        'pattern' => "/^\S*(?=\S{8,})(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/",
                        'message' => " Password must be at least 6 characters: 1 uppercase, 1 lowercase, numbers, or symbols."
                    ])
                ],
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Password'],
                'second_options' => ['label' => 'Confirm Password'],
            ])
            ->add('dateOfBirth')
            ->add('mobileNumber')
            ->add('country', EntityType::class, [
                'class' => Country::class,
                'label' => 'Country',
                'required' => true,
                'choice_label' => function (Country $country) {
                    return $country->getName();
                },
                'invalid_message' => 'You must select a Country',
                'placeholder' => 'Select Country',
            ])
            ->add('state', ChoiceType::class, [
                'choices' => [],
                'placeholder' => 'Select State',
            ])
            ->add('city', ChoiceType::class, [
                'choices' => [],
                'placeholder' => 'Select City',
            ])
            ->add('address')
            ->add('pincode')
            ->add('agreeTerms', CheckboxType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Agree Terms',
                    ]),
                ]
            ])
            ->add('save', SubmitType::class);

        //**************** Start State Form
        $addStateForm = function (FormInterface $form, $country_id) {

            // echo "fdv" ; exit;
            $form->add('state', EntityType::class, [
                'label' => 'state',
                'placeholder' => 'Select state',
                'required' => true,
                'class' => State::class,
                'query_builder' => function (StateRepository $repository) use ($country_id) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.country = :id')
                        ->setParameter('id', $country_id)
                        ->orderBy('c.name', 'ASC');
                    // echo "<pre>"; print_r(($sql->getQuery()->getArrayResult())); exit;
                },
                'choice_label' => 'name',
                'choice_value' => 'id',
                'constraints' => [
                    new NotBlank([
                        'message' => 'State is required',
                    ]),
                ],
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($addStateForm) {
                $country = $event->getData()->getCountry();
                $country_id = $country ? $country->getId() : null;
                $addStateForm($event->getForm(), $country_id);
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($addStateForm) {
                $data = $event->getData();

                $country_id = array_key_exists('country', $data) ? $data['country'] : null;

                $addStateForm($event->getForm(), $country_id);
            }
        );
        //**************** End State Form



        //**************** Start City Form
        $addCityForm = function (FormInterface $form, $State_id) {

            $form->add('city', EntityType::class, [
                'label' => 'city',
                'placeholder' => 'Select city',
                'required' => true,
                'class' => City::class,
                'query_builder' => function (CityRepository $repository) use ($State_id) {
                    return $repository->createQueryBuilder('c')
                        ->where('c.state = :id')
                        ->setParameter('id', $State_id)
                        ->orderBy('c.name', 'ASC');
                    // echo "<pre>"; print_r(($sql->getQuery()->getArrayResult())); exit;
                },
                'choice_label' => 'name',
                'choice_value' => 'name',
                'constraints' => [
                    new NotBlank([
                        'message' => 'city is required',
                    ]),
                ],
            ]);
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($addCityForm) {
                $state = $event->getData()->getState();
                $state_id = $state ? $state->getId() : null;
                $addCityForm($event->getForm(), $state_id);
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) use ($addCityForm) {
                $data = $event->getData();

                $state_id = array_key_exists('state', $data) ? $data['state'] : null;

                $addCityForm($event->getForm(), $state_id);
            }
        );
        //**************** End City Form
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
