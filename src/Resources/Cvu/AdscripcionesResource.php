<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Cvu;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Cvu\Adscripcion;
use TecNM_DPII\CvuApi2Core\Models\Cvu\Plaza;
use TecNM_DPII\CvuApi2Core\Models\Cvu\Puesto;

class AdscripcionesResource extends AbstractResource
{
    /**
     * @return Adscripcion
     */
    public function getCurrent()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/adscripciones/@current');
        return JsonEncoder::decode((string)$response->getBody(), Adscripcion::class);
    }

    /**
     * @return Plaza[]
     */
    public function getCurrentPlazas()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/adscripciones/@current/plazas');
        return JsonEncoder::decode((string)$response->getBody(), Plaza::class);
    }

    /**
     * @return Puesto[]
     */
    public function getCurrentPuestos()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/adscripciones/@current/puestos');
        return JsonEncoder::decode((string)$response->getBody(), Puesto::class);
    }
}
