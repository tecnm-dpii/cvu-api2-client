<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Cvu;

use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Client\CvuApi2Client;
use TecNM_DPII\CvuApi2Core\Models\Cvu\Adscripcion;

class AdscripcionesResource extends AbstractResource
{
    /**
     * @return Adscripcion
     */
    public function getCurrent()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/adscripciones/@current');
        $data = json_decode($response->getBody(), false);
        $data = reset($data) ?: null;
        return is_null($data) ? null : static::cast($data, Adscripcion::class);
    }
}
