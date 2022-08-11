<?php

namespace Dontdrinkandroot\BridgeBundle\Form\Type;

use Dontdrinkandroot\Common\FlexDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Util\StringUtil;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FlexDateType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $name = StringUtil::fqcnToBlockPrefix(static::class) ?: '';
        $builder
            ->add(
                'year',
                IntegerType::class,
                [
                    'label'    => false,
                    'required' => false,
                    'attr'     => ['class' => 'year', 'placeholder' => 'ddr.flexdate.year']
                ]
            )
            ->add(
                'month',
                ChoiceType::class,
                [
                    'label'       => false,
                    'required'    => false,
                    'choices'     => $this->getMonthChoices(),
                    'placeholder' => 'ddr.flexdate.month',
                    'attr'        => ['class' => 'month']
                ]
            )
            ->add(
                'day',
                ChoiceType::class,
                [
                    'label'       => false,
                    'required'    => false,
                    'choices'     => $this->getDayChoices(),
                    'placeholder' => 'ddr.flexdate.day',
                    'attr'        => ['class' => 'day']
                ]
            );
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults(
            [
                'data_class' => FlexDate::class,
                'attr'       => ['class' => 'flexdate']
            ]
        );
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
