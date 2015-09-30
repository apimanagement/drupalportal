<?php

/**
 * @file
 * Analytics functions for the ibm_apim module.
 */

/**
 * Display graphs of analytics for the current developer organization
 */
function ibm_apim_analytics_org() {
  $retValue = _ibm_apim_analytics_output(null, null, null);
  $developer_org = ibm_apim_get_current_developer_org();
  if (isset($developer_org)) {
    drupal_set_title($developer_org['name']);
  }
  return $retValue;
}

/**
 * Display graphs of analytics for a single application
 * If path and verb are specified then data will be for specific operation, else for all.
 */
function ibm_apim_analytics_app($appID = NULL) {
  $retValue = _ibm_apim_analytics_output($appID, null, null);
  return $retValue;
}

/**
 * Display graphs of analytics for a single operation
 */
function ibm_apim_analytics_op($appID = NULL, $base64path = NULL, $initialverb = NULL, $resourceId = NULL) {
  $retValue = _ibm_apim_analytics_output($appID, $base64path, $initialverb, $resourceId);
  return $retValue;
}

/**
 * Display graphs of analytics for an organization, a single application or a single operation
 * If path and verb are specified then data will be for specific operation, if app is specified
 * but not path or verb then for a single app, and if no params are supplied then for the current org.
 */
