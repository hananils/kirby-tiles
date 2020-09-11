# WIP: Kirby Tiles

Kirby 3 plugin to serve basic vector map tiles (`.mbtiles`).
This is work in progress and not ready for production use.

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

## Files

Upload your map style and your tiles using Kirby's files fields. The following routes are proviced:

### Styles

`/tiles/{{ parent.page }}/styles/{{ filename }}.json`

### TileJSON

`/tiles/{{ parent.page }}/tilejson/{{ filename }}.json`

### Vector tiles

`/tiles/{{ parent.page }}/vector/{{ filename }}/{{ z }}/{{ x }}/{{ y }}.pbf`

## License

MIT

## Credits

[hana+nils · Büro für Gestaltung](https://hananils.de)
