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
 * Admin settings for the news ticker plugin.
 *
 * @package    local_newsticker
 * @copyright  2026 Andrei Toma
 * @license    https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settings = new admin_settingpage(
        'local_newsticker',
        get_string('pluginname', 'local_newsticker')
    );

    $ADMIN->add('localplugins', $settings);

    $settings->add(new admin_setting_configcheckbox(
        'local_newsticker/enabled',
        get_string('enabled', 'local_newsticker'),
        get_string('enabled_desc', 'local_newsticker'),
        0
    ));

    $settings->add(new admin_setting_configtextarea(
        'local_newsticker/message',
        get_string('message', 'local_newsticker'),
        get_string('message_desc', 'local_newsticker'),
        'Important announcement: Please check the latest updates.',
        PARAM_TEXT
    ));

    $settings->add(new admin_setting_configtext(
        'local_newsticker/backgroundcolour',
        get_string('backgroundcolour', 'local_newsticker'),
        get_string('backgroundcolour_desc', 'local_newsticker'),
        '#0f172a',
        PARAM_RAW_TRIMMED
    ));

    $settings->add(new admin_setting_configtext(
        'local_newsticker/textcolour',
        get_string('textcolour', 'local_newsticker'),
        get_string('textcolour_desc', 'local_newsticker'),
        '#ffffff',
        PARAM_RAW_TRIMMED
    ));

    $settings->add(new admin_setting_configtext(
        'local_newsticker/speed',
        get_string('speed', 'local_newsticker'),
        get_string('speed_desc', 'local_newsticker'),
        '25',
        PARAM_INT
    ));

    $settings->add(new admin_setting_configcheckbox(
        'local_newsticker/showclose',
        get_string('showclose', 'local_newsticker'),
        get_string('showclose_desc', 'local_newsticker'),
        1
    ));
}
