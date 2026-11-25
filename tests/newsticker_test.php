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
 * Tests for the news ticker.
 *
 * @package    local_newsticker
 * @copyright  2026 TAG Digital Solutions Ltd <https://tandreig.com>
 * @author     Andrei Toma
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_newsticker;

use core\hook\output\before_standard_top_of_body_html_generation;
use local_newsticker\local\hook_callbacks;

/**
 * Tests for settings, output and privacy.
 *
 * @covers \local_newsticker\local\hook_callbacks
 */
final class newsticker_test extends \advanced_testcase {
    /**
     * Test that disabled and empty messages produce no ticker.
     */
    public function test_disabled_and_empty_messages(): void {
        $this->resetAfterTest();
        $hook = new before_standard_top_of_body_html_generation($this->createMock(\renderer_base::class));
        set_config('enabled', 0, 'local_newsticker');
        set_config('message', 'Example', 'local_newsticker');
        hook_callbacks::before_standard_top_of_body_html_generation($hook);
        $this->assertSame('', $hook->get_output());
        set_config('enabled', 1, 'local_newsticker');
        set_config('message', '  ', 'local_newsticker');
        hook_callbacks::before_standard_top_of_body_html_generation($hook);
        $this->assertSame('', $hook->get_output());
    }

    /**
     * Test rendered text, escaping, close button and setting fallbacks.
     */
    public function test_output_escapes_message_and_uses_settings(): void {
        $this->resetAfterTest();
        set_config('enabled', 1, 'local_newsticker');
        set_config('message', '<img src=x onerror="alert(1)"> & hello', 'local_newsticker');
        set_config('speed', -1, 'local_newsticker');
        set_config('showclose', 1, 'local_newsticker');
        $hook = new before_standard_top_of_body_html_generation($this->createMock(\renderer_base::class));
        hook_callbacks::before_standard_top_of_body_html_generation($hook);
        $html = $hook->get_output();
        $this->assertStringNotContainsString('<img src=x', $html);
        $this->assertStringContainsString('&lt;img src=x', $html);
        $this->assertStringContainsString('&amp; hello', $html);
        $this->assertStringContainsString('--local-newsticker-speed: 25s', $html);
        $this->assertStringContainsString('local-newsticker-close', $html);
        set_config('showclose', 0, 'local_newsticker');
        set_config('speed', 42, 'local_newsticker');
        $hook = new before_standard_top_of_body_html_generation($this->createMock(\renderer_base::class));
        hook_callbacks::before_standard_top_of_body_html_generation($hook);
        $this->assertStringNotContainsString('class="local-newsticker-close"', $hook->get_output());
        $this->assertStringContainsString('--local-newsticker-speed: 42s', $hook->get_output());
    }

    /**
     * Only administrators can access the settings page by default.
     */
    public function test_settings_and_permissions(): void {
        global $CFG;
        require_once($CFG->libdir . '/adminlib.php');
        $this->resetAfterTest();
        $this->setAdminUser();
        $root = admin_get_root(true, false);
        $page = $root->locate('local_newsticker');
        $this->assertInstanceOf(\admin_settingpage::class, $page);
        $this->assertTrue($page->check_access());
        foreach (['enabled', 'message', 'backgroundcolour', 'textcolour', 'speed', 'showclose'] as $name) {
            $this->assertObjectHasProperty('local_newsticker' . $name, $page->settings);
        }
        $this->setUser($this->getDataGenerator()->create_user());
        $this->assertFalse($page->check_access());
    }

    /**
     * The privacy provider declares no personal data and returns the string identifier.
     *
     * @covers \local_newsticker\privacy\provider
     */
    public function test_privacy_provider(): void {
        $this->assertInstanceOf(\core_privacy\local\metadata\null_provider::class, new privacy\provider());
        $this->assertSame('privacy:metadata', privacy\provider::get_reason());
        $this->assertTrue(get_string_manager()->string_exists(privacy\provider::get_reason(), 'local_newsticker'));
    }
}
