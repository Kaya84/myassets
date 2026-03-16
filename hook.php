<?php

/**
 * Hook per l'interfaccia semplificata (Helpdesk)
 * Aggiunge la voce "TEST" nel menu principale
 */

/**
 * Aggiunge la voce TEST al menu dell'interfaccia semplificata.
 * Questa funzione viene chiamata da GLPI tramite il hook
 * 'helpdesk_menu_entry'.
 *
 * @return array
 */
function plugin_myassets_helpdesk_menu_entry() {
    return [
	    'default'   => '/plugins/myplugin/front/model.php',
        'title' => __('MY ', 'myassets'),
        'icon'  => 'ti ti-test-pipe', // Icona Tabler Icons (usata da GLPI 10)
        'page'  => Plugin::getWebDir('myassets') . '/front/test.php',
		            'content'   => [true]

    ];
}
/**
 * Installazione del plugin myassets
 */
function plugin_myassets_install() {
    // Nessuna tabella DB necessaria per questo plugin
    return true;
}

/**
 * Disinstallazione del plugin myassets
 */
function plugin_myassets_uninstall() {
    // Nessuna tabella DB da rimuovere
    return true;
}

function plugin_myplugin_redefine_menus($menu) {
    if (empty($menu)) {
        return $menu;
    }

//    if (array_key_exists('myplugin', $menu) === false && $_SESSION['glpiactiveprofile']['interface'] == 'helpdesk') {
        $menu['myplugin'] = [
            'default'   => Plugin::getWebDir('myassets') . '/front/index.php',
            'title'     => __('I miei dispositivi', 'myassets'),
			'icon'  => 'ti ti-building-store', // Icona Tabler Icons (usata da GLPI 10)
            'content'   => [true]
        ];
  //  }

    return $menu;
}