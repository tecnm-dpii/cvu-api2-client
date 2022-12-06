<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Catalogos;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Catalogos\TipoInvestigacion;

class TiposInvestigacionResource extends AbstractResource
{
    /**
     * @return TipoInvestigacion[]
     */
    public function getAll()
    {
        $response = $this->protectedGet('/catalogos/tipos-investigacion');
        return JsonEncoder::decode((string)$response->getBody(), TipoInvestigacion::class);
    }

    /**
     * @param int|string $id_tipo
     * @return TipoInvestigacion
     */
    public function getById($id_tipo)
    {
        $response = $this->protectedGet("/catalogos/tipos-investigacion/{$id_tipo}");
        return JsonEncoder::decode((string)$response->getBody(), TipoInvestigacion::class);
    }
}
