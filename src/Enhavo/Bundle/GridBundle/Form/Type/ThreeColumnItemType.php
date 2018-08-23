<?php

namespace Enhavo\Bundle\GridBundle\Form\Type;

use Enhavo\Bundle\GridBundle\Model\Column\ThreeColumnItem;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ThreeColumnItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('columnOne', ColumnType::class, [
            'label' => 'column.label.column_one',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);

        $builder->add('columnTwo', ColumnType::class, [
            'label' => 'column.label.column_two',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);

        $builder->add('columnThree', ColumnType::class, [
            'label' => 'column.label.column_three',
            'translation_domain' => 'EnhavoGridBundle',
            'item_groups' => ['content']
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => ThreeColumnItem::class
        ));
    }

    public function getName()
    {
        return 'enhavo_grid_three_column_item';
    }
}
