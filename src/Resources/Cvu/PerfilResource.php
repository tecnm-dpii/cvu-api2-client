<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Cvu;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Cvu\Perfil;

class PerfilResource extends AbstractResource
{
    /**
     * @deprecated
     * @return Perfil
     */
    public function getCurrent()
    {
        $this->requiresOwnerAccessToken();
        $response = $this->protectedGet("/cvu/my/perfil");
        return JsonEncoder::decode((string)$response->getBody(), Perfil::class);
    }

    /**
     * @return Perfil
     */
    public function getMine()
    {
        $this->requiresOwnerAccessToken();
        $response = $this->protectedGet("/cvu/my/perfil");
        return JsonEncoder::decode((string)$response->getBody(), Perfil::class);
    }
}
