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

/**
 * Plugin install process
 *
 * @return boolean
 */
function plugin_myassets_install() {


   $config = new Config();
   $config->setConfigurationValues('plugin:Myassets', ['configuration' => false]);

   ProfileRight::addProfileRights(['myassets:read']);



   return true;
}

/**
 * Plugin uninstall process
 *
 * @return boolean
 */
function plugin_myassets_uninstall() {
   return true;
}
