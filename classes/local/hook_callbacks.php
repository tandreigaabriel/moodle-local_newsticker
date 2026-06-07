<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 * Hook callback handlers for local_newsticker.
 *
 * @package    local_newsticker
 * @copyright  2026 Andrei Toma
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_newsticker\local;

/**
 * Callbacks for Moodle output hooks.
 *
 * @package    local_newsticker
 * @copyright  2026 Andrei Toma
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {

    /**
     * Inject ticker HTML near the top of the body.
     *
     * On Boost the template places standard_top_of_body_html before the navbar, so JS moves
     * the ticker into #topofscroll just before #page-header (below the fixed navbar).
     * On Adaptable the template places standard_top_of_body_html between the nav and the page
     * content, so the ticker renders in the correct position without any JS repositioning.
     *
     * @param \core\hook\output\before_standard_top_of_body_html_generation $hook
     * @return void
     */
    public static function before_standard_top_of_body_html_generation(
        \core\hook\output\before_standard_top_of_body_html_generation $hook
    ): void {
        if (during_initial_install()) {
            return;
        }

        $enabled = get_config('local_newsticker', 'enabled');
        if (empty($enabled)) {
            return;
        }

        $message = trim((string) get_config('local_newsticker', 'message'));
        if ($message === '') {
            return;
        }

        $background = get_config('local_newsticker', 'backgroundcolour') ?: '#0f172a';
        $textcolour = get_config('local_newsticker', 'textcolour') ?: '#ffffff';

        $speed = (int) get_config('local_newsticker', 'speed');
        $speed = $speed > 0 ? $speed : 25;

        $showclose = (int) get_config('local_newsticker', 'showclose');

        $hook->add_html(self::render_ticker($message, $background, $textcolour, $speed, $showclose));
    }

    /**
     * Render ticker HTML and inline JS.
     *
     * @param string $message
     * @param string $background
     * @param string $textcolour
     * @param int    $speed
     * @param int    $showclose
     * @return string
     */
    private static function render_ticker(
        string $message,
        string $background,
        string $textcolour,
        int $speed,
        int $showclose
    ): string {
        $message    = s($message);
        $background = s($background);
        $textcolour = s($textcolour);

        $closebutton = '';
        if ($showclose) {
            $closebutton = '
                <button type="button"
                    class="local-newsticker-close"
                    aria-label="Close announcement"
                    onclick="
                        var w = document.querySelector(\'.local-newsticker-wrapper\');
                        if (w) { w.style.display = \'none\'; }
                        sessionStorage.setItem(\'local_newsticker_closed\', \'1\');
                    ">
                    &times;
                </button>';
        }

        // JS repositioning strategy:
        // standard_top_of_body_html lands at the very top of <body> on all themes, before any
        // visual chrome. We need to move the ticker into the correct visual position per theme.
        // - Boost: move into #topofscroll just before #page-header (below the fixed navbar).
        // - All other themes (Adaptable, etc.): move after nav.navbar so it sits between the
        //   site navigation bar and the page content.
        return '
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    var ticker = document.querySelector(".local-newsticker-wrapper");
                    if (!ticker) return;

                    if (sessionStorage.getItem("local_newsticker_closed") === "1") {
                        return;
                    }

                    var boostHeader = document.querySelector("#topofscroll #page-header") ||
                                      document.querySelector("#page.drawers #page-header");
                    if (boostHeader && boostHeader.parentNode) {
                        boostHeader.parentNode.insertBefore(ticker, boostHeader);
                        ticker.classList.add("local-newsticker-ready");
                        return;
                    }

                    // Adaptable: #page.drawers has margin-top to push content below the fixed
                    // header. Insert ticker before #maincontainer so it sits at the top of
                    // the content area, below the fixed header, above the breadcrumb/content.
                    var adaptableMain = document.querySelector("#maincontainer");
                    if (adaptableMain && adaptableMain.parentNode) {
                        adaptableMain.parentNode.insertBefore(ticker, adaptableMain);
                        ticker.classList.add("local-newsticker-ready");
                        return;
                    }

                    ticker.classList.add("local-newsticker-ready");
                });
            </script>

            <div class="local-newsticker-wrapper"
                style="
                    --local-newsticker-bg: ' . $background . ';
                    --local-newsticker-text: ' . $textcolour . ';
                    --local-newsticker-speed: ' . $speed . 's;
                ">
                <div class="local-newsticker-label">Announcement</div>

                <div class="local-newsticker-track" aria-live="polite">
                    <div class="local-newsticker-content">
                        <span>' . $message . '</span>
                        <span>' . $message . '</span>
                        <span>' . $message . '</span>
                    </div>
                </div>

                ' . $closebutton . '
            </div>
        ';
    }
}
