<?php

namespace TecNM_DPII\CvuApi2Client\Resources\Cvu;

use Francerz\JsonTools\JsonEncoder;
use TecNM_DPII\CvuApi2Client\AbstractResource;
use TecNM_DPII\CvuApi2Core\Models\Cvu\Producto;

class ProductosResource extends AbstractResource
{
    /**
     * Recupera todos los productos registrados a partir del filtro definido.
     *
     * @param array $params
     * @return Producto[]
     */
    public function getAll(array $params = [])
    {
        $response = $this->protectedGet('/cvu/productos', $params);
        return JsonEncoder::decode((string)$response->getBody(), Producto::class);
    }

    /**
     * Recupera todos los productos asociados a la cuenta de CVU activa.
     *
     * @return Producto[]
     */
    public function getAllMine()
    {
        $this->requiresOwnerAccessToken();
        $response = $this->protectedGet('/cvu/my/productos');
        return JsonEncoder::decode((string)$response->getBody(), Producto::class);
    }
}
