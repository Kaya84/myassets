<?php

/**
 * Plugin: myassets
 * Aggiunge una voce "TEST" nel menu dell'interfaccia semplificata (Helpdesk)
 */

define('PLUGIN_MYASSETS_VERSION', '1.0.0');
define('PLUGIN_MYASSETS_MIN_GLPI', '11.0.0');
define('PLUGIN_MYASSETS_MAX_GLPI', '11.9.99');

/**
 * Inizializzazione del plugin
 */
function plugin_init_myassets() {
    global $PLUGIN_HOOKS;

    $PLUGIN_HOOKS['csrf_compliant']['myassets'] = true;

    // Hook per modificare il menu dell'interfaccia semplificata
//    $PLUGIN_HOOKS['helpdesk_menu_entry']['myassets'] = true;
	if (Session::getCurrentInterface() === 'helpdesk') {

		$PLUGIN_HOOKS['redefine_menus']['myassets'] = 'plugin_myplugin_redefine_menus';
	}
    // Aggiunge il menu nella navbar dell'interfaccia semplificata
   // $PLUGIN_HOOKS['add_javascript']['myassets'] = [];
    //$PLUGIN_HOOKS['add_css']['myassets'] = [];
}

/**
 * Informazioni sul plugin
 */
function plugin_version_myassets() {
    return [
        'name'           => 'My Assets',
        'version'        => PLUGIN_MYASSETS_VERSION,
        'author'         => 'Comune di Rovereto',
        'license'        => 'GPLv2+',
        'homepage'       => '',
        'requirements'   => [
            'glpi' => [
                'min' => PLUGIN_MYASSETS_MIN_GLPI,
                'max' => PLUGIN_MYASSETS_MAX_GLPI,
            ],
        ],
    ];
}

/**
 * Verifica i prerequisiti per l'installazione
 */
function plugin_myassets_check_prerequisites() {
    if (version_compare(GLPI_VERSION, PLUGIN_MYASSETS_MIN_GLPI, 'lt')
        || version_compare(GLPI_VERSION, PLUGIN_MYASSETS_MAX_GLPI, 'gt')) {
        echo "Questa versione di GLPI non è supportata.";
        return false;
    }
    return true;
}

/**
 * Verifica la configurazione dopo l'installazione
 */
function plugin_myassets_check_config($verbose = false) {
    return true;
}
