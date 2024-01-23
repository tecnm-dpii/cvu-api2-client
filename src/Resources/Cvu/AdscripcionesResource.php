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
     * @deprecated
     * @return Adscripcion
     */
    public function getCurrent()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/my/adscripciones/@current');
        return JsonEncoder::decode((string)$response->getBody(), Adscripcion::class);
    }

    /**
     * @deprecated
     * @return Plaza[]
     */
    public function getCurrentPlazas()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/my/adscripciones/@current/plazas');
        return JsonEncoder::decode((string)$response->getBody(), Plaza::class);
    }

    /**
     * @deprecated
     * @return Puesto[]
     */
    public function getCurrentPuestos()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/my/adscripciones/@current/puestos');
        return JsonEncoder::decode((string)$response->getBody(), Puesto::class);
    }

    /**
     * @return Adscripcion
     */
    public function getMyCurrent()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/my/adscripciones/@current');
        return JsonEncoder::decode((string)$response->getBody(), Adscripcion::class);
    }

    /**
     * @return Plaza[]
     */
    public function getMyCurrentPlazas()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/my/adscripciones/@current/plazas');
        return JsonEncoder::decode((string)$response->getBody(), Plaza::class);
    }

    /**
     * @return Puesto[]
     */
    public function getMyCurrentPuestos()
    {
        $this->requiresOwnerAccessToken(true);
        $response = $this->protectedGet('/cvu/my/adscripciones/@current/puestos');
        return JsonEncoder::decode((string)$response->getBody(), Puesto::class);
    }
}
