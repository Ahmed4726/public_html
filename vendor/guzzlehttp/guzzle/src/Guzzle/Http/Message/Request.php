<?php

namespace Guzzle\Http\Message;

use Guzzle\Guzzle;
use Guzzle\Common\Event;
use Guzzle\Common\Collection;
use Guzzle\Http\ClientInterface;
use Guzzle\Http\CurlException;
use Guzzle\Http\QueryString;
use Guzzle\Http\Cookie;
use Guzzle\Http\EntityBody;
use Guzzle\Http\Url;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventDispatcher;

/**
 * HTTP request class to send requests
 */
class Request extends AbstractMessage implements RequestInterface
{
    /**
     * @var EventDispatcherInterface
     */
    protected $eventDispatcher;

    /**
     * @var Url HTTP Url
     */
    protected $url;

    /**
     * @var string HTTP method (GET, PUT, POST, DELETE, HEAD, OPTIONS, TRACE)
     */
    protected $method;

    /**
     * @var ClientInterface
     */
    protected $client;

    /**
     * @var Response Response of the request
     */
    protected $response;

    /**
     * @var EntityBody Response body
     */
    protected $responseBody;

    /**
     * @var string State of the request object
     */
    protected $state;

    /**
     * @var Cookie Cookies to send with the request
     */
    protected $cookie;

    /**
     * @param string Auth username
     */
    protected $username;

    /**
     * @param string Auth password
     */
    protected $password;

    /**
     * @var Collection cURL specific transfer options
     */
    protected $curlOptions;

    /**
     * {@inheritdoc}
     */
    public static function getAllEvents()
    {
        return array(
            // Called when receiving or uploading data through cURL
            'curl.callback.read', 'curl.callback.write', 'curl.callback.progress',
            // About to send the request
            'request.before_send',
            // Sent the request
            'request.sent',
            // Completed HTTP transaction
            'request.complete',
            // A request received a successful response
            'request.success',
            // A request received an unsuccessful response
            'request.error',
            // An exception is being thrown because of an unsuccessful response
            'request.exception',
            // Received response status line
            'request.receive.status_line',
            // Received response header
            'request.receive.header',
            // Manually set a response
            'request.set_response'
        );
    }

    /**
     * Create a new request
     *
     * @param string $method HTTP method
     * @param string|Url $url HTTP URL to connect to.  The URI scheme, host
     *      header, and URI are parsed from the full URL.  If query string
     *      parameters are present they will be parsed as well.
     * @param array|Collection $headers (optional) HTTP headers
     */
    public function __construct($method, $url, $headers = array())
    {
        $this->method = strtoupper($method);
        $this->headers = new Collection();
        $this->curlOptions = new Collection();
        $this->params = new Collection();
        $this->setUrl($url);
        if ($headers) {
            foreach ($headers as $key => $value) {
                if ($key == 'Host') {
                    $this->removeHeader($key);
                }
                $this->setHeader($key, $value);
            }
        }
        if (!$this->hasHeader('User-Agent', true)) {
            $this->setHeader('User-Agent', Guzzle::getDefaultUserAgent());
        }

        $this->cookie = Cookie::factory($this->getHeader('Cookie'));
        $this->setState(self::STATE_NEW);
    }

    /**
     * Clone the request object, leaving off any response that was received
     */
    public function __clone()
    {
        if ($this->eventDispatcher) {
            $this->eventDispatcher = clone $this->eventDispatcher;
        }
        $this->curlOptions = clone $this->curlOptions;
        $this->params = clone $this->params;
        $this->url = clone $this->url;
        $this->headers = clone $this->headers;
        $this->response = $this->responseBody = null;
        $this->params->remove('curl_handle')
             ->remove('queued_response')
             ->remove('curl_multi');
        $this->setState(RequestInterface::STATE_NEW);
    }

