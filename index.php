<?php

load(
    [
        'hananils\\tiles\\vectortiles' => 'lib/VectorTiles.php'
    ],
    __DIR__
);

use Hananils\Tiles\VectorTiles;

function tiles($path)
{
    $path = explode('/', $path);
    $name = array_pop($path);

    $tiles = new VectorTiles([
        'id' => implode('/', $path),
        'name' => $name
    ]);

    return $tiles;
}

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
                $page = page($id);

                if (!$page) {
                    return false;
                }

                $path = sprintf('%s/%s.json', $page->root(), $name);

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
                $page = page($id);

                if (!$page) {
                    return false;
                }

                if ($num = $page->num()) {
                    $id = $num . '_' . $id;
                }

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
