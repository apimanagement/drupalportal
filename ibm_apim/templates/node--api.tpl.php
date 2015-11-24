<?php

/**
 * @file
 * Default theme implementation for apis.
 *
 * @see template_preprocess()
 * @see template_preprocess_api()
 * @see template_process()
 * @see theme_api()
 *
 * @ingroup themeable
 */

drupal_add_library('system', 'ui.accordion');
drupal_add_js('jQuery(document).ready(function(){
      jQuery("div#accordion").accordion({
        header: "> div > h3",
        collapsible: true,
        active: false,
        heightStyle: "content",
      });
    jQuery(".subscribeLink").on("click", null, null, function(event) {
      window.location.href = jQuery(this).attr("href");
      event.preventDefault();
    });
  });', 'inline');

// Add Standard .css includes from Swagger-Editor Distribution
drupal_add_css(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/styles/main.css');
drupal_add_css(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/styles/branding.css');

// pull in angularJS
if (module_exists('libraries') && libraries_get_path('angular')) {
  $angularpath = libraries_get_path('angular');
  drupal_add_js($angularpath . '/angular.js', array('weight' => 1));
  drupal_add_js($angularpath . '/angular-cookies.js', array('weight' => 2));
  drupal_add_js($angularpath . '/angular-resource.js', array('weight' => 2));
  drupal_add_js($angularpath . '/angular-sanitize.js', array('weight' => 2));
}


drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/es5-shim/es5-shim.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/angular-bootstrap/ui-bootstrap-tpls.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/marked/lib/marked.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/angular-marked/angular-marked.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/ace-builds/src-noconflict/ace.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/ace-builds/src-noconflict/mode-yaml.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/ace-builds/src-noconflict/ext-language_tools.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/angular-ui-ace/ui-ace.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/raf/index.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/angular-ui-layout/ui-layout.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/angular-ui-router/release/angular-ui-router.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/bootstrap/dist/js/bootstrap.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/js-yaml/dist/js-yaml.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/json-formatter/dist/json-formatter.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/json-formatter-js/dist/bundle.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/json-schema-view-js/dist/bundle.min.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/lodash/lodash.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/ng-file-upload/ng-file-upload.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/ngstorage/ngStorage.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/promise-polyfill/Promise.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/json-editor/dist/jsoneditor.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/schema-form/dist/schema-form.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/spark-md5/spark-md5.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/yaml-js/yaml.js', array(
'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/bower_components/yaml-worker/index.js', array(
'weight' => 2));

// Add standard .js files from Swagger Editor
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/app.js', array(
'weight' => 3));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/bootstrap.js', array(
'weight' => 3));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/ace/themes/theme-atom_dark.js', array(
'weight' => 4));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/ace/snippets/swagger.snippet.js', array(
'weight' => 5));
//drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/config/app.config.js', array('weight' => 7));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/main.js', array(
'weight' => 8));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/header.js', array(
'weight' => 9));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/import-file.js', array(
'weight' => 10));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/editor.js', array(
'weight' => 11));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/preview.js', array(
'weight' => 12));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/general-modal.js', array(
'weight' => 13));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/importurl.js', array(
'weight' => 14));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/paste-json.js', array(
'weight' => 15));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/errorpresenter.js', array(
'weight' => 16));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/openexamples.js', array(
'weight' => 17));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/preferences.js', array(
'weight' => 17));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/modal.js', array(
'weight' => 18));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/controllers/security.js', array(
'weight' => 19));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/on-file-change.js', array(
'weight' => 20));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/operation.js', array(
'weight' => 22));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/schemamodel.js', array(
'weight' => 23));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/stop-event.js', array(
'weight' => 24));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/auto-focus.js', array(
'weight' => 24));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/scroll-into-view-when.js', array(
'weight' => 25));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/collapsewhen.js', array(
'weight' => 26));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/track-event.js', array(
'weight' => 27));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/enums/defaults.js', array(
'weight' => 28));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/enums/strings.js', array(
'weight' => 29));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/filters/formdata.js', array(
'weight' => 30));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/directives/tryoperation.js', array(
'weight' => 31));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/tag-manager.js', array(
'weight' => 33));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/autocomplete.js', array(
'weight' => 34));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/fileloader.js', array(
'weight' => 35));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/editor.js', array(
'weight' => 36));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/builder.js', array(
'weight' => 37));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/ast-manager.js', array(
'weight' => 38));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/codegen.js', array(
'weight' => 40));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/focused-path.js', array(
'weight' => 41));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/storage.js', array(
'weight' => 42));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/local-storage.js', array(
'weight' => 43));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/backend.js', array(
'weight' => 44));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/keyword-map.js', array(
'weight' => 45));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/preferences.js', array(
'weight' => 46));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/auth-manager.js', array(
'weight' => 47));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/analytics.js', array(
'weight' => 48));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/external-hooks.js', array(
'weight' => 49));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/sway-worker.js', array(
'weight' => 50));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/yaml.js', array(
'weight' => 51));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/services/fold-state-manager.js', array(
'weight' => 52));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/router.js', array(
'weight' => 53));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/plugins/jquery.scroll-into-view.js', array(
'weight' => 54));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggereditor/app/scripts/branding.js', array(
'weight' => 55));

