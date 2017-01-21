<?php
/**
 * ChartBlock.php
 *
 * @since 21/01/17
 * @author gseidel
 */

namespace Enhavo\Bundle\AppBundle\Block\Block;

use Enhavo\Bundle\AppBundle\Block\BlockInterface;
use Enhavo\Bundle\AppBundle\Chart\ChartProviderInterface;
use Enhavo\Bundle\AppBundle\Type\AbstractType;

class ChartBlock extends AbstractType implements BlockInterface
{
    public function render($parameters)
    {
        $translationDomain = $this->getOption('translationDomain', $parameters, null);
        $provider = $this->getRequiredOption('provider', $parameters);
        $provider = $this->getChartProvider($provider);

        return $this->renderTemplate('EnhavoAppBundle:Block:chart.html.twig', [
            'app' => $this->getOption('app', $parameters, 'app/Block/Chart'),
            'data' => $provider->getData($parameters),
            'options' => $provider->getOptions($parameters),
            'type' => $provider->getChartType($parameters),
            'chartApp' => $provider->getApp($parameters)
        ]);
    }

    /**
     * @param $provider
     * @return ChartProviderInterface
     * @throws \Enhavo\Bundle\AppBundle\Exception\TypeNotFoundException
     */
    protected function getChartProvider($provider)
    {
        return $this->container->get('enhavo_app.chart_provider_collector')->getType($provider);
    }

    public function getType()
    {
        return 'chart';
    }
}