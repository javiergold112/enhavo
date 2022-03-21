<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 29/05/16
 */

namespace Enhavo\Bundle\FormBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeType extends AbstractType
{
    /**
     * @var array|null
     */
    private $defaultDateTimePickerConfig;

    /**
     * @param string $defaultDateTimePickerConfig
     */
    public function __construct($defaultDateTimePickerConfig)
    {
        $this->defaultDateTimePickerConfig = $defaultDateTimePickerConfig;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        if (array_key_exists('data-date-time-picker', $options['attr'])) {
            $view->vars['attr']['data-date-time-picker'] = $options['config'];
        }
        if (!$options['allow_typing']) {
            $view->vars['attr']['readonly'] = 'readonly';
        }
        $view->vars['allowClear'] = $options['allow_clear'];
    }

    public function getBlockPrefix()
    {
        return 'enhavo_datetime';
    }

    public function getParent()
    {
        return DateType::class;
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options.
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'widget' => 'single_text',
            'format' => 'dd.MM.yyyy HH:mm',
            'html5' => false,
            'config' => $this->defaultDateTimePickerConfig,
            'allow_typing' => false,
            'allow_clear' => false,
            'attr' => [
                'data-date-time-picker' => null,
                'autocomplete' => 'off'
            ]
        ));
    }
}