$apim_session = &_ibm_apim_get_apim_session();
$protocol_lower = strtolower($api_protocol[0]['value']);
if (isset($protocol_lower) && $protocol_lower == 'soap') {
  $protocol = 'soap';
} else {
  $protocol = 'rest';
}
$ibm_apim_js_settings = array(
  'url' => $GLOBALS['base_url'] . '/?q=ibm_apim/swaggerjson/' . $content['api_apiid'][0]['#markup'] . '/' . $content['api_version'][0]['#markup'] . '/' . $protocol,
  'authtype' => $api_authentication[0]['value'],
  'context_header' => $apim_session['org'] . '.' . $apim_session['env'],
  'swaggerstrings' => $variables['swaggerstrings'],
  'apps' => $apps);
if (isset($apim_session['auth'])) {
  $ibm_apim_js_settings['basic_auth'] = 'Basic ' . $apim_session['auth'];
}
if (isset($variables['oauthconfig'])) {
  $variables['oauthconfig']['defaultscope'] = check_plain($content['api_context'][0]['#markup']);
  $ibm_apim_js_settings['oauthconfig'] = $variables['oauthconfig'];
}
$ibm_apim_show_cors_warnings = variable_get('ibm_apim_show_cors_warnings', 1);
$ibm_apim_js_settings['show_cors_warnings'] = $ibm_apim_show_cors_warnings;

drupal_add_js(array('ibm_apim' => $ibm_apim_js_settings), 'setting');

drupal_add_css(drupal_get_path('module', 'ibm_apim') . '/css/ie-9.css', array('weight' => 115, 'browsers' => array('IE' => 'lte IE 9', '!IE' => FALSE)));

