<?php

namespace Hananils\Tiles;

use Kirby\Database\Database;
use Kirby\Database\Query;
use Kirby\Data\Data;
use Exception;

class VectorTiles
{
    private $id;
    private $name;
    private $urls;
    private $content;
    private $path;
    private $modified;
    private $database;

    private $type = 'application/x-protobuf';
    private $status = 200;
    private $headers = [];

    public function __construct($options)
    {
        $this->id = $options['id'];
        $this->name = $options['name'];
        $this->urls = $options['urls'];
        $this->content = $options['content'];

        $this->path = $this->toTilesPath();
        $this->modified = filemtime($this->path);
        $this->database = new Database([
            'type' => 'sqlite',
            'database' => $this->path
        ]);
    }

    public function getInfo()
    {
        $meta = $this->queryMetadata();
        $tilejson = [
            'tilejson' => '2.2.0',
            'name' => 'OpenStreetMap',
            'description' => $meta['description'],
            'version' => $meta['version'],
            'attribution' => $meta['attribution'],
            'scheme' => 'xyz',
            'tiles' => $this->toTilesUrls(),
            'minzoom' => floatval($meta['minzoom'] ?? 0),
            'maxzoom' => floatval($meta['maxzoom'] ?? 22),
            'bounds' => array_map('floatval', explode(',', $meta['bounds']))
        ];

        return Data::encode($tilejson, 'json');
    }

    /**
     * When using Mapbox GL, keep in mind to set the source scheme to "tms"
     * to get the correct y coordinates.
     */
    public function getTile(float $z, float $x, float $y)
    {
        $result = $this->database->query(
            'select tile_data from tiles where
                zoom_level = :z and
                tile_column = :x and
                tile_row = :y',
            [
                ':z' => $z,
                ':x' => $x,
                ':y' => $y
            ],
            [
                'iterator' => 'array'
            ]
        );

        $this->headers = [
            'Last-Modified' => gmdate('r', $this->modified),
            'Etag' => md5($this->modified)
        ];

        if (empty($result)) {
            $this->status = 204;
            $this->type = 'text/plain';

            return '';
        }

        $this->status = 200;
        $this->type = 'application/x-protobuf';
        $this->headers['Content-Encoding'] = 'gzip';

        return $result[0]->tile_data();
    }

    public function getType()
    {
        return $this->type;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function getEtag()
    {
        return $this->etag;
    }

    private function queryMetadata()
    {
        $query = new Query($this->database, 'metadata');
        $query->iterator('array');

        $meta = [];
        foreach ($query->all() as $row) {
            $meta[$row->name()] = $row->value();
        }

        return $meta;
    }

    public function isModified()
    {
        $matches = isset($_SERVER['HTTP_IF_NONE_MATCH'])
            ? $_SERVER['HTTP_IF_NONE_MATCH']
            : null;
        $since = isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])
            ? strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE'])
            : null;

        if ($matches) {
            $etag = md5($this->modified);

            foreach (split(', ', $matches) as $tag) {
                if ($tag == $etag) {
                    return true;
                }
            }
        }

        return !$since || $this->modified < $since;
    }

    public function toTilesUrls()
    {
        $urls = [];

        foreach ($this->urls as $url) {
            $urls[] =
                $url .
                '/' .
                $this->id .
                '/vector/' .
                $this->name .
                '/{z}/{x}/{y}.pbf';
        }

        return $urls;
    }

    public function toTilesPath()
    {
        return sprintf(
            '%s/%s/%s.mbtiles',
            kirby()->root('content'),
            $this->id,
            $this->name
        );
    }
}