    /**
     * Get the HTTP request as a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getRawHeaders() . "\r\n\r\n";
    }

    /**
     * Default method that will throw exceptions if an unsuccessful response
     * is received.
     *
     * @param Event $event Received
     * @throws BadResponseException if the response is not successful
     */
    public static function onRequestError(Event $event)
    {
        $e = new BadResponseException('Unsuccessful response | ' . implode(' | ', array(
            '[status code] ' . $event['response']->getStatusCode(),
            '[reason phrase] ' . $event['response']->getReasonPhrase(),
            '[url] ' . $event['request']->getUrl(),
            '[request] ' . (string) $event['request'],
            '[response] ' . (string) $event['response']
        )));
        $e->setResponse($event['response']);
        $e->setRequest($event['request']);
        $event['request']->dispatch('request.exception', array(
            'request'   => $event['request'],
            'response'  => $event['response'],
            'exception' => $e
        ));
        throw $e;
    }

    /**
     * Set the client used to transport the request
     *
     * @param ClientInterface $client
     *
     * @return Request
     */
    public function setClient(ClientInterface $client)
    {
        $this->client = $client;

        return $this;
    }

    /**
     * Get the client used to transport the request
     *
     * @return ClientInterface $client
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Get the raw message headers as a string
     *
     * @return string
     */
    public function getRawHeaders()
    {
        $raw = $this->method . ' ' . $this->getResourceUri();
        $protocolVersion = $this->protocolVersion ?: '1.1';
        $raw = trim($raw) . ' ' . strtoupper(str_replace('https', 'http', $this->url->getScheme())) . '/' . $protocolVersion . "\r\n";

        foreach ($this->headers as $key => $value) {
            $raw .= $key . ': ' . $value . "\r\n";
        }

        return rtrim($raw, "\r\n");
    }

    /**
     * Set the URL of the request
     *
     * Warning: Calling this method will modify headers, rewrite the  query
     * string object, and set other data associated with the request.
     *
     * @param string|Url $url Full URL to set including query string
     *
     * @return Request
     */
    public function setUrl($url)
    {
        if (is_string($url)) {
            $this->url = Url::factory($url);
        } else if ($url instanceof Url) {
            $this->url = $url;
        } else {
            throw new \InvalidArgumentException('Invalid URL sent to ' . __METHOD__);
        }

        $this->setHost($this->url->getHost());
        $this->setPort($this->url->getPort());
        if ($this->url->getUsername() && $this->url->getPassword()) {
            $this->setAuth($this->url->getUsername(), $this->url->getPassword());
        }

        // Remove the auth info from the URL
        $this->url->setUsername(null);
        $this->url->setPassword(null);

        return $this;
    }

    /**
     * Send the request
     *
     * @return Response
     * @throws RequestException on a request error
     */
    public function send()
    {
        if (!$this->client) {
            throw new \RuntimeException('A client must be set on the request');
        }

        return $this->client->send($this);
    }

    /**
     * Get the previously received {@see Response} or NULL if the request has
     * not been sent
     *
     * @return Response|null
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * Get the collection of key value pairs that will be used as the query
     * string in the request
     *
     * @param bool $asString (optional) Set to TRUE to get the query as string
     *
     * @return QueryString|string
     */
    public function getQuery($asString = false)
    {
        return $asString
            ? (string) $this->url->getQuery()
            : $this->url->getQuery();
    }

    /**
     * Get the HTTP method of the request
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * Get the URI scheme of the request (http, https, ftp, etc)
     *
     * @return string
     */
    public function getScheme()
    {
        return $this->url->getScheme();
    }

    /**
     * Set the URI scheme of the request (http, https, ftp, etc)
     *
     * @param string $scheme Scheme to set
     *
     * @return Request
     */
    public function setScheme($scheme)
    {
        $this->url->setScheme($scheme);

        return $this;
    }

    /**
     * Get the host of the request
     *
     * @return string
     */
    public function getHost()
    {
        return $this->url->getHost();
    }

    /**
     * Set the host of the request.
     *
     * @param string $host Host to set (e.g. www.yahoo.com, www.yahoo.com)
     *
     * @return Request
     */
    public function setHost($host)
    {
        $parts = explode(':', $host);
        $this->url->setHost($parts[0]);
        if (isset($parts[1])) {
            $this->setPort($parts[1]);
        } else {
            $this->headers->set('Host', $host);
        }

        return $this;
    }

    /**
     * Get the HTTP protocol version of the request
     *
     * @param bool $curlValue (optional) Set to TRUE to retrieve the cURL value
     *      for the HTTP protocol version
     *
     * @return string|int
     */
    public function getProtocolVersion($curlValue = false)
    {
        if (!$curlValue) {
            return $this->protocolVersion;
        } else {
            return $this->protocolVersion === '1.0'
                ? CURL_HTTP_VERSION_1_0 : CURL_HTTP_VERSION_1_1;
        }
    }

