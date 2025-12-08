<?php

require_once dirname(__FILE__, 3) . '/vendor/autoload.php';

use TecNM_DPII\CvuApi2Client\Resources\Catalogos\AreasConocimientoResource;
use TecNM_DPII\CvuApi2Client\Resources\CvuAdmin\PerfilesResource;

$acRes = new AreasConocimientoResource();
$areas = $acRes->getAll();
print_r($areas);

$perfilesRes = new PerfilesResource();
$perfil = $perfilesRes->getById(1);
print_r($perfil);
$perfil = $perfilesRes->getByCvuTecnm('IT15A001');
print_r($perfil);
$perfil = $perfilesRes->getByCurp('CEZF911109HCMRMR08');
print_r($perfil);
$perfil = $perfilesRes->getByRfc('CEZF911109RQ9');
print_r($perfil);
