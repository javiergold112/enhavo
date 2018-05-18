<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.04.18
 * Time: 17:55
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\DynamicFormType;
use Enhavo\Bundle\AppBundle\Form\Type\DynamicItemType;
use Enhavo\Bundle\NavigationBundle\Entity\Node;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NodeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('order', HiddenType::class, []);
        $builder->add('label', TextType::class, []);

        if($options['children']) {
            $builder->add('children', DynamicFormType::class, [
                'label' => 'form.label.node',
                'entry_type' => NodeType::class,
                'entry_options' => [
                    'item_resolver' => 'enhavo_navigation.resolver.item_resolver',
                    'data_class' => Node::class
                ],
                'translation_domain' => 'EnhavoNavigationBundle',
                'item_resolver' => 'enhavo_navigation.resolver.item_resolver',
                'item_route' => 'enhavo_navigation_navigation_form'
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Node::class,
            'label' => 'Node',
            'children' => false
        ]);
    }

    public function getName()
    {
        return 'enhavo_navigation_node';
    }

    public function getParent()
    {
        return DynamicItemType::class;
    }
}