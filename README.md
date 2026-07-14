# VIG Contact Bar

A floating multi-channel contact bar for WordPress by [VIG Digital](https://vigdigital.com) — Tawk.to live chat, WhatsApp, and a contact form, responsive on desktop & mobile.

> Thanh liên hệ nổi: Chat Tawk.to, WhatsApp và Contact Form (shortcode). Responsive Desktop & Mobile.

## Features

- **Floating contact bar** with collapse/expand and optional hide-text (per desktop/mobile).
- **Tawk.to integration** — hides Tawk's default bubble and drives it from the bar's own button.
- **WhatsApp** click-to-chat link.
- **Contact form** via shortcode (drop any form builder's shortcode in settings).
- **Multilingual** — channel titles registered with Polylang string translation.
- **VIG Toolkit** shared admin menu (Dashboard + Guideline docs).

Configure under **WP Admin → VIG Toolkit → Contact Bar**.

## Requirements

- WordPress 6.0+
- PHP 7.4+
- Optional: Polylang (for multilingual titles), a Tawk.to account (for live chat)

## Installation

1. Download the latest `vig-contact-bar.zip` from [Releases](https://github.com/vigdigital/vig-contact-bar/releases).
2. WP Admin → **Plugins → Add New → Upload Plugin** → choose the zip → **Install** → **Activate**.

> Do **not** use GitHub's green "Download ZIP" button — its folder name is wrong for WordPress. Always use the release asset above (or let auto-update handle it).

## Updates

Self-update is powered by [Plugin Update Checker](https://github.com/YahnisElsts/plugin-update-checker) reading this repo's GitHub Releases. When a new release is tagged, WordPress shows a normal **Update** prompt under Plugins.

Because this repo is **private**, each site must authorise access via `wp-config.php`:

```php
// ⚠️ Quote the token string. Give the token only `repo` scope.
define( 'VIG_GH_TOKEN', 'ghp_xxxxxxxxxxxxxxxxxxxx' );
```

(Public repos / a VIG update server do not need this.)

## Releasing (maintainers)

Tagging a version automatically builds a clean zip and attaches it to the release — see [`.github/workflows/build-release.yml`](.github/workflows/build-release.yml).

```bash
# bump Version: in vig-contact-bar.php, commit, then:
git tag v1.1.0
git push origin v1.1.0
```

## License

GPL-2.0-or-later © VIG Digital
