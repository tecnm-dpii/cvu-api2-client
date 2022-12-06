<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Catalogos;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Catalogos\Plantel;

class PlantelesResource extends AbstractResource
{
    /**
     * @return Plantel[]
     */
    public function getAll()
    {
        $response = $this->protectedGet('/catalogos/planteles');
        return JsonEncoder::decode((string)$response->getBody(), Plantel::class);
    }

    /**
     * @param int|string $id_plantel
     * @return Plantel
     */
    public function getById($id_plantel)
    {
        $response = $this->protectedGet("/catalogos/planteles/{$id_plantel}");
        return JsonEncoder::decode((string)$response->getBody(), Plantel::class);
    }
}
