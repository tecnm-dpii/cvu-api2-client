<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Catalogos;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Catalogos\NivelEducativo;

class NivelesEstudiosResource extends AbstractResource
{
    /**
     * @return NivelEducativo[]
     */
    public function getAll()
    {
        $response = $this->protectedGet('/catalogos/niveles-educativos');
        return JsonEncoder::decode((string)$response->getBody(), NivelEducativo::class);
    }

    /**
     * @param int|string $id
     * @return NivelEducativo
     */
    public function getById($id)
    {
        $response = $this->protectedGet("/catalogos/niveles-educativos/{$id}");
        return JsonEncoder::decode((string)$response->getBody(), NivelEducativo::class);
    }
}
