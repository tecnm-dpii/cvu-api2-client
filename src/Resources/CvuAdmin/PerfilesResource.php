<?php

namespace TecNM_DPII\CvuApi2Client\Resources\CvuAdmin;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Cvu\Perfil;

class PerfilesResource extends AbstractResource
{
    /**
     * @param int|string $id_persona
     * @return Perfil
     */
    public function getById($id_persona)
    {
        $response = $this->protectedGet("/cvu-admin/perfil/{$id_persona}");
        return JsonEncoder::decode((string)$response->getBody(), Perfil::class);
    }

    /**
     * @param string $cvu_tecnm
     * @return Perfil
     */
    public function getByCvuTecnm($cvu_tecnm)
    {
        $response = $this->protectedGet("/cvu-admin/perfil/@cvu/{$cvu_tecnm}");
        return JsonEncoder::decode((string)$response->getBody(), Perfil::class);
    }

    /**
     * @param string $curp
     * @return Perfil
     */
    public function getByCurp($curp)
    {
        $response = $this->protectedGet("/cvu-admin/perfil/@curp/{$curp}");
        return JsonEncoder::decode((string)$response->getBody(), Perfil::class);
    }

    /**
     * @param string $rfc
     * @return Perfil
     */
    public function getByRfc($rfc)
    {
        $response = $this->protectedGet("/cvu-admin/perfil/@rfc/{$rfc}");
        return JsonEncoder::decode((string)$response->getBody(), Perfil::class);
    }
}
