<?php

namespace Guzzle\Service;

use Guzzle\Common\Collection;
use Guzzle\Common\NullObject;
use Guzzle\Http\ClientInterface as HttpClientInterface;
use Guzzle\Service\Command\CommandInterface;
use Guzzle\Service\Command\CommandSet;
use Guzzle\Service\Description\ServiceDescription;

/**
 * Client interface for executing commands on a web service.
 */
interface ClientInterface extends HttpClientInterface
{
    /**
     * Basic factory method to create a new client.  Extend this method in
     * subclasses to build more complex clients.
     *
     * @param array|Collection $config (optiona) Configuartion data
     *
     * @return ClientInterface
     */
    static function factory($config);

    /**
     * Get a command by name.  First, the client will see if it has a service
     * description and if the service description defines a command by the
     * supplied name.  If no dynamic command is found, the client will look for
     * a concrete command class exists matching the name supplied.  If neither
     * are found, an InvalidArgumentException is thrown.
     *
     * @param string $name Name of the command to retrieve
     * @param array $args (optional) Arguments to pass to the command
     *
     * @return CommandInterface
     * @throws InvalidArgumentException if no command can be found by name
     */
    function getCommand($name, array $args = array());

    /**
     * Execute a command and return the response
     *
     * @param CommandInterface|CommandSet $command The command or set to execute
     *
     * @return mixed Returns the result of the executed command's
     *       {@see CommandInterface::getResult} method if a CommandInterface is
     *       passed, or the CommandSet itself if a CommandSet is passed
     * @throws InvalidArgumentException if an invalid command is passed
     * @throws Command\CommandSetException if a set contains commands associated
     *      with other clients
     */
    function execute($command);

    /**
     * Set the service description of the client
     *
     * @param ServiceDescription $description Service description that describes
     *      all of the commands and information of the client
     *
     * @return ClientInterface
     */
    function setDescription(ServiceDescription $service);

    /**
     * Get the service description of the client
     *
     * @return ServiceDescription|NullObject
     */
    function getDescription();
}