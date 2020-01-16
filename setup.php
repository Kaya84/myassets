<?php
/*
 -------------------------------------------------------------------------
 myassets plugin for GLPI
 Copyright (C) 2020 by the myassets Development Team.

 https://github.com/pluginsGLPI/myassets
 -------------------------------------------------------------------------

 LICENSE

 This file is part of myassets.

 myassets is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 myassets is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with myassets. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

define('PLUGIN_MYASSETS_VERSION', '0.0.1');

/**
 * Init hooks of the plugin.
 * REQUIRED
 *
 * @return void
 */
function plugin_init_myassets() {
   global $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['csrf_compliant']['myassets'] = true;


  Plugin::registerClass('PluginMyassetsMyassets', [
      'addtabon' => ['Assets', 'Profile']
   ]);

    $PLUGIN_HOOKS["menu_toadd"]['myassets'] = array('assets'  => 'PluginMyassetsMyassets');
    $PLUGIN_HOOKS['config_page']['myassets'] = 'front/index.php';

   $_SESSION["glpi_plugin_myassets_profile"]['myassets'] = 'w';
}


/**
 * Get the name and the version of the plugin
 * REQUIRED
 *
 * @return array
 */
function plugin_version_myassets() {
   return [
      'name'           => 'myassets',
      'version'        => PLUGIN_MYASSETS_VERSION,
      'author'         => '<a href="http://www.comune.rovereto.tn.it">Comune di Rovereto</a>',
      'license'        => '',
      'homepage'       => '',
      'requirements'   => [
         'glpi' => [
            'min' => '9.2',
         ]
      ]
   ];
}

/**
 * Check pre-requisites before install
 * OPTIONNAL, but recommanded
 *
 * @return boolean
 */
function plugin_myassets_check_prerequisites() {

   //Version check is not done by core in GLPI < 9.2 but has to be delegated to core in GLPI >= 9.2.
   $version = preg_replace('/^((\d+\.?)+).*$/', '$1', GLPI_VERSION);
   if (version_compare($version, '9.2', '<')) {
      echo "This plugin requires GLPI >= 9.2";
      return false;
   }
   return true;
}

/**
 * Check configuration process
 *
 * @param boolean $verbose Whether to display message on failure. Defaults to false
 *
 * @return boolean
 */
function plugin_myassets_check_config($verbose = false) {
   if (true) { // Your configuration check
      return true;
   }

   if ($verbose) {
      echo __('Installed / not configured', 'myassets');
   }
   return false;
}
