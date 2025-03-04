<?php

namespace Dontdrinkandroot\BridgeBundle\Tests\TestApp\Form\Type;

use Dontdrinkandroot\BridgeBundle\Form\Type\FlexDateType;
use Dontdrinkandroot\BridgeBundle\Tests\TestApp\Entity\ExampleEntity;
use Override;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * @extends AbstractType<ExampleEntity>
 */
class ExampleEntityType extends AbstractType
{
    #[Override]
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('flexDate', FlexDateType::class, [
                'label' => 'flexDate',
                'help' => 'A help text',
                'constraints' => [new Valid()]
            ]);
    }

    #[Override]
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefault('data_class', ExampleEntity::class);
    }
}
