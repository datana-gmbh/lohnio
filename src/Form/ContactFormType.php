<?php

declare(strict_types=1);

namespace App\Form;

use App\Domain\Enum\Anrede;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ContactFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('anrede', EnumType::class, [
                'required' => false,
                'label' => 'Anrede',
                'class' => Anrede::class,
                'placeholder' => 'Keine Angabe',
                'expanded' => true,
            ])
            ->add('vorname', TextType::class, [
                'required' => true,
                'label' => 'Vorname',
                'attr' => [
                    'placeholder' => 'Vorname',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte geben Sie einen Vornamen ein.',
                    ]),
                ],
            ])
            ->add('nachname', TextType::class, [
                'required' => true,
                'label' => 'Nachname',
                'attr' => [
                    'placeholder' => 'Nachname',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte geben Sie einen Nachnamen ein.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'label' => 'E-Mail',
                'attr' => [
                    'placeholder' => 'E-Mail',
                ],
                'constraints' => [
                    new NotBlank(
                        message: 'Bitte geben Sie eine E-Mailadresse ein.',
                    ),
                    new Email(
                        mode: Email::VALIDATION_MODE_STRICT,
                        message: 'Die E-Mailadresse ist nicht gültig.',
                    ),
                ],
            ])
            ->add('telefon', TextType::class, [
                'required' => false,
                'label' => 'Telefonnummer',
                'attr' => [
                    'placeholder' => 'Telefonnummer',
                ],
            ])

            // This is used as a honeypot. If filled out request will not proceed.
            ->add('faxnummer', TextType::class, [
                'required' => false,
                'label' => 'Faxnummer',
                'attr' => [
                    'placeholder' => 'Faxnummer',
                ],
            ])

            ->add('nachricht', TextareaType::class, [
                'required' => true,
                'label' => 'Nachricht',
                'attr' => [
                    'placeholder' => 'Nachricht',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Bitte teilen Sie uns mit worum in Ihrer Kontaktanfrage geht.',
                    ]),
                ],
            ])
            ->add('datenschutz', CheckboxType::class, [
                'required' => true,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Bitte stimmen Sie unseren Datenschutzbestimmungen zu.',
                    ]),
                ],
            ]);
    }
}
