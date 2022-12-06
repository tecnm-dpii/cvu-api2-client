<?php

namespace TecNM_DPII\CvuApi2Client;

use Fig\Http\Message\RequestMethodInterface;
use Francerz\Http\Utils\Constants\MediaTypes;
use Francerz\Http\Utils\Exceptions\ClientErrorException;
use Francerz\Http\Utils\Exceptions\ServerErrorException;
use Francerz\Http\Utils\HttpHelper;
use Francerz\Http\Utils\UriHelper;
use Francerz\PowerData\Objects;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

abstract class AbstractResource
{
    private $client;
    private $httpHelper;
    private $requiresOwnerAccessToken = false;

    public function __construct(?CvuApi2Client $client = null)
    {
        $this->client = $client ?? CvuApi2Client::getLastInstance();
        $this->httpHelper = new HttpHelper($this->client->getHttpFactoryManager());
    }

    protected static function cast(object $obj, string $className)
    {
        return Objects::cast($obj, $className);
    }

    protected static function castArray(array $data, string $className)
    {
        $objs = [];
        foreach ($data as $k => $o) {
            $objs[$k] = Objects::cast($o, $className);
        }
        return $objs;
    }

    protected function buildRequest(
        string $method,
        string $path,
        array $queryParams = [],
        $content = null,
        string $mediaType = MediaTypes::APPLICATION_X_WWW_FORM_URLENCODED
    ): RequestInterface {
        $uri = $this->client->getCvuOAuth2Client()->getResourcesEndpoint();
        $uri = UriHelper::appendPath($uri, $path);
        if (!empty($params)) {
            $uri = UriHelper::withQueryParams($uri, $params);
        }

        $requestFactory = $this->client->getHttpFactoryManager()->getRequestFactory();
        $request = $requestFactory->createRequest($method, $uri);
        // $request = $this->client->getOAuth2Client()->bindClientAccessToken($request);
        if ($this->requiresOwnerAccessToken) {
            $request = $this->client->getOAuth2Client()->bindOwnerAccessToken($request);
        }

        if (isset($content)) {
            $request = $this->httpHelper->withContent($request, $mediaType, $content);
        }

        return $request;
    }

    protected function protectedGet(string $path, array $params = [])
    {
        $request = $this->buildRequest(RequestMethodInterface::METHOD_GET, $path, $params);
        return $this->sendRequest($request);
    }

    protected function requiresOwnerAccessToken(bool $required = true)
    {
        $this->requiresOwnerAccessToken = $required;
    }

    protected function sendRequest(RequestInterface $request): ResponseInterface
    {
        $response = $this->client->getOAuth2Client()->getHttpClient()->sendRequest($request);
        if (HttpHelper::isClientError($response)) {
            throw new ClientErrorException($request, $response, print_r($response->getBody(), true));
        } elseif (HttpHelper::isServerError($response)) {
            throw new ServerErrorException($request, $response, print_r($response->getBody(), true));
        }
        return $response;
    }
}
