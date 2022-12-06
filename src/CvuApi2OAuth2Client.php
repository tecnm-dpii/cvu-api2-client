<?php

namespace TecNM_DPII\CvuApi2Client;

use Exception;
use Francerz\Crypto\RandomString;
use Francerz\Http\Uri;
use Francerz\OAuth2\AccessToken;
use Francerz\OAuth2\Client\ClientAccessTokenSaverInterface;
use Francerz\OAuth2\Client\OAuth2ClientInterface;
use Francerz\OAuth2\Client\OwnerAccessTokenSaverInterface;
use Francerz\OAuth2\Client\PKCECode;
use Francerz\OAuth2\Client\PKCEManagerInterface;
use Francerz\OAuth2\Client\StateManagerInterface;
use Francerz\OAuth2\CodeChallengeMethodsEnum;
use InvalidArgumentException;
use Psr\Http\Message\UriInterface;
use RuntimeException;

class CvuApi2OAuth2Client implements
    OAuth2ClientInterface,
    ClientAccessTokenSaverInterface,
    OwnerAccessTokenSaverInterface,
    StateManagerInterface,
    PKCEManagerInterface
{
    #region Constants
    private const DEFAULT_AUTHORIZATION_ENDPOINT = 'https://cvu.dpii.tecnm.mx/index.php/oauth2/authorize';
    private const DEFAULT_TOKEN_ENDPOINT = 'https://cvu.dpii.tecnm.mx/index.php/oauth2/token';
    private const DEFAULT_RESOURCES_ENDPOINT = 'https://cvu.dpii.tecnm.mx/api2/index.php';

    private const ENV_KEY_CLIENT_ID = 'CVU_API2_CLIENT_ID';
    private const ENV_KEY_CLIENT_SECRET = 'CVU_API2_CLIENT_SECRET';

    private const SESSION_KEY_CLIENT_ACCESS_TOKEN = 'cvu-api2.client-access-token';
    private const SESSION_KEY_OWNER_ACCESS_TOKEN = 'cvu-api2.owner-access-token';
    private const SESSION_KEY_STATE = 'cvu-api2.state';
    private const SESSION_KEY_PKCE_CODE = 'cvu-api2.pkce-code';
    #endregion

    private $clientId = '';
    private $clientSecret;
    private $authorizationEndpoint;
    private $tokenEndpoint;
    private $callbackEndpoint;
    private $resourceEndpoint;

    private $pathClientAccessToken;

    #region Static Methods
    private static function valueToUri($value): UriInterface
    {
        if ($value instanceof UriInterface) {
            return $value;
        }
        if (is_object($value) && method_exists($value, '__toString')) {
            $value = (string)$value;
        }
        if (!is_string($value)) {
            throw new InvalidArgumentException("Invalid value to convert to Uri Interface");
        }
        return new Uri($value);
    }
    #endregion

    public function __construct()
    {
        $this->init();
    }

    private function init()
    {
        $this->setClientId($_ENV[self::ENV_KEY_CLIENT_ID] ?? '');
        $this->setClientSecret($_ENV[self::ENV_KEY_CLIENT_SECRET] ?? null);
        $this->setAuthorizationEndpoint(self::DEFAULT_AUTHORIZATION_ENDPOINT);
        $this->setTokenEndpoint(self::DEFAULT_TOKEN_ENDPOINT);
        $this->setResourcesEndpoint(self::DEFAULT_RESOURCES_ENDPOINT);

        $this->pathClientAccessToken = sprintf(
            "%s/.oauth2/access_token_%s.json",
            dirname(__FILE__, 2),
            $this->getClientId()
        );
    }

    private function setClientId(string $clientId)
    {
        $this->clientId = $clientId;
    }

    public function getClientId(): string
    {
        return $this->clientId;
    }

    private function setClientSecret(?string $clientSecret)
    {
        $this->clientSecret = $clientSecret;
    }

    public function getClientSecret(): ?string
    {
        return $this->clientSecret;
    }

    private function setAuthorizationEndpoint($authorizationEndpoint)
    {
        if (is_null($authorizationEndpoint)) {
            $this->authorizationEndpoint = null;
            return;
        }
        $authorizationEndpoint = static::valueToUri($authorizationEndpoint);
        $this->authorizationEndpoint = $authorizationEndpoint;
    }

    public function getAuthorizationEndpoint(): ?UriInterface
    {
        return $this->authorizationEndpoint;
    }

    private function setTokenEndpoint($tokenEndpoint)
    {
        if (is_null($tokenEndpoint)) {
            $this->tokenEndpoint = null;
            return;
        }
        $tokenEndpoint = static::valueToUri($tokenEndpoint);
        $this->tokenEndpoint = $tokenEndpoint;
    }

    public function getTokenEndpoint(): ?UriInterface
    {
        return $this->tokenEndpoint;
    }

    public function setCallbackEndpoint($callbackEndpoint)
    {
        if (is_null($callbackEndpoint)) {
            $this->callbackEndpoint = null;
            return;
        }
        $callbackEndpoint = static::valueToUri($callbackEndpoint);
        $this->callbackEndpoint = $callbackEndpoint;
    }

    public function getCallbackEndpoint(): ?UriInterface
    {
        return $this->callbackEndpoint;
    }

    public function setResourcesEndpoint($resourceEndpoint)
    {
        if (is_null($resourceEndpoint)) {
            $this->resourceEndpoint = null;
            return;
        }
        $resourceEndpoint = static::valueToUri($resourceEndpoint);
        $this->resourceEndpoint = $resourceEndpoint;
    }

    public function getResourcesEndpoint()
    {
        return $this->resourceEndpoint;
    }

    private function saveClientAccessTokenToFile(AccessToken $accessToken)
    {
        $filepath = $this->pathClientAccessToken;
        $filedir = dirname($filepath);
        if (!file_exists($filedir)) {
            mkdir($filedir, 0777, true);
        }
        $file = fopen($filepath, 'w');
        if ($file === false) {
            $error = error_get_last();
            throw new RuntimeException(
                is_array($error) && isset($error['message']) ?
                $error['message'] :
                'Failed to open CVU TecNM API Client Access Token file.'
            );
        }
        $written = fwrite($file, json_encode($accessToken));
        if ($written === false) {
            $error = error_get_last();
            throw new RuntimeException(
                is_array($error) && isset($error['message']) ?
                $error['message'] :
                'Failed to write CVU TecNM API Client Access Token file.'
            );
        }
        fclose($file);
    }

    private function loadClientAccessTokenFromFile(): ?AccessToken
    {
        $filepath = $this->pathClientAccessToken;
        if (!file_exists($filepath)) {
            return null;
        }
        $jsonString = \file_get_contents($filepath);
        try {
            return AccessToken::fromJsonString($jsonString);
        } catch (Exception $ex) {
            return null;
        }
    }

    public function loadClientAccessToken(): ?AccessToken
    {
        /** @var AccessToken|null */
        $accessToken = $_SESSION[self::SESSION_KEY_CLIENT_ACCESS_TOKEN] ?? null;
        if (isset($accessToken) || isset($accessToken) && $accessToken->isExpired()) {
            $_SESSION[self::SESSION_KEY_CLIENT_ACCESS_TOKEN] = $this->loadClientAccessTokenFromFile();
        }
        return $_SESSION[self::SESSION_KEY_CLIENT_ACCESS_TOKEN];
    }

    public function saveClientAccessToken(AccessToken $accessToken)
    {
        $_SESSION[self::SESSION_KEY_CLIENT_ACCESS_TOKEN] = $accessToken;
        try {
            $this->saveClientAccessTokenToFile($accessToken);
        } catch (Exception $ex) {
        }
    }

    public function loadOwnerAccessToken(): ?AccessToken
    {
        $accessToken = $_SESSION[self::SESSION_KEY_OWNER_ACCESS_TOKEN] ?? null;
        return $accessToken instanceof AccessToken ? $accessToken : null;
    }

    public function saveOwnerAccessToken(AccessToken $accessToken)
    {
        $_SESSION[self::SESSION_KEY_OWNER_ACCESS_TOKEN] = $accessToken;
    }

    public function generateState(): string
    {
        $state = $_SESSION[self::SESSION_KEY_STATE] = RandomString::generateUrlSafe(16);
        return $state;
    }
    public function getState(): ?string
    {
        $state = $_SESSION[self::SESSION_KEY_STATE] ?? null;
        return is_string($state) ? $state : null;
    }

    public function generatePKCECode(): PKCECode
    {
        $pkceCode = $_SESSION[self::SESSION_KEY_PKCE_CODE] = new PKCECode(
            RandomString::generateUrlSafe(64),
            CodeChallengeMethodsEnum::SHA256
        );
        return $pkceCode;
    }

    public function getPKCECode(): ?PKCECode
    {
        $pkceCode = $_SESSION[self::SESSION_KEY_PKCE_CODE] ?? null;
        return $pkceCode instanceof PKCECode ? $pkceCode : null;
    }
}
