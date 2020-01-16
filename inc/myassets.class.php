<?php

class PluginMyassetsMyassets extends CommonDBTM {

   static protected $notable = true;



   public function showForm($ID, $options = []) {
      global $CFG_GLPI;

      $this->initForm($ID, $options);
      $this->showFormHeader($options);

      if (!isset($options['display'])) {
         //display per default
         $options['display'] = true;
      }

      $params = $options;
      //do not display called elements per default; they'll be displayed or returned here
      $params['display'] = false;

      $out = '<tr>';
      $out .= '<th>' . __('My label', 'myexampleplugin') . '</th>';

      $objectName = autoName(
         $this->fields["name"],
         "name",
         (isset($options['withtemplate']) && $options['withtemplate']==2),
         $this->getType(),
         $this->fields["entities_id"]
      );

      $out .= '<td>';
      $out .= Html::autocompletionTextField(
         $this,
         'name',
         [
            'value'     => $objectName,
            'display'   => false
         ]
      );
      $out .= '</td>';

      $out .= $this->showFormButtons($params);

      if ($options['display'] == true) {
         echo $out;
      } else {
         return $out;
      }
   }


   /**
    * @see CommonGLPI::getMenuName()
   **/
   static function getMenuName() {
      return "I miei dispositivi"; //__('My Assets');
   }

   /**
    *  @see CommonGLPI::getMenuContent()
    *
    *  @since version 0.5.6
   **/
   static function getMenuContent() {
   	global $CFG_GLPI;
	if ( 1) {
   	$menu = array();
      $menu['title']   = __('I miei dispositivi','myassets');
      $menu['page']    = '/plugins/myassets/front/myassets.php';
      $image = "<img src='".
            $CFG_GLPI["root_doc"]."/pics/options_search.png' title='".
            _n('Configurations', 'Configurations', 2, 'invoice')."' alt='".
            _n('Configurations', 'Configurations', 2, 'invoice')."'>";
      $menu['links'][$image]                          = "/plugins/invoice/front/config.php";
   	return $menu;
	}
   }
function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
   switch ($item::getType()) {
      case Computer::getType():
      case Phone::getType():
         return __('Tab from my plugin', 'PluginMyassetsMyassets');
         break;
   }
   return '';
}

}

?>