?>
<div class="pagebreadcrumb"><?php print l("< " .t('Back to APIs'), 'api');?></div>
<article id="node-<?php print $node->nid; ?>"
	class="<?php print $classes . ' ' . $content['api_apiid'][0]['#markup'] . ' ' . $protocol; ?> clearfix" <?php print $attributes; ?>>

  <?php print $unpublished; ?>

  <?php print render($title_prefix); ?>
  <?php $showplaceholders = variable_get('ibm_apim_show_placeholder_images', 1);?>

  <div class="portal api">
  <div class="apimTopSection">
  <div class="apimAPIImage">
  <?php if (isset($content['api_image'])) {
    print render($content['api_image']);
  } elseif($showplaceholders != 0){
    print '<div class="apimIcon">
			 <div class="field field-name-api-image field-type-image field-label-hidden view-mode-teaser">
			   <div class="field-items">
				 <figure class="clearfix field-item even">
				   <img typeof="foaf:Image" class="image-style-none" src="'. file_create_url(drupal_get_path('module', 'ibm_apim') . '/images/placeholder_image.png') .'" width="123" height="123" alt="">
				 </figure>
		       </div>
			 </div>
		   </div>';
   } else {
	     print "<div class='apimIcon' style='display:none'></div>";
   }
   ?></div>
   <div class="centralspacer"></div>
   <div class="apimInfoSection">
		<div class="apimTitleContainer">
			<p class="apimTitle">
			<?php
			$showversion = variable_get('ibm_apim_show_versions', 1);
			$versiontext = '';
			if ($showversion == 1) {
			  $versiontext = ' (v' . $api_version[0]['value'] . ')';
			}
			print "<span>" . $title . $versiontext . "</span>";
			?>
			</p>
	<div class="planSelector">
    <?php
  if (module_exists('plan') && module_exists('application')) {
    // Use this plan selector button / dropdown
    if (user_is_logged_in()) {
      $isdev = ibm_apim_check_is_developer();
      if (isset($isdev) && $isdev == TRUE) {
        if (isset($plans) && count($plans) == 1) {
          $buttontarget = url("plan/" . check_plain($plans[0]['id']) . "/" . check_plain($plans[0]['version']));
          print "<button type='button' onclick=\"location.href='" . $buttontarget . "'\">" . t('Use this API') . "</button>";
        }
        elseif (isset($plans) && count($plans) > 1) {
          print '<ul class="dropitmenu apimenu"><li><a href="#">' . t('Select a plan') . ' <span class="dropit-icon ui-icon-triangle-1-s"></span></a>';
          print '<ul id="dropdown-menu" class="dropdown-menu">';
          foreach ($plans as $plan) {
            $versiontext = '';
            if ($showversion == 1) {
              $versiontext = ' (v' . $plan['version'] . ')';
            }
            print '<li title="' . check_plain($plan['name']) . $versiontext . '">' . l(check_plain($plan['name']) . $versiontext, 'plan/' . check_plain($plan['id']) . '/' . check_plain($plan['version']), array(
              'html' => TRUE,
              'attributes' => array('class' => array('elipsis-names')))) . '</li>';
          }
          print '</ul></li></ul>';
        }
      } else {
        print '<span>' . t("Only developers can sign up to use APIs").'</span>';
      }
    } else {
      print '<span>' . t("Login to use this API").'</span>';
    }
  }
    ?>

    </div>
			<div class="apimUpdated"><?php try {
			  if (is_numeric($api_updated[0]['value'])) {
			    $epoch = (int)($api_updated[0]['value'] / 1000);
			    $updateddate = new DateTime("@$epoch");
			  } else {
			    $updateddate = new DateTime($api_updated[0]['value']);
			  }
			  print $updateddate->format('Y-m-d');
    } catch (Exception $e) {
    } ?></div>
			<?php
  if (isset($content['field_apirating'])) {
    $content['field_apirating']['#label_display'] = 'hidden';
    print render($content['field_apirating']);
  }
  ?>

		</div>

		<div class="section apimMainContent">
			<label class="label apimField apimDescription"><?php print t('Description'); ?></label>
			<div id="api_description">
			   <?php
			   if (module_exists('markdown')) {
                 print _filter_markdown($api_description[0]['safe_value'], null);
               } else {
                 print '<p>'. $api_description[0]['safe_value'] .'</p>';
               }
               ?>
			</div>
		</div>

  <?php
  // API Documentation attachments
  $docs = api_documents_contents($content['api_apiid'][0]['#markup'], $content['api_version'][0]['#markup']);
  if (isset($docs) && count($docs) > 0) {
    print '<div class="section" class="clearBoth">';
    print '<span class="apiDocTitle">Documentation</span><br/>';

    print '<div class="apiDocRow">';
    foreach ($docs as $doc) {
      $leftdiv = "";
      $rightdiv = "";
      $leftdiv = $leftdiv . '<div class="docFile"><div class="docLink">';
      if (isset($doc['docURL']) && $doc['docURL'] != '') {
        $doctypeClass = api_getCSSforMimetype($doc['mimeType']);
        $linkContent = '<div class="apiDocumentIcon ' . $doctypeClass . '"></div><div class="apiDocumentFileName">' . check_plain($doc['name']) . '</div>';
        $leftdiv = $leftdiv . l($linkContent, 'api/docdownload/'. base64_encode($doc['docURL']), array(
          'attributes' => array('target' => '_blank'),
          'html' => TRUE));
      }
      $leftdiv = $leftdiv . '</span></div></div>';
      $descrset = '';
      if (isset($doc['description']) && $doc['description'] != '') {
        $rightdiv = $rightdiv . '<div>' . check_plain($doc['description']) . '</div>';
        $descrset = 'shortname';
      }
      print '<div class="apiDocLeft ' . $descrset . '">';
      print $leftdiv;
      print '</div><div class="apiDocRight">';
      print $rightdiv;
      print '</div>';
      print '<div class="clearBoth"></div>';
    }
    print '</div></div>';
  }

  // WSDL/Swagger download link
  if ($protocol == "soap") {
    print '<div class="section">';
    print '<label class="sectionHeader">'. t('WSDL').'</label>';
    print l(t('Download WSDL Document'), 'api/wsdldownload/'. check_plain($content['api_apiid'][0]['#markup']) . '/v' . check_plain($content['api_version'][0]['#markup']), array(
          'attributes' => array('target' => '_blank'),
          'html' => TRUE));
    print '</div>';
  } else {
    print '<div class="section">';
    print '<label class="sectionHeader">'. t('Swagger').'</label>';
    print l(t('Download Swagger Document'), 'api/swaggerdownload/'. check_plain($content['api_apiid'][0]['#markup']) . '/' . check_plain($content['api_version'][0]['#markup']), array(
        'attributes' => array('target' => '_blank'),
        'html' => TRUE));
    print '</div>';
  }
  ?>

