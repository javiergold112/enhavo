<?php
/**
 * UserType.php
 *
 * @since 04/08/14
 * @author Gerhard Seidel <gseidel.message@googlemail.com>
 */

namespace Enhavo\Bundle\UserBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Form\Type\BooleanType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class UserType extends AbstractType
{
    /**
     * @var string
     */
    private $dataClass;

    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    public function __construct($dataClass, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->dataClass = $dataClass;
        $this->authorizationChecker = $authorizationChecker;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email', TextType::class, array(
            'label' => 'user.form.label.email',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('plainPassword', RepeatedType::class, array(
            'type' => 'password',
            'options' => array('translation_domain' => 'FOSUserBundle'),
            'first_options' => array('label' => 'form.password'),
            'second_options' => array('label' => 'form.password_confirmation'),
            'invalid_message' => 'fos_user.password.mismatch',
        ));

        $builder->add('firstName', TextType::class, array(
            'label' => 'user.form.label.firstName',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('lastName', TextType::class, array(
            'label' => 'user.form.label.lastName',
            'translation_domain' => 'EnhavoUserBundle'
        ));

        $builder->add('admin', BooleanType::class, array(
            'label' => 'user.form.label.admin',
            'translation_domain' => 'EnhavoUserBundle',
            'help' => 'user.form.help.admin'
        ));

        if($this->authorizationChecker->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->add('superAdmin', BooleanType::class, array(
                'label' => 'user.form.label.super_admin',
                'translation_domain' => 'EnhavoUserBundle'
            ));
        }

        $builder->add('groups', EntityType::class, array(
            'class' => 'EnhavoUserBundle:Group',
            'property' => 'name',
            'multiple' => true,
            'expanded' => true,
            'list' => true,
            'label' => 'user.form.label.groups',
            'translation_domain' => 'EnhavoUserBundle'
        ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass
        ]);
    }

    public function getName()
    {
        return 'enhavo_user_user';
    }
}