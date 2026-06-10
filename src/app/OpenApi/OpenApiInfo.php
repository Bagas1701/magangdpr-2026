<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: 'SIMALEX API Documentation',
    version: '1.0.0',
    description: 'Dokumentasi REST API SIMALEX untuk pengajuan aspirasi, tracking aspirasi, kategori aspirasi, dan statistik dashboard.'
)]
#[OA\Server(
    url: 'https://magangdpr.test',
    description: 'Local Development Server'
)]
class OpenApiInfo
{
}