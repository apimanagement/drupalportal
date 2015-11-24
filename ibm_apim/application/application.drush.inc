<?php
include_once DRUPAL_ROOT . '/sites/all/modules/ibm_apim/application/application.install';
function application_drush_command() {

$items['application-clense-drush-command'] = array(
    'description' => 'Clears the application entries back to a clean state.',
    'aliases' => array('clense_applications'),
  );

  return $items;
}

function drush_application_clense_drush_command() {
  _application_delete_node_content('application');
  _application_delete_custom_fields();
  field_purge_batch(500);
  drupal_set_message(t('All Application entries deleted.'), 'success');
}