    /**
     * Set the HTTP protocol version of the request (e.g. 1.1 or 1.0)
     *
     * @param string $protocol HTTP protocol version to use with the request
     *
     * @return Request
     */
    public function setProtocolVersion($protocol)
    {
        $this->protocolVersion = $protocol;

        return $this;
    }

    /**
     * Get the path of the request (e.g. '/', '/index.html')
     *
     * @return string
     */
    public function getPath()
    {
        return $this->url->getPath();
    }

    /**
     * Set the path of the request (e.g. '/', '/index.html')
     *
     * @param string|array $path Path to set or array of segments to implode
     *
     * @return Request
     */
    public function setPath($path)
    {
        $this->url->setPath($path);

        return $this;
    }

    /**
     * Get the port that the request will be sent on if it has been set
     *
     * @return int|null
     */
    public function getPort()
    {
        return $this->url->getPort();
    }

    /**
     * Set the port that the request will be sent on
     *
     * @param int $port Port number to set
     *
     * @return Request
     */
    public function setPort($port)
    {
        $this->url->setPort($port);
        // Include the port in the Host header if it is not the default port
        // for the scheme of the URL
        if (($this->url->getScheme() == 'http' && $port != 80) || ($this->url->getScheme() == 'https' && $port != 443)) {
            $this->headers->set('Host', $this->url->getHost() . ':' . $port);
        } else {
            $this->headers->set('Host', $this->url->getHost());
        }

        return $this;
    }

    /**
     * Get the username to pass in the URL if set
     *
     * @return string|null
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Get the password to pass in the URL if set
     *
     * @return string|null
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set HTTP authorization parameters
     *
     * @param string|false $user (optional) User name or false disable authentication
     * @param string $password (optional) Password
     * @param string $scheme (optional) Curl authentication scheme to use
     *
     * @return Request
     *
     * @see http://www.ietf.org/rfc/rfc2617.txt
     * @throws RequestException
     */
    public function setAuth($user, $password = '', $scheme = CURLAUTH_BASIC)
    {
        // If we got false or null, disable authentication
        if (!$user || !$password) {
            $this->password = $this->username = null;
            $this->removeHeader('Authorization');
            $this->getCurlOptions()->remove(CURLOPT_HTTPAUTH);
        } else {
            $this->username = $user;
            $this->password = $password;
            // Bypass CURL when using basic auth to promote connection reuse
            if ($scheme == CURLAUTH_BASIC) {
                $this->getCurlOptions()->remove(CURLOPT_HTTPAUTH);
                $this->setHeader('Authorization', 'Basic ' . base64_encode($this->username . ':' . $this->password));
            } else {
                $this->getCurlOptions()->set(CURLOPT_HTTPAUTH, $scheme)
                     ->set(CURLOPT_USERPWD, $this->username . ':' . $this->password);
            }
        }

        return $this;
    }

    /**
     * Get the URI of the request (e.g. '/', '/index.html', '/index.html?q=1)
     * This is the path plus the query string, fragment
     *
     * @return string
     */
    public function getResourceUri()
    {
        $url = $this->url->getPath();

        $query = (string) $this->url->getQuery();
        if ($query) {
            $url .= $query;
        }

        return $url;
    }

    /**
     * Get the full URL of the request (e.g. 'http://www.guzzle-project.com/')
     *
     * scheme://username:password@domain:port/path?query_string#fragment
     *
     * @return string
     */
    public function getUrl()
    {
        return (string) $this->url;
    }

    /**
     * Get the state of the request.  One of 'complete', 'sending', 'new'
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set the state of the request
     *
     * @param string $state State of the request (complete, sending, or new)
     *
     * @return Request
     */
    public function setState($state)
    {
        $this->state = $state;
        if ($this->state == self::STATE_NEW) {
            $this->responseBody = $this->response = null;
        } else if ($this->state == self::STATE_COMPLETE) {
            $this->processResponse();
        }

        return $this;
    }

