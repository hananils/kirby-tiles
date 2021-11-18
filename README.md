![Kirby Tiles](.github/title.png)

**Tiles** is a plugin for [Kirby 3](https://getkirby.com) to serve basic vector map tiles (`.mbtiles`). The plugin provides styles, tilejson and vector tiles that can be used with with [Maplibre](https://maplibre.org/maplibre-gl-js-docs/api/) or similar.

## Installation

### Download

Download and copy this repository to `/site/plugins/tiles`.

### Git submodule

```
git submodule add https://github.com/hananils/kirby-tiles.git site/plugins/tiles
```

### Composer

```
composer require hananils/kirby-tiles
```

# File storage and routes

Upload your map style and your tiles using Kirby's files fields. The following routes are provided:

## Styles

`/tiles/{{ parent.page }}/styles/{{ filename }}.json`

## TileJSON

`/tiles/{{ parent.page }}/tilejson/{{ filename }}.json`

## Vector tiles

`/tiles/{{ parent.page }}/vector/{{ filename }}/{{ z }}/{{ x }}/{{ y }}.pbf`

# Creating styles and tiles

More information on how to create styles and generate tyles can be found at <https://openmaptiles.org>.

# License

This plugin is provided freely under the [MIT license](LICENSE.md) by [hana+nils · Büro für Gestaltung](https://hananils.de).  
We create visual designs for digital and analog media.