<?php if ($protocol == "soap") {
   // only need old authentication information for SOAP APIs, its included in the swagger editor content for REST
   print '<div class="section">
			<label class="sectionHeader">'. t('Identify your application using').'</label>
			<span class="highlightText"
				id="api_identify">'. ibm_apim_get_ident_label($api_authorization[0]['value']) .'</span>
			<span class="explanationText">&nbsp;-&nbsp;&nbsp;&nbsp;</span><span
				class="explanationText">'. ibm_apim_get_ident_explanation($api_authorization[0]['value']) .'</span>
		</div>

		<div class="section">
			<label class="sectionHeader">'. t('Authenticate using').'</label>
			<span class="highlightText" id="api_auth">'. ibm_apim_get_auth_label($api_authentication[0]['value']) .'</span>
			<span class="explanationText">&nbsp;-&nbsp;&nbsp;&nbsp;</span><span
				class="explanationText">';

  switch ($api_authentication[0]['value']) {
    case 'basic' :
      print t('You must always provide your user ID and password to use this API.') . '</span>';
      break;
    case 'none' :
      print t('You can access this API without authentication.') . '</span>';
      break;
    case 'oauth' :
      print t('You can use OAuth 2 to access this API.') . '</span>';
      print '<div class="oauthContainer">';
      print '<div><span class="explanationText">' . t('Authorization endpoint URL:') . ' </span>';
      print '<span class="oauthText">' . check_plain($variables['oauthconfig']['authorizationEndpointURL']) . '</span></div>';
      print '<div><span class="explanationText">' . t('Token endpoint URL:') . ' </span>';
      print '<span class="oauthText">' . check_plain($variables['oauthconfig']['tokenEndpointURL']) . '</span></div>';
      print '<div><span class="explanationText">' . t('Default scope:') . ' </span>';
      print '<span class="oauthText">' . check_plain($content['api_context'][0]['#markup']) . '</span></div>';
      if (isset($variables['oauthconfig']['grantType']) && is_array($variables['oauthconfig']['grantType'])) {
        $grantcount = count($variables['oauthconfig']['grantType']);
        if ($grantcount > 0) {
          print '<div><span class="explanationText">' . t('Grant type:') . ' </span>';
          print '<span class="oauthText">' . rtrim(implode(',', $variables['oauthconfig']['grantType']), ',') . '</span></div>';
        }
      }
      if (isset($variables['oauthconfig']['refreshTokensEnabled']) && !empty($variables['oauthconfig']['refreshTokensEnabled'])) {
         if ($variables['oauthconfig']['refreshTokensEnabled'] == true) {
           print '<div><span class="explanationText">' . t('Token refresh:') . ' </span>';
           print '<span class="oauthText">' . t('enabled') . '</span></div>';
         } else {
           print '<div><span class="explanationText">' . t('Token refresh:') . ' </span>';
           print '<span class="oauthText">' . t('disabled') . '</span></div>';
         }
      }
      if (isset($variables['oauthconfig']['tokenRevocationURL']) && !empty($variables['oauthconfig']['tokenRevocationURL']) && $variables['oauthconfig']['tokenRevocationURL'] != null) {
        print '<div><span class="explanationText">' . t('Token revocation URL:') . ' </span>';
        print '<span class="oauthText">' . check_plain($variables['oauthconfig']['tokenRevocationURL']) . '</span></div>';
      }
      print '</div>';
      break;
  }

  print "</div>";
}

if (is_array($customfields) && count($customfields) > 0) {
  foreach($customfields as $customfield) {
    print render($content[$customfield]);
  }
}

 print "</div></div>";

 // API Operations from Swagger Editor
