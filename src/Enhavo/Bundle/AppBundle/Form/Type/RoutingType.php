<?php
/**
 * RoutingType.php
 *
 * @since 16/05/16
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Form\Type;

use Enhavo\Bundle\AppBundle\Entity\Route;
use Enhavo\Bundle\AppBundle\Route\GeneratorInterface;
use Enhavo\Bundle\AppBundle\Route\Routeable;
use Enhavo\Bundle\AppBundle\Route\Routing;
use Enhavo\Bundle\AppBundle\Route\Slugable;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Routing\RouterInterface;
use Enhavo\Bundle\AppBundle\Route\RouteGuesser;

class RoutingType extends AbstractType
{
    use ContainerAwareTrait;

    /**
     * @var RouterInterface
     */
    protected $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) use ($options) {
            $data = $event->getData();
            $form = $event->getForm();

            if ($options['routing_strategy'] === Routing::STRATEGY_ID) {
                if (!method_exists($data, 'getId')) {
                    throw new \Exception('Routing strategy id used, but data has no getId method');
                }

                if($data->getId() && !empty($options['routing_route'])) {
                    $form->add('link', 'text', array(
                        'mapped' => false,
                        'data' => $this->router->generate($options['routing_route'], array(
                            'id' => $data->getId()
                        ), true),
                        'disabled' => true
                    ));
                }
            }

            if ($options['routing_strategy'] === Routing::STRATEGY_SLUG) {
                if (!$data instanceof Slugable || !method_exists($data, 'getSlug')) {
                    throw new \Exception('Routing strategy slug used, but data has no getSlug method nor is instanceof Slugable');
                }

                if($data->getSlug() && !empty($options['routing_route'])) {
                    $form->add('link', 'text', array(
                        'mapped' => false,
                        'data' => $this->router->generate($options['routing_route'], array(
                            'slug' => $data->getSlug()
                        ), true),
                        'disabled' => true
                    ));
                }

                $form->add('slug', 'enhavo_slug');
            }

            if ($options['routing_strategy'] === Routing::STRATEGY_SLUG_ID) {
                if (!$data instanceof Slugable || !method_exists($data, 'getSlug')) {
                    throw new \Exception('Routing strategy id_slug used, but data has no getSlug method nor is instanceof Slugable');
                }

                if (!method_exists($data, 'getId')) {
                    throw new \Exception('Routing strategy id_slug used, but data has no getId method');
                }

                if($data->getId() && $data->getSlug() && !empty($options['routing_route'])) {
                    $form->add('link', 'text', array(
                        'mapped' => false,
                        'data' => $this->router->generate($options['routing_route'], array(
                            'id' => $data->getId(),
                            'slug' => $data->getSlug()
                        ), true),
                        'disabled' => true
                    ));
                }

                $form->add('slug', 'enhavo_slug', array());
            }

            if ($options['routing_strategy'] === Routing::STRATEGY_ROUTE) {
                if (!$data instanceof Routeable) {
                    throw new \Exception('Routing strategy route used, but data is not instanceof Routeable');
                }

                $form->add('route', 'enhavo_route');

            }

        });

        $builder->addEventListener(FormEvents::SUBMIT, function (FormEvent $event) use ($options) {
            if ($options['routing_strategy'] === Routing::STRATEGY_ROUTE) {
                $data = $event->getData();
                if($data instanceof Routeable) {
                    $route = $data->getRoute();
                    if($route instanceof Route && empty($route->getStaticPrefix())) {
                        /** @var GeneratorInterface $generator */
                        $generator = $this->container->get($options['routing_generator']);
                        $url = $generator->generate($data);
                        if($url !== null) {
                            $route->setStaticPrefix($url);
                        }
                    }
                }
            }
        });
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'routing_strategy' => null,
            'routing_route' => null,
            'routing_generator' => 'enhavo_app.route_guess_generator'
        ));
    }

    public function getName()
    {
        return 'enhavo_routing';
    }
}