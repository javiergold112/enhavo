<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 23.08.18
 * Time: 21:56
 */

namespace Enhavo\Bundle\BlockBundle\Block;

use Enhavo\Bundle\AppBundle\Type\TypeCollector;

class BlockManager
{
    /**
     * @var Block[]
     */
    private $blocks = [];

    public function __construct(TypeCollector $collector, $configurations)
    {
        foreach($configurations as $name => $options) {
            /** @var AbstractBlockType $configuration */
            $configuration = $collector->getType($options['type']);
            unset($options['type']);
            $block = new Block($configuration, $name, $options);
            $this->blocks[$name] = $block;
        }
    }

    public function getBlocks()
    {
        return $this->blocks;
    }
}
