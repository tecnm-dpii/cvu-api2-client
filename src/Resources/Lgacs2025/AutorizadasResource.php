<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Lgacs2025;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Lgacs2025\Autorizada;

class AutorizadasResource extends AbstractResource
{
    /**
     * @param array $params
     * Optional filters can be passed to limit results:
     * - `plantel:int`
     * - `programa:int`
     * - `disciplina:int`
     * - `area:int`
     * - `clave:int`
     * - `link-plantel:int`
     * - `link-programa:int`
     * @return Autorizada[]
     */
    public function getAll(array $params = [])
    {
        $response = $this->protectedGet('/lgacs2025/autorizadas');
        return JsonEncoder::decode((string)$response->getBody(), Autorizada::class);
    }
}
