<?php
include_once DRUPAL_ROOT . '/sites/all/modules/ibm_apim/api/api.install';
function api_drush_command() {

$items['api-clense-drush-command'] = array(
    'description' => 'Clears the api entries back to a clean state.',
    'aliases' => array('clense_apis'),
  );

  return $items;
}

function drush_api_clense_drush_command() {
  _api_delete_node_content('api');
  _api_delete_custom_fields();
  field_purge_batch(500);
  drupal_set_message(t('All API entries deleted.'), 'success');
}
