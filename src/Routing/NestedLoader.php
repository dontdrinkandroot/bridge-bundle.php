<?php

namespace Dontdrinkandroot\BridgeBundle\Routing;

use InvalidArgumentException;
use Override;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\Routing\Loader\YamlFileLoader;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Parser as YamlParser;
use Symfony\Component\Yaml\Yaml;

use function is_array;

class NestedLoader extends YamlFileLoader
{
    #[Override]
    public function supports($resource, ?string $type = null): bool
    {
        return 'ddr_nested' === $type;
    }

    #[Override]
    public function load(mixed $file, ?string $type = null): RouteCollection
    {
        $filePath = $this->locator->locate($file);

        if (!stream_is_local($filePath)) {
            throw new InvalidArgumentException(sprintf('This is not a local file "%s".', $filePath));
        }

        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(sprintf('File "%s" not found.', $filePath));
        }

        try {
            $parsedConfig = new YamlParser()->parseFile($filePath, Yaml::PARSE_CONSTANT);
        } catch (ParseException $e) {
            throw new InvalidArgumentException(
                sprintf('The file "%s" does not contain valid YAML: ', $filePath) . $e->getMessage(), 0, $e
            );
        }

        $collection = new RouteCollection();
        $collection->addResource(new FileResource($filePath));

        if (null === $parsedConfig) {
            return $collection;
        }

        if (!is_array($parsedConfig)) {
            throw new InvalidArgumentException(sprintf('The file "%s" must contain a YAML array.', $filePath));
        }

        foreach ($parsedConfig as $path => $config) {
            $this->processConfig($collection, '/' . $path, $config, $file);
        }

        return $collection;
    }

    /**
     * @param array<string,mixed> $config
     */
    private function processConfig(RouteCollection $collection, string $path, array $config, mixed $file): void
    {
        if (null !== $name = $config['name'] ?? null) {
            $transformedConfig = $this->transformConfig($config, $path);
            $this->validate($transformedConfig, $name, $path);

            if (isset($config['resource'])) {
                $this->parseImport($collection, $transformedConfig, $path, $file);
            } else {
                $this->parseRoute($collection, $name, $transformedConfig, $path);
            }
        }

        if (null !== $children = $config['children'] ?? null) {
            $childCollection = new RouteCollection();
            foreach ($children as $childPath => $childConfig) {
                $this->processConfig($childCollection, $path . '/' . $childPath, $childConfig, $file);
            }
            if (null !== $namePrefix = $config['name_prefix'] ?? null) {
                $childCollection->addNamePrefix($namePrefix);
            }
            $collection->addCollection($childCollection);
        }
    }

    /**
     * @param array<string,mixed> $config
     * @return array<string,mixed>
     */
    private function transformConfig(array $config, string $path): array
    {
        unset($config['name']);
        if (isset($config['resource'])) {
            $config['prefix'] = $path;
        } else {
            $config['path'] = $path;
        }
        unset($config['children']);

        return $config;
    }
}
