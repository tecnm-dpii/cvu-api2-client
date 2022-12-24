<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Lgacs3;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Lgacs3\Lgac;

class LgacsResource extends AbstractResource
{
    /**
     * @param array $params
     *  ['id_plantel']
     *  ['id_nivel']
     *  ['plantel']
     *  ['nivel']
     *
     * @return Lgac[]
     */
    public function getAll(array $params = [])
    {
        $response = $this->protectedGet('/lgacs3/lgacs', $params);
        return JsonEncoder::decode((string)$response->getBody(), Lgac::class);
    }

    /**
     * @param int|string $id_lgac
     * @return Lgac
     */
    public function getById($id_lgac)
    {
        $response = $this->protectedGet("/lgacs3/lgacs/{$id_lgac}");
        return JsonEncoder::decode((string)$response->getBody(), Lgac::class);
    }

    /**
     * @param string $clave
     * @return Lgac
     */
    public function getByClave($clave)
    {
        $response = $this->protectedGet("/lgacs3/lgacs/@clave/{$clave}");
        return JsonEncoder::decode((string)$response->getBody(), Lgac::class);
    }
}
