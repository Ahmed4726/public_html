<?php

namespace Guzzle\Service;

/**
 * Service builder to generate service builders and service clients from
 * configuration settings
 */
class ServiceBuilder implements \ArrayAccess
{
    /**
     * @var array Service builder configuration data
     */
    protected $builderConfig = array();

    /**
     * @var array Instantiated client objects
     */
    protected $clients = array();

    /**
     * Create a new ServiceBuilder using configuration data sourced from an
     * array, .json|.js file, SimpleXMLElement, or .xml file.
     *
     * @param array|string|SimpleXMLElement $data An instantiated
     *      SimpleXMLElement containing configuration data, the full path to an
     *      .xml or .js|.json file, or an associative array of data
     * @param string $extension (optional) When passing a string of data to load
     *      from a file, you can set $extension to specify the file type if the
     *      extension is not the standard extension for the file name (e.g. xml,
     *      js, json)
     *
     * @return ServiceBuilder
     * @throws RuntimeException if a file cannot be openend
     * @throws LogicException when trying to extend a missing client
     */
    public static function factory($data, $extension = null)
    {
        $config = array();
        if (is_string($data)) {
            if (!is_readable($data)) {
                throw new \RuntimeException('Unable to open ' . $data);
            }
            $extension = $extension ?: pathinfo($data, PATHINFO_EXTENSION);
            if ($extension == 'xml') {
                $data = new \SimpleXMLElement($data, null, true);
            } else if ($extension == 'js' || $extension == 'json') {
                $config = json_decode(file_get_contents($data), true);
            } else {
                throw new \RuntimeException('Unknown file type ' . $extension);
            }
        } else if (is_array($data)) {
            $config = $data;
        } else if (!($data instanceof \SimpleXMLElement)) {
            throw new \InvalidArgumentException('Must pass a file name, array, or SimpleXMLElement');
        }

        if ($data instanceof \SimpleXMLElement) {
            foreach ($data->clients->client as $client) {
                $row = array();
                foreach ($client->param as $param) {
                    $row[(string) $param->attributes()->name] = (string) $param->attributes()->value;
                }
                $config[(string) $client->attributes()->name] = array(
                    'class'   => (string) $client->attributes()->class,
                    'extends' => (string) $client->attributes()->extends,
                    'params'  => $row
                );
            }
        }

        // Validate the configuration and handle extensions
        foreach ($config as $name => &$client) {
            $client['params'] = isset($client['params']) ? $client['params'] : array();
            // Check if this client builder extends another client
            if (!empty($client['extends'])) {
                // Make sure that the service it's extending has been defined
                if (!isset($config[$client['extends']])) {
                    throw new \LogicException($name . ' is trying to extend a non-existent service: ' . $client['extends']);
                }
                $client['class'] = empty($client['class'])
                    ? $config[$client['extends']]['class'] : $client['class'];
                $client['params'] = array_merge($config[$client['extends']]['params'], $client['params']);
            }
            $client['class'] = str_replace('.', '\\', $client['class']);
        }

        return new self($config);
    }

    /**
     * Construct a new service builder
     *
     * @param array $serviceBuilderConfig Service configuration settings:
     *      name => Name of the service
     *      class => Builder class used to create clients using dot notation (Guzzle.Service.Aws.S3builder or Guzzle.Service.Builder.DefaultBuilder)
     *      params => array of key value pair configuration settings for the builder
     */
    public function __construct(array $serviceBuilderConfig)
    {
        $this->builderConfig = $serviceBuilderConfig;
    }

    /**
     * Magic method to allow serialization to support caching
     *
     * @return array
     */
    public function __sleep()
    {
        return array('builderConfig');
    }

    /**
     * Get a client using a registered builder
     *
     * @param $name Name of the registered client to retrieve
     * @param bool $throwAway (optional) Set to TRUE to not store the client
     *     for later retrieval from the ServiceBuilder
     *
     * @return ClientInterface
     * @throws InvalidArgumentException when a client cannot be found by name
     */
    public function get($name, $throwAway = false)
    {
        if (!isset($this->builderConfig[$name])) {
            throw new \InvalidArgumentException('No client is registered as ' . $name);
        }

        if (!$throwAway && isset($this->clients[$name])) {
            return $this->clients[$name];
        }

        // Convert references to the actual client
        foreach ($this->builderConfig[$name]['params'] as $k => &$v) {
            if (0 === strpos($v, '{{') && strlen($v) - 2 == strpos($v, '}}')) {
                $v = $this->get(trim(substr($v, 2, -2)));
            }
        }

        $client = call_user_func(
            array($this->builderConfig[$name]['class'], 'factory'),
            $this->builderConfig[$name]['params']
        );

        if (!$throwAway) {
            $this->clients[$name] = $client;
        }

        return $client;
    }

    /**
     * Register a client by name with the service builder
     *
     * @param string $offset Name of the client to register
     * @param ClientInterface $value Client to register
     */
    public function offsetSet($offset, $value)
    {
        $this->builderConfig[$offset] = $value;
    }

    /**
     * Remove a registered client by name
     *
     * @param string $offset Client to remove by name
     */
    public function offsetUnset($offset)
    {
        unset($this->builderConfig[$offset]);
    }

    /**
     * Check if a client is registered with the service builder by name
     *
     * @param string $offset Name to check to see if a client exists
     *
     * @return bool
     */
    public function offsetExists($offset)
    {
        return isset($this->builderConfig[$offset]);
    }

    /**
     * Get a registered client by name
     *
     * @param string $offset Registered client name to retrieve
     *
     * @return ClientInterface
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }
}