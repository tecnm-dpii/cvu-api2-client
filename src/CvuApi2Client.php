<?php

namespace TecNM_DPII\CvuApi2Client;

use Francerz\Http\Client;
use Francerz\Http\HttpFactory;
use Francerz\OAuth2\AccessToken;
use Francerz\OAuth2\Client\OAuth2Client;
use RuntimeException;

class CvuApi2Client
{
    #region Static Attributes
    private static $lastInstance;
    #endregion

    private $cvuOauth2Client;
    private $oauth2Client;
    private $httpFactoryManager;

    #region Static Methods
    public static function getLastInstance()
    {
        if (!isset(static::$lastInstance)) {
            static::$lastInstance = new static();
        }
        return static::$lastInstance;
    }
    #endregion

    public function __construct()
    {
        $this->initSessions();
        $this->cvuOauth2Client = new CvuApi2OAuth2Client();
        $this->httpFactoryManager = HttpFactory::getManager();
        $this->oauth2Client = new OAuth2Client(
            $this->cvuOauth2Client,
            new Client(),
            $this->httpFactoryManager->getRequestFactory(),
            $this->cvuOauth2Client,
            $this->cvuOauth2Client,
            $this->cvuOauth2Client,
            $this->cvuOauth2Client
        );
        $this->oauth2Client->preferBodyAuthentication();

        static::$lastInstance = $this;
    }

    private function initSessions()
    {
        switch (session_status()) {
            case PHP_SESSION_DISABLED:
                throw new RuntimeException("Cannot start because Sessions are disabled.");
            case PHP_SESSION_NONE:
                if (!headers_sent()) {
                    session_start();
                }
                break;
        }
    }

    public function getOAuth2Client()
    {
        return $this->oauth2Client;
    }

    public function getCvuOAuth2Client()
    {
        return $this->cvuOauth2Client;
    }

    public function getHttpFactoryManager()
    {
        return $this->httpFactoryManager;
    }

    public function setOwnerAccessToken(AccessToken $accessToken)
    {
        $this->oauth2Client->setOwnerAccessToken($accessToken, true);
    }
}