$swagger_classes = $protocol;
if ($allow_api_testing == 0) {
  $swagger_classes .= ' disableapitest';
}
print "<div class='swagger-section " . $swagger_classes . "'>";

       print"<div id='message-bar' class='swagger-ui-wrap' style='display: none;'>&nbsp;</div>
			<div id='swagger-ui-container' class='swagger-ui-wrap'></div>
            <div class='swagger-ops-title apimTitle'>".t('API Operations')."</div>
            <div class='swaggerErrorMessage hiddenError' id='swaggerXHRErrorMessage'>" . t('An error has occurred rendering this API. Please contact your server administrator.') . "</div>
            <div class='swaggerLoadingMessage' id='swaggerLoadingMessage'><img src='". file_create_url(drupal_get_path('module', 'ibm_apim') . '/images/ajax-loader.gif') . "'></img></div>
			<div class='total-wrapper' ui-view></div>
		</div>";
 // Plan selection
 if (module_exists('plan')) {
	print "<div class='apiPlanSummary clearBoth portal section apimMainContent'>";
	print "<span class='apimTitle'>". t('Subscribe to Plan') . "</span>";
    print "<div id='accordion'>";
   foreach ($plans as $plan) {
     $subscribelink = '';
     $isdev = ibm_apim_check_is_developer();
     if (isset($isdev) && $isdev == TRUE) {
       $subscribelink = "<a class='subscribeLink' href='" . url('plan/' . check_plain($plan['id']) . '/' . check_plain($plan['version'])) . "'>".t('Subscribe') . "</a>";
     }
     $showversion = variable_get('ibm_apim_show_versions', 1);
     $versiontext = '';
     if ($showversion == 1) {
       $versiontext = ' (v' . check_plain($plan['version']) . ')';
     }
      print "<div class='appPlanName'><h3>" . check_plain($plan['name']) . $versiontext . "<span class='planSubscribe'>".$subscribelink."</span></h3>";
      foreach ($plan['apis'] as $api) {
        if ($api['id'] == check_plain($content['api_apiid'][0]['#markup']) && $api['version'] == $content['api_version'][0]['#markup']) {
          print "<div class='portalApi animateMaxHeight'>
        <div class='planHeader'><div class='rate'>".t('Name | Rate limit')."</div><div class='verb'>".t('Verb')."</div><div class='path'>".t('Path')."</div></div>
	      <div class='resourceView resourcePlanView'>";
          foreach ($api['resources'] as $resource) {
            if (isset($resource['rateLimit']['numRequests'])) {
              $ratelimitstr = t('@requests requests per @period @timescale', array('@requests'=> check_plain($resource['rateLimit']['numRequests']), '@period' => check_plain($resource['rateLimit']['timePeriod']), '@timescale' => check_plain($resource['rateLimit']['timeScale'])));
            } else {
              $ratelimitstr = t('unlimited');
            }
            print "<div class='displayInlineTop resourceHeadline'>
		      <div class='displayInlineTop resourceMethod resourceMethodBadge " . strtoupper(check_plain($resource['verb'])) . "'>" . strtoupper(check_plain($resource['verb'])) . "</div>
	          <div class='resourceNameAndRate boundedText'>" . check_plain($resource['name']) . " | " . $ratelimitstr . "</div>
              <div class='resourceInfoLine'>
                <div class='displayInlineTop resourcePathShort boundedText' title='" . check_plain($api['context']) . check_plain($resource['path']) . "'>" . check_plain($api['context']) . check_plain($resource['path']) . "</div>
      		    <div class='resourceDesc boundedText' title='" . check_plain($resource['description']) . "'>" . check_plain($resource['description']) . "</div>
	          </div>
            </div>";
          }
          print "</div></div>";
        }
      }
      print "</div>";
    }
      print "</div>";

    print "</div>";

  }

	print	"<div ". $content_attributes ." >";

  hide($content['comments']);
  hide($content['links']);
  // print render($content);
  if (isset($content['field_apitags'])) {
    print render($content['field_apitags']);
  }
  if (module_exists('forum')) {
    $autocreate_forum = variable_get('ibm_apim_autocreate_apiforum', 1);
    if ($autocreate_forum == 1 && isset($apiforumlink)) {
      print "<div class=\"apiForumLink\">" . $apiforumlink . "</div>";
    }
  }
  ?>
  </div>

  <?php if ($links = render($content['links'])): ?>
    <nav <?php print $links_attributes; ?>><?php print $links; ?></nav>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</article>