    /**
     * Get the cURL options that will be applied when the cURL handle is created
     *
     * @return Collection
     */
    public function getCurlOptions()
    {
        return $this->curlOptions;
    }

    /**
     * Method to receive HTTP response headers as they are retrieved
     *
     * @param string $data Header data.
     *
     * @return integer Returns the size of the data.
     */
    public function receiveResponseHeader($data)
    {
        $this->state = self::STATE_TRANSFER;
        $length = strlen($data);

        // Normalize line endings
        $data = preg_replace("/([^\r])(\n)\b/", "$1\r\n", $data);

        if (preg_match('/^\HTTP\/1\.[0|1]\s\d{3}\s.+$/', $data)) {
            $previousResponse = $this->response;
            list($dummy, $code, $status) = explode(' ', str_replace("\r\n", '', $data), 3);
            $this->response = new Response($code, null, $this->getResponseBody());
            $this->response->setStatus($code, $status)->setRequest($this);
            $this->dispatch('request.receive.status_line', array(
                'line'              => $data,
                'status_code'       => $code,
                'reason_phrase'     => $status,
                'previous_response' => $previousResponse
            ));
        } else if ($length > 2) {
            list($header, $value) = array_map('trim', explode(':', trim($data), 2));
            $this->response->addHeaders(array(
                $header => $value
            ));
            $this->dispatch('request.receive.header', array(
                'header' => $header,
                'value'  => $value
            ), true);
        }

        return $length;
    }

    /**
     * Manually set a response for the request.
     *
     * This method is useful for specifying a mock response for the request or
     * setting the response using a cache.  Manually setting a response will
     * bypass the actual sending of a request.
     *
     * @param Response $response Response object to set
     * @param bool $queued (optional) Set to TRUE to keep the request in a stat
     *      of not having been sent, but queue the response for send()
     *
     * @return Request Returns a reference to the object.
     */
    public function setResponse(Response $response, $queued = false)
    {
        $response->setRequest($this);

        if (!$queued) {
            $this->getParams()->remove('queued_response');
            $this->response = $response;
            $this->responseBody = $response->getBody();
            $this->processResponse();
        } else {
            $this->getParams()->set('queued_response', $response);
        }

        $this->dispatch('request.set_response', $this->getEventArray());

        return $this;
    }

    /**
     * Set the EntityBody that will hold the response message's entity body.
     *
     * This method should be invoked when you need to send the response's
     * entity body somewhere other than the normal php://temp buffer.  For
     * example, you can send the entity body to a socket, file, or some other
     * custom stream.
     *
     * @param EntityBody $body Response body object
     *
     * @return Request
     */
    public function setResponseBody(EntityBody $body)
    {
        $this->responseBody = $body;

        return $this;
    }

    /**
     * Determine if the response body is repeatable (readable + seekable)
     *
     * @return bool
     */
    public function isResponseBodyRepeatable()
    {
        return !$this->responseBody ? true : $this->responseBody->isSeekable() && $this->responseBody->isReadable();
    }

    /**
     * Get an array of Cookies or a specific cookie from the request
     *
     * @param string $name (optional) Cookie to retrieve
     *
     * @return null|string|Cookie Returns null if not found by name, a Cookie
     *      object if no $name is supplied, or the cookie value by name if found
     *      If a Cookie object is returned, changes to the cookie object does
     *      not modify the request's cookies.  You will need to set the cookie
     *      back on the request after modifying the object.
     */
    public function getCookie($name = null)
    {
        return !$name ? clone $this->cookie : $this->cookie->get($name);
    }

    /**
     * Set the Cookie header using an array or Cookie object
     *
     * @param array|Cookie $cookies Cookie data to set on the request
     *
     * @return Request
     */
    public function setCookie($cookies)
    {
        if ($cookies instanceof Cookie) {
            $this->cookie = $cookies;
        } else if (is_array($cookies)) {
            $this->cookie->replace($cookies);
        } else {
            throw new \InvalidArgumentException('Invalid cookie data');
        }

        $this->headers->set('Cookie', (string) $this->cookie);

        return $this;
    }

    /**
     * Add a Cookie value by name to the Cookie header
     *
     * @param string $name Name of the cookie to add
     * @param string $value Value to set
     *
     * @return Request
     */
    public function addCookie($name, $value)
    {
        $this->cookie->add($name, $value);
        $this->headers->set('Cookie', (string) $this->cookie);

        return $this;
    }

