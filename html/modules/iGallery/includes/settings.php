<?php
/*      iGallery for RavenNuke: index.php
 *      Copyright 2009 Jonathan Estrella <jestrella04@gmail.com>
 * 		Join me at http://slaytanic.sourceforge.net
 *
 *      This program is free software; you can redistribute it and/or modify
 *      it under the terms of the GNU General Public License as published by
 *      the Free Software Foundation; either version 2 of the License, or
 *      (at your option) any later version.
 *
 *      This program is distributed in the hope that it will be useful,
 *      but WITHOUT ANY WARRANTY; without even the implied warranty of
 *      MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *      GNU General Public License for more details.
 *
 *      You should have received a copy of the GNU General Public License
 *      along with this program; if not, write to the Free Software
 *      Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 *      MA 02110-1301, USA.
 */

defined('IN_IGM') OR die();
global $db, $prefix;
$ver = '0.9.9 (aka 1.0 RC)';
$result = $db->sql_query('SELECT setting_name, setting_value FROM '.$prefix.'_igallery_settings');
while (list($setting_name, $setting_value) = $db->sql_fetchrow($result)) {
	$iConfig[$setting_name] = $setting_value;
}

if(empty($iConfig['tooltip_theme']) || !file_exists('modules/'.$moduleName.'/includes/jquery/jquery.tooltip/themes/'.$iConfig['tooltip_theme'].'/jquery.tooltip.css')) {
	$iConfig['tooltip_theme'] = 'RavenIce';
}
?>