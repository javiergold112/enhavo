<?php
/**
 * Created by PhpStorm.
 * User: gseidel
 * Date: 12.08.18
 * Time: 19:50
 */

namespace Enhavo\Bundle\RoutingBundle\AutoGenerator\Generator;

use Enhavo\Bundle\AppBundle\Repository\EntityRepositoryInterface;
use Enhavo\Bundle\RoutingBundle\AutoGenerator\AbstractGenerator;
use Enhavo\Bundle\RoutingBundle\Model\RouteInterface;
use Enhavo\Bundle\RoutingBundle\Slugifier\Slugifier;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrefixGenerator extends AbstractGenerator
{
    /**
     * @var EntityRepositoryInterface
     */
    private $routeRepository;

    public function __construct($routeRepository)
    {
        $this->routeRepository = $routeRepository;
    }

    public function generate($resource, $options = [])
    {
        $properties = $this->getSlugifiedProperties($resource, $options['properties']);
        if(count($properties)) {
            /** @var RouteInterface $route */
            $route = $this->getProperty($resource, $options['route_property']);
            if(!$options['overwrite'] && $route->getStaticPrefix()) {
                return;
            }
            $route->setStaticPrefix($this->createPrefix($properties, $options));
        }
    }

    private function getSlugifiedProperties($resource, $properties)
    {
        $result = [];

        if(!is_array($properties)){
            $properties = [$properties];
        }

        foreach ($properties as $property){
            $slug = $this->getSlug($this->getProperty($resource, $property));
            if($slug){
                $result[$property] = $slug;
            }
        }

        return $result;
    }

    private function getSlug($string)
    {
        return Slugifier::slugify($string);
    }

    private function createPrefix(array $properties, array $options)
    {
        return $options['unique'] ? $this->getUniqueUrl($properties, $options) : $this->format($properties, $options);
    }

    private function format(array $properties, array $options)
    {
        if ($options['format']) {
            $string = $options['format'];
            foreach ($properties as $key => $value){
                $string = str_replace(sprintf('{%s}', $key), $value, $string);
            }
            return $string;
        } else {
            return sprintf('/%s', join('-', $properties));
        }
    }

    private function existsPrefix($prefix): bool
    {
        $results = $this->routeRepository->findBy([
            'staticPrefix' => $prefix
        ]);

        return count($results);
    }

    private function getUniqueUrl(array $properties, array $options): string
    {
        if($options['unique_property']) {
            $this->checkUniqueProperty($properties, $options);
            $isFirstTry = true;
            while($this->existsPrefix($this->format($properties, $options))) {
                $properties = $this->increaseProperties($properties, $options, $isFirstTry);
                $isFirstTry = false;
            }
            return $this->format($properties, $options);
        }

        $string = $this->format($properties, $options);
        $isFirstTry = true;
        while($this->existsPrefix($string)) {
            $string = $this->increaseString($string, $isFirstTry);
            $isFirstTry = false;
        }
        return $string;
    }

    private function checkUniqueProperty($properties, $options)
    {
        if(!isset($properties[$options['unique_property']])) {
            throw new \InvalidArgumentException(sprintf(
                'The unique_property option "%s" don\'t exists in option properties. Available properties are "%s"',
                $options['unique_property'],
                is_array($options['properties']) ? join(',', $options['properties']) : $options['properties']
            ));
        }
    }

    private function increaseProperties($properties, $options, $isFirstTry)
    {
        $uniqueProperty = $this->getUniqueProperty($properties, $options);
        $string = $properties[$uniqueProperty];

        $added = false;

        if(!$isFirstTry) {
            $isMatch = preg_match('/^(.*)-([0-9]+)$/', $string, $matches);
            if($isMatch && isset($matches[1]) && isset($matches[2])) {
                $string = sprintf('%s-%u', $matches[1], intval($matches[2]) + 1);
                $added = true;
            }
        }

        if(!$added) {
            $string = sprintf('%s-1', $string);
        }

        $properties[$this->getUniqueProperty($properties, $options)] = $string;

        return $properties;
    }

    private function increaseString($string, $isFirstTry)
    {
        if(!$isFirstTry) {
            $isMatch = preg_match('/^(.*)-([0-9]+)$/', $string, $matches);
            if($isMatch && isset($matches[1]) && isset($matches[2])) {
                $string = sprintf('%s-%u', $matches[1], intval($matches[2]) + 1);
                return $string;
            }
        }
        return sprintf('%s-1', $string);
    }

    private function getUniqueProperty($properties, $options)
    {
        if($options['unique_property']){
            return $options['unique_property'];
        }
        return array_key_last($properties);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        $resolver->setDefaults([
            'route_property' => 'route',
            'overwrite' => false,
            'format' => null,
            'unique' => false,
            'unique_property' => null
        ]);
        $resolver->setRequired([
            'properties'
        ]);
    }

    public function getType()
    {
        return 'prefix';
    }
}