    /**
     * Remove the cookie header or a specific cookie value by name
     *
     * @param string $name (optional) Cookie to remove by name.  If no value is
     *      provided, the entire Cookie header is removed from the request
     *
     * @return Request
     */
    public function removeCookie($name = null)
    {
        $this->cookie->remove($name);
        $this->headers->set('Cookie', (string) $this->cookie);

        return $this;
    }

    /**
     * Returns whether or not the response served to the request can be cached
     *
     * @return bool
     */
    public function canCache()
    {
        // Only GET and HEAD requests can be cached
        if ($this->method != RequestInterface::GET && $this->method != RequestInterface::HEAD) {
            return false;
        }

        // Never cache requests when using no-store
        if ($this->hasCacheControlDirective('no-store')) {
            return false;
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function setEventDispatcher(EventDispatcherInterface $eventDispatcher)
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->eventDispatcher->addListener('request.error', array(__CLASS__, 'onRequestError'), -255);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getEventDispatcher()
    {
        if (!$this->eventDispatcher) {
            $this->setEventDispatcher(new EventDispatcher());
        }

        return $this->eventDispatcher;
    }

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, array $context = array())
    {
        $context['request'] = $this;
        $this->getEventDispatcher()->dispatch($eventName, new Event($context));
    }

    /**
     * {@inheritdoc}
     */
    protected function changedHeader($action, $keyOrArray)
    {
        $keys = (array) $keyOrArray;
        parent::changedHeader($action, $keys);

        if (in_array('Cookie', $keys)) {
            if ($action == 'set') {
                $this->cookie = Cookie::factory($this->getHeader('Cookie'));
            } else {
                $this->cookie->clear();
            }
        }

        if (in_array('Host', $keys)) {
            $parts = explode(':', $this->getHeader('Host'));
            $this->url->setHost($parts[0]);
            $this->setPort(!empty($parts[1])
                ? $parts[1]
                : ($this->url->getScheme() == 'https' ? 443 : 80));
        }
    }

    /**
     * Get the EntityBody that will store the received response entity body
     *
     * @return EntityBody
     */
    protected function getResponseBody()
    {
        if ($this->responseBody === null) {
            $this->responseBody = EntityBody::factory();
        }

        return $this->responseBody;
    }

    /**
     * Get an array containing the request and response for event notifications
     *
     * @return array
     */
    protected function getEventArray()
    {
        return array(
            'request'  => $this,
            'response' => $this->response
        );
    }

    /**
     * Process a received response
     *
     * @throws BadResponseException on unsuccessful responses
     */
    protected function processResponse()
    {
        // Use the queued response if one is set
        if ($this->getParams()->get('queued_response')) {
            $this->response = $this->getParams()->get('queued_response');
            $this->responseBody = $this->response->getBody();
            $this->getParams()->remove('queued_response');
        } else if (!$this->response) {
            // If no response, then processResponse shouldn't have been called
            $e = new RequestException('Error completing request');
            $e->setRequest($this);
            throw $e;
        }

        $this->state = self::STATE_COMPLETE;

        // A request was sent, but we don't know if we'll send more or if the
        // final response will be a successful response
        $this->dispatch('request.sent', $this->getEventArray());

        // Some response processors will remove the response or reset the state
        // (example: ExponentialBackoffPlugin)
        if ($this->state == RequestInterface::STATE_COMPLETE) {

            // The request completed, so the HTTP transaction is complete
            $this->dispatch('request.complete', $this->getEventArray());

            // If the response is bad, allow listeners to modify it or throw
            // exceptions.  You can change the response by modifying the Event
            // object in your listeners or calling setResponse() on the request
            if ($this->response->isError()) {
                $event = new Event($this->getEventArray());
                $this->getEventDispatcher()->dispatch('request.error', $event);
                // Allow events of request.error to quietly change the response
                if ($event['response'] !== $this->response) {
                    $this->response = $event['response'];
                }
            }

            // If a successful response was received, dispatch an event
            if ($this->response->isSuccessful()) {
                $this->dispatch('request.success', $this->getEventArray());
            }
        }

        return $this;
    }
}