function _ibm_apim_analytics_output($appID = NULL, $base64path = NULL, $initialverb = NULL, $resourceId = NULL) {
  $analytics_data = array();
  $path = null;
  $verb = null;
  $markup = '';
  if (isset($base64path) && !empty($base64path)) {
    $path = base64_decode($base64path);
  }
  if (isset($initialverb) && !empty($initialverb)) {
    $verb = strtolower($initialverb);
  }
  $org = ibm_apim_get_current_developer_org();
  $orgID = $org['id'];

  $ibm_apim_analytics_successrate = variable_get('ibm_apim_analytics_successrate', 1);
  $ibm_apim_analytics_latencies = variable_get('ibm_apim_analytics_latencies', 1);
  $ibm_apim_analytics_datausage = variable_get('ibm_apim_analytics_datausage', 1);

  // handle all graphs being turned off
  if ($ibm_apim_analytics_successrate == 0 && $ibm_apim_analytics_latencies == 0 && $ibm_apim_analytics_datausage == 0) {
    $markup .= "<p>" . t('Analytics has been disabled by the system administrator. Contact them for further assistance.') . "</p>";
    return array('#markup' => $markup);
  }
  else {
    if (isset($appID)) {
      if (isset($resourceId)) {
        $urlstring = 'op/' . $appID . '/' . $resourceId;
      }
      else {
        $urlstring = 'app/' . $appID;
      }
    }
    else {
      $urlstring = 'org/' . $orgID;
    }

    if (isset($path) && !(empty($path)) && isset($verb) && !(empty($verb))) {
      $markup .= "<p class='summaryInfo'><b>" . t('Path:') . "</b> " . $path . "</p>";
      $markup .= "<p class='summaryInfo'><b>" . t('Verb:') . "</b> " . strtoupper($verb) . "</p>";
    }
    else
      if (isset($appID) && !empty($appID)) {
        $markup .= "<p class='summaryInfo'>" . t('Summary of all APIs used by this application.') . "</p>";
      }
      else {
        $markup .= "<p class='summaryInfo'>" . t('Summary of all APIs used by all applications owned by this organization.') . "</p>";
      }

    if (module_exists('d3')) {

      global $language;
      drupal_add_js(libraries_get_path('moment') . '/moment-with-locales.min.js', 'file');
      drupal_add_js('moment.locale("' . $language->language . '");', 'inline');
      drupal_add_js(drupal_get_path('module', 'd3') . '/libraries/d3.tooltip/tooltip.js', 'file');

      if ($ibm_apim_analytics_successrate == 1) {
        /* Get success rate data */
        $successrate_data = _ibm_apim_analytics_get_successrate($appID, $resourceId);
        $successchartdata = array();
        foreach ($successrate_data as $data) {
          if (isset($base64path) && isset($initialverb)) {
            foreach ($data['successRates'] as $successrates) {
              $successchartdata[] = array(
                'datetime' => $successrates['datetime'],
                'success' => $successrates['success'],
                'failure' => $successrates['failure'],
                'total' => $successrates['total']);
            }
          }
          else {
            $successchartdata[] = array(
              'datetime' => $data['datetime'],
              'success' => $data['success'],
              'failure' => $data['failure'],
              'total' => $data['total']);
          }
        }

        $markup .= "<h2>" . t('Success rate') . "</h2>";
        if (count($successchartdata) > 1) {
          $markup .= '<svg id="successgraph" class="d3Chart" width="800" height="350"></svg>';
          $chart4 = array(
            'id' => 'successgraph',
            'type' => 'SuccessLineGraphWithSelector',
            'legend' => array(t('Success'), t('Failure'), t('Total')),
            'datacolumns' => array(t('success'), t('failure'), t('total')),
            'rows' => $successchartdata);
          $markup .= d3_draw($chart4);
        }
        else {
          $markup .= "<p class='analyticsNoDataFound'>" . t('No data found') . "</p>";
        }
        $markup .= "<p class='downloadDataLink'>" . l(t('Download Data'), 'ibm_apim/downloadanalytics/successrate/' . $urlstring) . "</p>";
      }

      if ($ibm_apim_analytics_datausage == 1) {
        /* Get data usage data */
        $datausage_data = _ibm_apim_analytics_get_datausage($appID, $resourceId);
        $datausagechartdata = array();
        foreach ($datausage_data as $data) {
          if (isset($base64path) && isset($initialverb)) {
            foreach ($data['dataUsages'] as $datausage) {
              $datausagechartdata[] = array(
                'datetime' => $datausage['datetime'],
                'avg' => $datausage['avg'],
                'max' => $datausage['max'],
                'min' => $datausage['min']);
            }
          }
          else {
            $datausagechartdata[] = array(
              'datetime' => $data['datetime'],
              'avg' => $data['avg'],
              'max' => $data['max'],
              'min' => $data['min']);
          }
        }
        $markup .= "<h2>" . t('Data usage') . "</h2>";
        if (count($datausagechartdata) > 1) {
          $markup .= '<svg id="datausagegraph" class="d3Chart" width="800" height="350"></svg>';
          $chart2 = array(
            'id' => 'datausagegraph',
            'type' => 'LineGraphWithSelector',
            'legend' => array(t('Average'), t('Maximum'), t('Minimum')),
            'datacolumns' => array(t('avg'), t('max'), t('min')),
            'rows' => $datausagechartdata);
          $markup .= d3_draw($chart2);
        }
        else {
          $markup .= "<p class='analyticsNoDataFound'>" . t('No data found') . "</p>";
        }
        $markup .= "<p class='downloadDataLink'>" . l(t('Download Data'), 'ibm_apim/downloadanalytics/datausage/' . $urlstring) . "</p>";
      }

      if ($ibm_apim_analytics_latencies == 1) {
        /* Get latency data */
        $latency_data = _ibm_apim_analytics_get_latency($appID, $resourceId);
        $latencychartdata = array();
        foreach ($latency_data as $data) {
          if (isset($base64path) && isset($initialverb)) {
            foreach ($data['latencies'] as $latency) {
              $latencychartdata[] = array(
                'datetime' => $latency['datetime'],
                'avg' => $latency['avg'],
                'max' => $latency['max'],
                'min' => $latency['min']);
            }
          }
          else {
            $latencychartdata[] = array(
              'datetime' => $data['datetime'],
              'avg' => $data['avg'],
              'max' => $data['max'],
              'min' => $data['min']);
          }
        }
        $markup .= "<h2>" . t('Latency') . "</h2>";
        if (count($latencychartdata) > 1) {
          $markup .= '<svg id="latencygraph" class="d3Chart" width="800" height="350"></svg>';
          $chart3 = array(
            'id' => 'latencygraph',
            'type' => 'LineGraphWithSelector',
            'legend' => array(t('Average'), t('Maximum'), t('Minimum')),
            'datacolumns' => array(t('avg'), t('max'), t('min')),
            'rows' => $latencychartdata);
          $markup .= d3_draw($chart3);
        }
        else {
          $markup .= "<p class='analyticsNoDataFound'>" . t('No data found') . "</p>";
        }
        $markup .= "<p class='downloadDataLink'>" . l(t('Download Data'), 'ibm_apim/downloadanalytics/latency/' . $urlstring) . "</p>";
      }
      return array('#markup' => $markup);
    }
    else {
      drupal_set_message(t('D3 module not enabled'), 'error');
      return;
    }
  }
}

/*
 * download the data usage data as JSON
 */
