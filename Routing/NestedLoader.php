<?php

namespace Dontdrinkandroot\BridgeBundle\Routing;

use InvalidArgumentException;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;
use function is_array;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class NestedLoader extends YamlFileLoader
{
    /**
     * {@inheritdoc}
     */
    public function supports($resource, $type = null)
    {
        return 'ddr_nested' === $type;
    }

    /**
     * {@inheritdoc}
     */
    public function load($file, $type = null)
    {
        $path = $this->locator->locate($file);

        if (!stream_is_local($path)) {
            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $path));
        }

        if (!file_exists($path)) {
            throw new InvalidArgumentException(sprintf('File "%s" not found.', $path));
        }

        try {
            $parsedConfig = (new YamlParser())->parseFile($path, Yaml::PARSE_CONSTANT);
        } catch (ParseException $e) {
            throw new InvalidArgumentException(
                sprintf('The file "%s" does not contain valid YAML: ', $path) . $e->getMessage(), 0, $e
            );
        }

        $collection = new RouteCollection();
        $collection->addResource(new FileResource($path));

        if (null === $parsedConfig) {
            return $collection;
        }

        if (!is_array($parsedConfig)) {
            throw new InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $path));
        }

        foreach ($parsedConfig as $path => $config) {
            $this->processConfig($collection, '/' . $path, $config, $file);
        }

        return $collection;
    }

    private function processConfig(RouteCollection $collection, string $path, array $config, $file)
    {
        if (array_key_exists('name', $config)) {
            $transformedConfig = $this->transformConfig($config, $path);
            $this->validate($transformedConfig, $config['name'], $path);

            if (isset($config['resource'])) {
                $this->parseImport($collection, $transformedConfig, $path, $file);
            } else {
                $this->parseRoute($collection, $config['name'], $transformedConfig, $path);
            }
        }

        if (array_key_exists('children', $config)) {
            $childCollection = new RouteCollection();
            foreach ($config['children'] as $childPath => $childConfig) {
                $this->processConfig($childCollection, $path . '/' . $childPath, $childConfig, $file);
            }
            if (array_key_exists('name_prefix', $config)) {
                $childCollection->addNamePrefix($config['name_prefix']);
            }
            $collection->addCollection($childCollection);
        }
    }

    private function transformConfig(array $config, string $path): array
    {
        unset($config['name']);
        if (array_key_exists('resource', $config)) {
            $config['prefix'] = $path;
        } else {
            $config['path'] = $path;
        }
        if (array_key_exists('children', $config)) {
            unset($config['children']);
        }
        return $config;
    }
}
