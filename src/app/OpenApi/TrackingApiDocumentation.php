<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Get(
    path: '/api/v1/tracking/{ticket_number}',
    summary: 'Tracking aspirasi berdasarkan nomor tiket',
    description: 'Endpoint untuk mengambil detail aspirasi berdasarkan nomor tiket.',
    tags: ['Tracking'],
    parameters: [
        new OA\Parameter(
            name: 'ticket_number',
            in: 'path',
            required: true,
            description: 'Nomor tiket aspirasi',
            schema: new OA\Schema(type: 'string', example: 'ASP-2026-0006')
        ),
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: 'Data aspirasi berhasil ditemukan',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'success', type: 'boolean', example: true),
                    new OA\Property(
                        property: 'data',
                        type: 'object',
                        properties: [
                            new OA\Property(property: 'ticket_number', type: 'string', example: 'ASP-2026-0006'),
                            new OA\Property(property: 'judul', type: 'string', example: 'Test API SIMALEX'),
                            new OA\Property(property: 'status', type: 'string', example: 'Masuk'),
                            new OA\Property(property: 'kategori', type: 'string', example: 'Pidana'),
                            new OA\Property(property: 'attachments_count', type: 'integer', example: 0),
                        ]
                    ),
                ]
            )
        ),
        new OA\Response(
            response: 404,
            description: 'Nomor tiket tidak ditemukan'
        ),
    ]
)]
class TrackingApiDocumentation
{
}