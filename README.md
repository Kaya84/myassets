# Plugin GLPI: testmenu

Aggiunge una voce "I MIEI DISPOSITIVI" nel menu dell'interfaccia semplificata (Helpdesk) di GLPI 10.x.

## Struttura

```
testmenu/
├── setup.php          # Punto di ingresso obbligatorio del plugin
├── hook.php           # Hook per il menu helpdesk
├── front/
│   └── index.php       # Pagina puntata dalla voce "TEST"
├── inc/
│   └── install.php    # Logica di installazione/disinstallazione
└── locale/            # File di traduzione (opzionale)
```

## Installazione

1. Copia la cartella `testmenu/` in:
   ```
   /var/www/html/glpi/plugins/testmenu/
   ```

2. Accedi a GLPI come amministratore.

3. Vai su **Configurazione → Plugin**.

4. Trova **Test Menu** nell'elenco e clicca **Installa**, poi **Attiva**.

5. Accedi con un utente in **interfaccia semplificata** (Helpdesk): nel menu in alto apparirà la voce "I MIEI DISPOSITIVI" che punta a `front/index.php`.

## Compatibilità

- GLPI 10.0.x – 10.9.x
- PHP 7.4+

## Personalizzazione

Per modificare il contenuto della pagina, edita:
```
front/index.php
```

Per cambiare icona o titolo della voce menu, edita la funzione
`plugin_myassets_helpdesk_menu_entry()` in `hook.php`.

Le icone disponibili sono quelle di [Tabler Icons](https://tabler.io/icons)
(usate nativamente da GLPI 10), con il prefisso `ti ti-`.
