<?php

namespace Enhavo\Bundle\CommentBundle\Form\Type;

use Enhavo\Bundle\CommentBundle\Entity\CommentBlock;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CommentBlockType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // TODO: Insert form fields
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CommentBlock::class
        ));
    }
}
