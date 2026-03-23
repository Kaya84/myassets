<?php

/**
 * Pagina TEST - Elenco dispositivi collegati all'utente loggato
 */

include('../../../inc/includes.php');

Session::checkLoginUser();

$title = __('I miei dispositivi', 'myassets');

if (Session::getCurrentInterface() === 'helpdesk') {
    Html::helpHeader($title);
} else {
    Html::header($title, $_SERVER['PHP_SELF'], 'plugins', 'myassets');
}

$user_id = (int) Session::getLoginUserID();

// Filtro attivo: 'all' oppure 'inuso' (states_id = 5)
$filter = isset($_GET['status']) && $_GET['status'] === 'all' ? 'all' : '5';

$asset_types = [
    'Computer' => [
        'table' => 'glpi_computers',
        'label' => 'Computer',
        'icon'  => 'ti ti-device-desktop',
    ],
    'Monitor' => [
        'table' => 'glpi_monitors',
        'label' => 'Monitor',
        'icon'  => 'ti ti-device-tv',
    ],
    'NetworkEquipment' => [
        'table' => 'glpi_networkequipments',
        'label' => 'Apparato di rete',
        'icon'  => 'ti ti-network',
    ],
    'Peripheral' => [
        'table' => 'glpi_peripherals',
        'label' => 'Periferica',
        'icon'  => 'ti ti-device-usb',
    ],
    'Phone' => [
        'table' => 'glpi_phones',
        'label' => 'Telefono',
        'icon'  => 'ti ti-phone',
    ],
    'Printer' => [
        'table' => 'glpi_printers',
        'label' => 'Stampante',
        'icon'  => 'ti ti-printer',
    ],
];

global $DB, $CFG_GLPI;

$model_tables = [
    'Computer'         => 'glpi_computermodels',
    'Monitor'          => 'glpi_monitormodels',
    'NetworkEquipment' => 'glpi_networkequipmentmodels',
    'Peripheral'       => 'glpi_peripheralmodels',
    'Phone'            => 'glpi_phonemodels',
    'Printer'          => 'glpi_printermodels',
];
$model_id_fields = [
    'Computer'         => 'computermodels_id',
    'Monitor'          => 'monitormodels_id',
    'NetworkEquipment' => 'networkequipmentmodels_id',
    'Peripheral'       => 'peripheralmodels_id',
    'Phone'            => 'phonemodels_id',
    'Printer'          => 'printermodels_id',
];

$devices = [];
$total   = 0;

foreach ($asset_types as $itemtype => $meta) {
    $table = $meta['table'];

    if (!$DB->tableExists($table)) {
        continue;
    }

    $model_id_key  = $model_id_fields[$itemtype] ?? null;
    $select_fields = ['id', 'name', 'serial', 'otherserial', 'locations_id', 'states_id', 'manufacturers_id'];
    if ($model_id_key) {
        $select_fields[] = $model_id_key;
    }

    $where = [
        'users_id'    => $user_id,
        'is_deleted'  => 0,
        'is_template' => 0
    ];

    if ($filter == '5') {
        $where['states_id'] = 5;
    }

    $iterator = $DB->request([
        'SELECT'  => $select_fields,
        'FROM'    => $table,
        'WHERE'   => $where,
        'ORDERBY' => 'name ASC',
    ]);

    $rows = [];
    foreach ($iterator as $row) {
        $location_name = '';
        if (!empty($row['locations_id'])) {
            $loc = $DB->request(['SELECT' => ['name'], 'FROM' => 'glpi_locations', 'WHERE' => ['id' => $row['locations_id']]]);
            if ($loc_row = $loc->current()) {
                $location_name = $loc_row['name'];
            }
        }

        $state_name = '';
        if (!empty($row['states_id'])) {
            $sta = $DB->request(['SELECT' => ['name'], 'FROM' => 'glpi_states', 'WHERE' => ['id' => $row['states_id']]]);
            if ($sta_row = $sta->current()) {
                $state_name = $sta_row['name'];
            }
        }

        $manufacturer_name = '';
        if (!empty($row['manufacturers_id'])) {
            $man = $DB->request(['SELECT' => ['name'], 'FROM' => 'glpi_manufacturers', 'WHERE' => ['id' => $row['manufacturers_id']]]);
            if ($man_row = $man->current()) {
                $manufacturer_name = $man_row['name'];
            }
        }

        $model_name  = '';
        $model_table = $model_tables[$itemtype] ?? null;
        if ($model_id_key && $model_table && !empty($row[$model_id_key]) && $DB->tableExists($model_table)) {
            $mod = $DB->request(['SELECT' => ['name'], 'FROM' => $model_table, 'WHERE' => ['id' => $row[$model_id_key]]]);
            if ($mod_row = $mod->current()) {
                $model_name = $mod_row['name'];
            }
        }

        $rows[] = [
            'id'                => $row['id'],
            'name'              => $row['name'],
            'serial'            => $row['serial'],
            'otherserial'       => $row['otherserial'],
            'location_name'     => $location_name,
            'state_name'        => $state_name,
            'manufacturer_name' => $manufacturer_name,
            'model_name'        => $model_name,
        ];
        $total++;
    }

    if (count($rows) > 0) {
        $devices[$itemtype] = [
            'meta' => $meta,
            'rows' => $rows,
        ];
    }
}

