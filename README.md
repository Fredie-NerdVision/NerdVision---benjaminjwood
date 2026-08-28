# NerdVision---benjaminjwood

Two WordPress themes for composer / producer / audio engineer **Benjamin J. Wood**. Both carry identical
copy and the same tandem A/B audio player; only the visual direction differs.

| Theme | Slug | Look |
| --- | --- | --- |
| BJW Obsidian | `themes/bjw-obsidian` | Premium obsidian + gold marble (refined `ben_site.html`) |
| BJW Studio | `themes/bjw-studio` | Warm copper / walnut / moss, console + rack inspired |

## Tandem A/B player

Each track holds two audio files that play on one shared playhead, so version A and version B stay in
sync. Visitors can mute, solo, gain-trim or crossfade either side, and the sticky transport bar handles
play/pause, prev/next, seeking and quick A/B switching. Both files for a track must be the same length.

Tracks are managed in wp-admin under **Tracks** (`bjw_track` post type). Each track stores a label and
audio file for version A and B plus an artist name. With no tracks published the theme falls back to
demo tracks defined in `inc/tracks.php`.

## Editing content (ACF)

Field groups are registered in code (`inc/acf.php`), so nothing has to be rebuilt in the ACF UI — the
themes ship ready to edit:

- **Tracks** — Advanced Custom Fields renders the tandem audio fields (file pickers for the two
  renders). Without ACF, an equivalent native meta box is shown instead, so the site never depends on
  the plugin.
- **Site Content** — an ACF Pro options page covering the hero, about, services, A/B room, portfolio
  and contact copy, including repeaters for the service strips and portfolio entries and the inquiry
  recipient address. Every value falls back to the copy baked into the templates when it is empty or
  ACF Pro is not installed.

## Installing

```bash
cd themes && zip -r bjw-obsidian.zip bjw-obsidian -x '*/dev/*'
```

Upload the zip via **Appearance → Themes → Add New → Upload Theme**, activate it, then create a page,
set it as the static front page (Settings → Reading) — the theme renders the one-page layout from
`front-page.php`.

Contact form submissions go through `admin-post.php` and are emailed with `wp_mail()` to the site admin
address.

## Local design preview (no WordPress)

Each theme ships a small stub harness so the layout can be rendered by PHP's built-in server:

```bash
cd themes/bjw-obsidian && php -S localhost:8081 -t . dev/router.php
cd themes/bjw-studio   && php -S localhost:8082 -t . dev/router.php
```

The `dev/` directory is preview-only and can be excluded when packaging for production.
