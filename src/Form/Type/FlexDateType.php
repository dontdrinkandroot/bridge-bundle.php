<?php

namespace Dontdrinkandroot\BridgeBundle\Form\Type;

use Dontdrinkandroot\Common\FlexDate;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @extends AbstractType<FlexDate>
 */
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
                    'constraints' => [
                        new Assert\Range(min: 0),
                        new Assert\Length(min: 4, max: 4),
                        new Assert\Expression(
                            expression: 'value == null && this.getParent().getViewData().getMonth() != null',
                            message: 'ddr.flexdate.yearnotset',
                            negate: false
                        ),
                    ]
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
                    'choice_translation_domain' => false,
                    'constraints' => [
                        new Assert\Range(min: 1, max: 12),
                        new Assert\Expression(
                            expression: 'value == null && this.getParent().getViewData().getDay() != null',
                            message: 'ddr.flexdate.monthnotset',
                            negate: false
                        ),
                    ]
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
                    'choice_translation_domain' => false,
                    'constraints' => [
                        new Assert\Range(min: 1, max: 31),
                        new Assert\Expression(
                            expression: 'value != null && !this.getParent().getViewData().isValidDate()',
                            message: 'ddr.flexdate.dateinvalid',
                            negate: false
                        ),
                    ]
                ]
            );
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
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
