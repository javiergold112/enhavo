<?php
/**
 * ViewerFactory.php
 *
 * @since 28/05/15
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Controller;

use Sylius\Bundle\ResourceBundle\Controller\Parameters;
use Symfony\Component\HttpFoundation\Request;

class SimpleRequestConfiguration implements RequestConfigurationInterface
{
    /**
     * @var Request
     */
    protected $request;

    /**
     * @var Parameters
     */
    protected $parameters;

    /**
     * @param Request $request
     * @param Parameters $parameters
     */
    public function __construct(Request $request, Parameters $parameters)
    {
        $this->request = $request;
        $this->parameters = $parameters;
    }

    public function getViewerOptions()
    {
        $attributes = $this->parameters->get('viewer', []);
        if(isset($attributes['type'])) {
            unset($attributes['type']);
        }
        return $attributes;
    }

    public function getViewerType()
    {
        $attributes = $this->parameters->get('viewer', []);
        if(isset($attributes['type'])) {
            return $attributes['type'];
        }
        return null;
    }

    public function getTemplate($default)
    {
        $template = $this->parameters->get('template', $default);

        if (null === $template) {
            throw new \RuntimeException(sprintf('Could not resolve template for'));
        }

        return $template;
    }


}