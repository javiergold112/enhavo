<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 04.07.18
 * Time: 11:29
 */

namespace Enhavo\Bundle\NavigationBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TargetType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => 'target',
            'choices' => [
                '_self' => 'self',
                '_blank' => 'blank',
            ]
        ]);
    }

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function getName()
    {
        return 'enhavo_navigation_target';
    }
}