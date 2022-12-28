<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Catalogos;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Catalogos\ProgramaEducativo;

class ProgramasEducativosResource extends AbstractResource
{
    /**
     * @return ProgramaEducativo[]
     */
    public function getAll()
    {
        $response = $this->protectedGet('/catalogos/programas-educativos');
        return JsonEncoder::decode((string)$response->getBody(), ProgramaEducativo::class);
    }

    /**
     * @param int|string $id_programa
     * @return ProgramaEducativo
     */
    public function getById($id_programa)
    {
        $response = $this->protectedGet("/catalogos/programas-educativos/{$id_programa}");
        return JsonEncoder::decode((string)$response->getBody(), ProgramaEducativo::class);
    }
}
