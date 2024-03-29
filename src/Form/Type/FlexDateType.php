<?php

namespace Dontdrinkandroot\BridgeBundle\Form\Type;

use Dontdrinkandroot\Common\FlexDate;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlexDateType extends AbstractType
{
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'year',
                IntegerType::class,
                [
                    'label' => false,
                    'required' => false,
                    'attr' => ['class' => 'year', 'placeholder' => 'year', 'min' => 0],
                ]
            )
            ->add(
                'month',
                ChoiceType::class,
                [
                    'label' => false,
                    'required' => false,
                    'choices' => $this->getMonthChoices(),
                    'placeholder' => 'month',
                    'attr' => ['class' => 'month'],
                    'choice_translation_domain' => false
                ]
            )
            ->add(
                'day',
                ChoiceType::class,
                [
                    'label' => false,
                    'required' => false,
                    'choices' => $this->getDayChoices(),
                    'placeholder' => 'day',
                    'attr' => ['class' => 'day'],
                    'choice_translation_domain' => false
                ]
            );
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefault('translation_domain', 'FlexDate');
        $resolver->setDefault('data_class', FlexDate::class);
        $resolver->setDefault('attr', ['class' => 'flexdate']);
    }

    /** @return array<int,int> */
    private function getMonthChoices(): array
    {
        $choices = [];
        for ($i = 1; $i <= 12; $i++) {
            $choices[$i] = $i;
        }

        return $choices;
    }

    /** @return array<int,int> */
    private function getDayChoices(): array
    {
        $choices = [];
        for ($i = 1; $i <= 31; $i++) {
            $choices[$i] = $i;
        }

        return $choices;
    }
}
