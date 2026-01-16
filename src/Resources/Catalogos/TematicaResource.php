<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Catalogos;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Catalogos\AreaTematica;
use TecNM_DPII\CvuApi2Core\Models\Catalogos\DisciplinaTematica;

class TematicaResource extends AbstractResource
{
    /**
     * @return AreaTematica[]
     */
    public function getAreas()
    {
        $response = $this->protectedGet('/catalogos/tematica-areas');
        return JsonEncoder::decode((string)$response->getBody(), AreaTematica::class);
    }

    /**
     * @return DisciplinaTematica[]
     */
    public function getDisciplinas()
    {
        $response = $this->protectedGet('/catalogos/tematica-disciplinas');
        return JsonEncoder::decode((string)$response->getBody(), DisciplinaTematica::class);
    }
}
