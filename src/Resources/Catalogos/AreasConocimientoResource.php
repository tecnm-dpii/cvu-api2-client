<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Catalogos;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Catalogos\AreaConocimiento;

class AreasConocimientoResource extends AbstractResource
{
    /**
     * @return AreaConocimiento[]
     */
    public function getAll()
    {
        $response = $this->protectedGet('/catalogos/areas-conocimiento');
        return JsonEncoder::decode((string)$response->getBody(), AreaConocimiento::class);
    }

    /**
     * @param int|string $id_area
     * @return AreaConocimiento
     */
    public function getById($id_area)
    {
        $response = $this->protectedGet("/catalogos/areas-conocimiento/{$id_area}");
        return JsonEncoder::decode((string)$response->getBody(), AreaConocimiento::class);
    }
}
