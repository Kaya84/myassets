<?php


	define('GLPI_ROOT', '../../..');
	include (GLPI_ROOT . "/inc/includes.php");
	Html::header("My Assets", $_SERVER['PHP_SELF'], 'Assets', 'PluginMyassetsMyassets');
	Html::displayTitle($CFG_GLPI['root_doc']."/pics/ok.png","Info", "Elenco dei dispositivi associati", null);
	showAssets(false); //todo : muovere il tutto nella classe?
	Html::footer();


function showAssets($tech) {
	global $DB, $CFG_GLPI;

      $ID = $_SESSION['glpiID'];

      if ($tech) {
         $type_user   = $CFG_GLPI['linkuser_tech_types'];
         $type_group  = $CFG_GLPI['linkgroup_tech_types'];
         $field_user  = 'users_id_tech';
         $field_group = 'groups_id_tech';
      } else {
         $type_user   = $CFG_GLPI['linkuser_types'];
         $type_group  = $CFG_GLPI['linkgroup_types'];
         $field_user  = 'users_id';
         $field_group = 'groups_id';
      }

      $group_where = "";
      $groups      = [];

      $iterator = $DB->request([
         'SELECT'    => [
            'glpi_groups_users.groups_id',
            'glpi_groups.name'
         ],
         'FROM'      => 'glpi_groups_users',
         'LEFT JOIN' => [
            'glpi_groups' => [
               'FKEY' => [
                  'glpi_groups_users'  => 'groups_id',
                  'glpi_groups'        => 'id'
               ]
            ]
         ],
         'WHERE'     => ['glpi_groups_users.users_id' => $ID]
      ]);

      $number = count($iterator);

      $group_where = [];
      while ($data = $iterator->next()) {
         $group_where[$field_group][] = $data['groups_id'];
         $groups[$data["groups_id"]] = $data["name"];
      }

      echo "<div class='spaced'><table class='tab_cadre_fixehov'>";
      $header = "<tr><th>".__('Type')."</th>";
      $header .= "<th>".__('Name')."</th>";
      $header .= "<th>".__('Marca')."</th>";
      $header .= "<th>".__('Modello')."</th>";
      $header .= "<th>".__('Serial number')."</th>";
      $header .= "<th>".__('Inventory number')."</th>";
      $header .= "<th>".__('Location')."</th>";
      $header .= "<th>&nbsp;</th></tr>";
      echo $header;

//var_dump($type_user);
      foreach ($type_user as $itemtype) {
         if (!($item = getItemForItemtype($itemtype))) {
            continue;
         }
         //if ($item->canView()) {
         if (true) {
            $itemtable = getTableForItemType($itemtype);
            $iterator_params = [
               'FROM'   => $itemtable,
               'WHERE'  => [$field_user => $ID]
            ];

            if ($item->maybeTemplate()) {
               $iterator_params['WHERE']['is_template'] = 0;
            }
            if ($item->maybeDeleted()) {
               $iterator_params['WHERE']['is_deleted'] = 0;
            }

            $item_iterator = $DB->request($iterator_params);

            $type_name = $item->getTypeName();

            while ($data = $item_iterator->next()) {

               $cansee = $item->can($data["id"], READ);
               $link   = $data["name"];
               if ($cansee) {
                  $link_item = $item::getFormURLWithID($data['id']);
                  if ($_SESSION["glpiis_ids_visible"] || empty($link)) {
                     $link = sprintf(__('%1$s (%2$s)'), $link, $data["id"]);
                  }
                  $link = "<a href='".$link_item."'>".$link."</a>";
               }
               $linktype = "";
               if ($data[$field_user] == $ID) {
                 // $linktype = self::getTypeName(1);
               }
               echo "<tr class='tab_bg_1'><td class='center'>$type_name</td>";
               echo "<td class='center'>$link</td>";

 	       echo "<td class='center'>";
               if (isset($data["manufacturers_id"])) {
                  echo Dropdown::getDropdownName("glpi_manufacturers", $data['manufacturers_id']);
               } else {
                  echo 'N/A';
               }
               echo "</td>";
 	       echo "<td class='center'>";
               if (isset($data["computermodels_id"])) {
                  echo Dropdown::getDropdownName("glpi_computermodels", $data['computermodels_id']);
               } else if (isset($data["monitormodels_id"])) {
                  echo Dropdown::getDropdownName("glpi_monitormodels", $data['monitormodels_id']);
               } else if (isset($data["peripheraltypes_id"])) {
                  echo Dropdown::getDropdownName("glpi_peripheraltypes", $data['peripheraltypes_id']);
               } else if (isset($data["phonemodels_id"])) {
                  echo Dropdown::getDropdownName("glpi_phonemodels", $data['phonemodels_id']);
               } else {
                  echo 'N/A';

               }
               echo "</td>";
               echo "<td class='center'>";
               if (isset($data["serial"]) && !empty($data["serial"])) {
                  echo $data["serial"];
               } else {
                  echo '&nbsp;';
               }
               echo "</td><td class='center'>";
               if (isset($data["otherserial"]) && !empty($data["otherserial"])) {
                  echo $data["otherserial"];
               } else {
                  echo '&nbsp;';
               }
               echo "</td><td class='center'>";
               if (isset($data["locations_id"])) {
                  echo Dropdown::getDropdownName("glpi_locations", $data['locations_id']);
               } else {
                  echo '&nbsp;';
               }

               echo "</td><td class='center'>$linktype</td></tr>";
            }
         }
      }
      if ($number) {
         echo $header;
      }
      echo "</table></div>";


}
?>

