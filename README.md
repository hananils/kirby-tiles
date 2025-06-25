![Kirby Tiles](.github/title.png)

**Tiles** is a plugin for [Kirby 3](https://getkirby.com) to serve basic vector map tiles (`.mbtiles`). The plugin provides routes for styles, tilejson and vector tiles that can be used with [Maplibre](https://maplibre.org/maplibre-gl-js-docs/api/) or similar.

> [!NOTE]
> **There is a large update coming for Kirby 5 this fall:**  
> Please check out [kirby.hananils.de/plugins/tiles](https://kirby.hananils.de/plugins/tiles) for further information.

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
