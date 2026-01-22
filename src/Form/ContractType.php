<?php

namespace App\Form;

use App\Application\Contract\CreateContractRequest;
use App\Domain\Payment\PaymentProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContractType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('contractNumber', TextType::class, [
                'label' => 'Contract number'
            ])
            ->add('contractDate', DateType::class, [
                'label' => 'Contract date',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('totalAmount', IntegerType::class, [
                'label' => 'Total amount',
                'help' => 'Enter amount in COP.'
            ])
            ->add('months', IntegerType::class, [
                'label' => 'Months',
                'help' => 'Number of installments (e.g. 12).'
            ])
            ->add('paymentProvider', ChoiceType::class, [
                'label' => 'Payment provider',
                'choices' => [
                    'PayPal' => PaymentProvider::PAYPAL,
                    'PayOnline' => PaymentProvider::PAY_ONLINE,
                ],
                'choice_value' => fn(?PaymentProvider $p) => $p?->value ?? null,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CreateContractRequest::class,
        ]);
    }
}