function ibm_apim_analytics_get_org_datausage_json() {
  $analytics_data = _ibm_apim_analytics_get_datausage(null, null, null);
  header('Content-Disposition: attachment; filename="org_datausage.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the latency data as JSON
 */
function ibm_apim_analytics_get_org_latency_json() {
  $analytics_data = _ibm_apim_analytics_get_latency(null, null, null);
  header('Content-Disposition: attachment; filename="org_latency.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the success rate data as JSON
 */
function ibm_apim_analytics_get_org_successrate_json() {
  $analytics_data = _ibm_apim_analytics_get_successrate(null, null, null);
  header('Content-Disposition: attachment; filename="org_successrate.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the data usage data as JSON
 */
function ibm_apim_analytics_get_app_datausage_json($appID = NULL) {
  $analytics_data = _ibm_apim_analytics_get_datausage($appID, null, null);
  header('Content-Disposition: attachment; filename="app_datausage.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the latency data as JSON
 */
function ibm_apim_analytics_get_app_latency_json($appID = NULL) {
  $analytics_data = _ibm_apim_analytics_get_latency($appID, null, null);
  header('Content-Disposition: attachment; filename="app_latency.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the success rate data as JSON
 */
function ibm_apim_analytics_get_app_successrate_json($appID = NULL) {
  $analytics_data = _ibm_apim_analytics_get_successrate($appID, null, null);
  header('Content-Disposition: attachment; filename="app_successrate.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the data usage data as JSON
 */
function ibm_apim_analytics_get_op_datausage_json($appID = NULL, $resourceId = NULL) {
  $analytics_data = _ibm_apim_analytics_get_datausage($appID, $resourceId);
  header('Content-Disposition: attachment; filename="op_datausage.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the latency data as JSON
 */
function ibm_apim_analytics_get_op_latency_json($appID = NULL, $resourceId = NULL) {
  $analytics_data = _ibm_apim_analytics_get_latency($appID, $resourceId);
  header('Content-Disposition: attachment; filename="op_latency.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * download the success rate data as JSON
 */
function ibm_apim_analytics_get_op_successrate_json($appID = NULL, $resourceId = NULL) {
  $analytics_data = _ibm_apim_analytics_get_successrate($appID, $resourceId);
  header('Content-Disposition: attachment; filename="op_successrate.json"');
  header('Content-Type: application/json');
  if (isset($analytics_data)) {
    echo drupal_json_encode($analytics_data);
  }
}

/*
 * Utility method to make the actual apim call to get the data usage data
 */
function _ibm_apim_analytics_get_datausage($appID = NULL, $resourceId = NULL) {
  $analytics_data = array();
  $org = ibm_apim_get_current_developer_org();
  $developerOrgs = $org['id'];

  if (isset($developerOrgs)) {
    $developer_org_url = '/orgs/' . $developerOrgs;
  }
  else {
    $developer_org_url = '';
  }

  if (isset($appID) && !empty($appID)) {
    $appidstring = '/apps/' . $appID;
  }
  else {
    $appidstring = '';
  }

  $url = '/v1/portal' . $developer_org_url . '/analytics' . $appidstring . '/data-usage';
  if (!isset($resourceId) || empty($resourceId)) {
    $url .= '/all';
  }
  $url .= '?timeRange=quarter&interval=hour';
  if (isset($resourceId) && !empty($resourceId)) {
    $url .= '&resourceID=' . $resourceId;
  }
  $result = ibm_apim_call($url);
  if (isset($result) && isset($result->data) && !isset($result->data['errors'])) {
    $analytics_data = $result->data;
  }
  return $analytics_data;
}

/*
 * Utility method to make the actual apim call to get the latency data
 */
function _ibm_apim_analytics_get_latency($appID = NULL, $resourceId = NULL) {
  $analytics_data = array();
  $org = ibm_apim_get_current_developer_org();
  $developerOrgs = $org['id'];

  if (isset($developerOrgs)) {
    $developer_org_url = '/orgs/' . $developerOrgs;
  }
  else {
    $developer_org_url = '';
  }
  if (isset($appID) && !empty($appID)) {
    $appidstring = '/apps/' . $appID;
  }
  else {
    $appidstring = '';
  }
  $url = '/v1/portal' . $developer_org_url . '/analytics' . $appidstring . '/latencies';
  if (!isset($resourceId) || empty($resourceId)) {
    $url .= '/all';
  }
  $url .= '?timeRange=quarter&interval=hour';
  if (isset($resourceId) && !empty($resourceId)) {
    $url .= '&resourceID=' . $resourceId;
  }
  $result = ibm_apim_call($url);
  if (isset($result) && isset($result->data) && !isset($result->data['errors'])) {
    $analytics_data = $result->data;
  }
  return $analytics_data;
}

/*
 * Utility method to make the actual apim call to get the success rate data
 */
function _ibm_apim_analytics_get_successrate($appID = NULL, $resourceId = NULL) {
  $analytics_data = array();
  $org = ibm_apim_get_current_developer_org();
  $developerOrgs = $org['id'];

  if (isset($developerOrgs)) {
    $developer_org_url = '/orgs/' . $developerOrgs;
  }
  else {
    $developer_org_url = '';
  }
  if (isset($appID) && !empty($appID)) {
    $appidstring = '/apps/' . $appID;
  }
  else {
    $appidstring = '';
  }
  $url = '/v1/portal' . $developer_org_url . '/analytics' . $appidstring . '/success-rates';
  if (!isset($resourceId) || empty($resourceId)) {
    $url .= '/all';
  }
  $url .= '?timeRange=quarter&interval=hour';
  if (isset($resourceId) && !empty($resourceId)) {
    $url .= '&resourceID=' . $resourceId;
  }
  $result = ibm_apim_call($url);
  if (isset($result) && isset($result->data) && !isset($result->data['errors'])) {
    $analytics_data = $result->data;
  }
  return $analytics_data;
}

?>