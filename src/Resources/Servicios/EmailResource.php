<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Servicios;

use Francerz\Http\Utils\Constants\MediaTypes;
use Francerz\Http\Utils\HttpHelper;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Client\CvuApi2OAuth2Client;
use TecNM_DPII\CvuApi2Core\Models\Servicios\Email;

class EmailResource extends AbstractResource
{
    /**
     * Envía un correo electrónico utilizando la misma dirección de correo de SIITEC.
     *
     * @param Email $email
     * @return void
     */
    public function send(Email $email)
    {
        $emailOverride = CvuApi2OAuth2Client::getEnv(CvuApi2OAuth2Client::ENV_KEY_EMAIL_OVERRIDE);
        if (!empty($emailOverride)) {
            $email->clearRecipients();
            $email->addTo($emailOverride);
        }
        $this->requiresClientAccessToken(true);
        $response = $this->protectedPost('/servicios/email', $email, MediaTypes::APPLICATION_JSON);
        return HttpHelper::getContent($response);
    }
}
