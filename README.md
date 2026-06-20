News Ticker for Moodle
local_newsticker
Maintained by Andrei Toma

A Moodle local plugin that displays a site-wide scrolling announcement ticker below the main navigation, above the page content. Ideal for broadcasting important notices, maintenance windows, or any time-sensitive message to all users across the site.
Features

    Full-width scrolling ticker bar displayed below the main navigation on every page

    Configurable message, background colour, text colour, and scroll speed

    Pauses automatically on hover so users can read the full message

    Optional close button — users can dismiss the ticker for their current browser session

    Dismissed state persists via sessionStorage and resets when the browser session ends

    Responsive — the "Announcement" label is hidden on small screens to maximise readable space

    Works with Boost and Adaptable themes out of the box

    No database tables — zero install footprint beyond the plugin files

    Lightweight — pure CSS animation, no external dependencies

Requirements

    Moodle 4.5 or later

    PHP 8.1 or later

Installation

1. Download the plugin

Download the latest release ZIP from the Moodle plugins directory or from the GitHub releases page.

2. Install in Moodle

Option A — Via the Moodle admin interface (recommended)

    Log in as admin

    Go to Site Administration → Plugins → Install plugins

    Upload the ZIP file

    Click Install plugin from the ZIP file

    Follow the on-screen prompts and click Upgrade Moodle database now

Option B — Manual installation

    Unzip the plugin

    Copy the newsticker folder to <moodleroot>/local/newsticker/

    The folder must be named newsticker — NOT local_newsticker

    Log in as admin

    Go to Site Administration → Notifications

    Click Upgrade Moodle database now

Configuration

    Go to Site Administration → Plugins → Local plugins → News Ticker

    Enable the plugin and fill in your settings:

Setting Description
Enable news ticker Show or hide the ticker across the entire site
Ticker message The announcement text displayed in the scrolling ticker
Background colour CSS colour value for the ticker bar (e.g. #0f172a)
Text colour CSS colour value for the ticker text (e.g. #ffffff)
Scroll speed Animation duration in seconds — lower number = faster (default: 25)
Show close button Allow users to dismiss the ticker for their current browser session

    Save changes. The ticker appears immediately on every page — no cache purge required for content changes.

Theme Support

The plugin positions the ticker correctly on the Boost and Adaptable themes. Other themes are supported on a best-effort basis — the ticker will be visible on the page even if the exact position differs from Boost and Adaptable.
Troubleshooting
Ticker not showing at all

    Confirm the plugin is enabled under Site Administration → Plugins → Local plugins → News Ticker

    Confirm the Ticker message field is not empty

    Purge caches: Site Administration → Development → Purge all caches, then hard-refresh the browser (Ctrl+Shift+R)

Ticker appears in the wrong position

    Purge caches and do a hard refresh (Ctrl+Shift+R)

    If you are using a custom or third-party theme other than Boost or Adaptable, the JS insertion point may need adjusting — open a GitHub issue with your theme name

Ticker shows but does not scroll

    Confirm styles.css exists in the plugin root — it may have been excluded during a manual install

    Check the browser console for CSS errors

Close button does not stay dismissed between visits

    This is by design — the dismissed state is stored in sessionStorage, which clears when the browser tab or window is closed. The ticker reappears on the user's next session.

Support

Developed and maintained by Andrei Toma.

    GitHub repository: tandreigaabriel/moodle-local_newsticker

    Bug reports and feature requests: open an issue on GitHub

    Moodle plugins directory: moodle.org/plugins/local_newsticker

    Website: tandreig.com/plugins
License

This plugin is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or any later version.

Copyright © 2026 Andrei Toma
Changelog
1.0.0

    Initial release

    Scrolling ticker with configurable message, colours, and speed

    Optional close button with sessionStorage persistence

    Boost and Adaptable theme support via Moodle Hooks API

    GitHub Actions CI pipeline (PHPCS, Validate, Mustache, Grunt)
