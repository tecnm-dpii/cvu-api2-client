<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Escolares;

use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Client\CvuApi2Client;
use TecNM_DPII\CvuApi2Core\Models\Estudiante;

class EstudiantesResource extends AbstractResource
{
    public function __construct(?CvuApi2Client $client = null)
    {
        parent::__construct($client);
    }

    /**
     * @param string $numControl
     * @return Estudiante
     */
    public function getByNumControl(string $numControl)
    {
        $response = $this->protectedGet("/escolares/estudiantes/@num-control/{$numControl}");
        $data = json_decode($response->getBody(), true);
        $row = reset($data) ?: null;
        return is_object($row) ? static::cast($row, Estudiante::class) : null;
    }

    /**
     * @param string $curp
     * @return Estudiante
     */
    public function getByCurp(string $curp)
    {
        $response = $this->protectedGet("/escolares/estudiantes/@curp/{$curp}");
        $data = json_decode($response->getBody(), true);
        $row = reset($data) ?: null;
        return is_object($row) ? static::cast($row, Estudiante::class) : null;
    }
}
