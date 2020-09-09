<?php

load(
    [
        'hananils\\tiles\\vectortiles' => 'lib/VectorTiles.php'
    ],
    __DIR__
);

use Hananils\Tiles\VectorTiles;

Kirby::plugin('hananils/tiles', [
    'options' => [
        'urls' => [kirby()->url() . '/tiles']
    ],
    'routes' => [
        /**
         * Styles
         */
        [
            'pattern' => ['tiles/(:all)/styles/(:any).json'],
            'action' => function ($id, $name) {
                $path = sprintf(
                    '%s/%s/%s.json',
                    kirby()->root('content'),
                    $id,
                    $name
                );

                if ($json = F::read($path)) {
                    return new Response($json, 'application/json');
                }

                return false;
            }
        ],

        /**
         * TileJSON
         */
        [
            'pattern' => ['tiles/(:all)/tilejson/(:any).json'],
            'action' => function ($id, $name) {
                $tiles = new VectorTiles([
                    'id' => $id,
                    'name' => $name
                ]);

                if ($tiles->exists()) {
                    return new Response($tiles->getInfo(), 'application/json');
                }

                return false;
            }
        ],

        /**
         * Vector Tiles
         */
        [
            'pattern' => [
                'tiles/(:all)/vector/(:any)/(:num)/(:num)/(:num).pbf'
            ],
            'action' => function ($id, $name, $z, $x, $y) {
                $tiles = new VectorTiles([
                    'id' => $id,
                    'name' => $name
                ]);

                if (!$tiles->exists()) {
                    return false;
                }

                if (!$tiles->isModified()) {
                    return new Response('', 'text/plain', 304);
                }

                return new Response(
                    $tiles->getTile($z, $x, $y),
                    $tiles->getType(),
                    $tiles->getStatus(),
                    $tiles->getHeaders(),
                    'utf-8'
                );
            }
        ]
    ]
]);