$base_url = Plugin::getWebDir('myassets') . '/front/index.php';

?>

<div class="container-fluid mt-4">

    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <h2 class="mb-0">
                <i class="ti ti-devices me-2 text-primary"></i>
                <?= htmlspecialchars($title) ?>
            </h2>
            <span class="badge bg-primary fs-6"><?= $total ?> dispositivo/i</span>
        </div>

        <div class="btn-group" role="group">
            <a href="<?= $base_url ?>?status=5"
               class="btn <?= $filter == '5' ? 'btn-primary' : 'btn-outline-primary' ?>">
                <i class="ti ti-list me-1"></i>
                Solo dispositivi in uso
            </a>
            <a href="<?= $base_url ?>?status=all"
               class="btn <?= $filter == 'all' ? 'btn-primary' : 'btn-outline-primary' ?>">
                <i class="ti ti-building me-1"></i>
                Tutti i dispositivi
            </a>
        </div>
    </div>

    <?php if ($total === 0): ?>

        <div class="card">
            <div class="card-body text-center py-5">
                <i class="ti ti-device-desktop-off text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted fs-4">
                    <?= $filter === 'inuso'
                        ? 'Nessun dispositivo "In uso presso uffici" collegato al tuo account.'
                        : 'Nessun dispositivo collegato al tuo account.' ?>
                </p>
                <?php if ($filter === 'inuso'): ?>
                    <a href="<?= $base_url ?>?filter=all" class="btn btn-outline-secondary mt-2">
                        <i class="ti ti-list me-1"></i> Mostra tutti i dispositivi
                    </a>
                <?php endif; ?>
            </div>
        </div>

    <?php else: ?>

        <?php foreach ($devices as $itemtype => $group): ?>
            <?php
                $meta = $group['meta'];
                $rows = $group['rows'];
            ?>
            <div class="card mb-4">
                <div class="card-header">
                    <h3 class="card-title mb-0">
                        <i class="<?= htmlspecialchars($meta['icon']) ?> me-2"></i>
                        <?= htmlspecialchars($meta['label']) ?>
                        <span class="badge"><?= count($rows) ?></span>
                    </h3>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Nome</th>
                                    <th>Marca</th>
                                    <th>Modello</th>
                                    <th>N° di serie</th>
                                    <th>N° inventario</th>
                                    <th>Posizione</th>
                                    <th>Stato</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($rows as $row): ?>
                                <tr>
                                    <td>
                                        <i class="<?= htmlspecialchars($meta['icon']) ?> me-1 text-muted"></i>
                                        <strong><?= htmlspecialchars($row['name'] ?: '—') ?></strong>
                                    </td>
                                    <td><?= htmlspecialchars($row['manufacturer_name'] ?: '—') ?></td>
                                    <td><?= htmlspecialchars($row['model_name'] ?: '—') ?></td>
                                    <td class="font-monospace text-muted">
                                        <?= htmlspecialchars($row['serial'] ?: '—') ?>
                                    </td>
                                    <td class="font-monospace text-muted">
                                        <?= htmlspecialchars($row['otherserial'] ?: '—') ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['location_name'])): ?>
                                            <i class="ti ti-map-pin me-1 text-muted"></i>
                                            <?= htmlspecialchars($row['location_name']) ?>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($row['state_name'])): ?>
                                            <span class="badge bg-info text-dark">
                                                <?= htmlspecialchars($row['state_name']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    <?php endif; ?>

</div>

<?php

if (Session::getCurrentInterface() === 'helpdesk') {
    Html::helpFooter();
} else {
    Html::footer();
}
