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

// Add Standard .css includes from Swagger-UI Distribution
drupal_add_css('http://fonts.googleapis.com/css?family=Droid+Sans:400,700', array(
  'type' => 'external'));
drupal_add_css(drupal_get_path('module', 'ibm_apim') . '/swaggerui/css/screen.css');

// Add Standard .js includes from Swagger-UI Distribution
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/shred.bundle.js', array(
  'weight' => 0));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/jquery.slideto.min.js', array(
  'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/jquery.wiggle.min.js', array(
  'weight' => 3));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/jquery.ba-bbq.min.js', array(
  'weight' => 4));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/handlebars-1.0.0.js', array(
  'weight' => 5));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/underscore-min.js', array(
  'weight' => 6));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/backbone-min.js', array(
  'weight' => 7));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/swagger.js', array(
  'weight' => 8));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/swagger-client.js', array(
  'weight' => 9));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/swagger-ui.js', array(
  'weight' => 10));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/highlight.7.3.pack.js', array(
  'weight' => 11));
$apim_session = &_ibm_apim_get_apim_session();
$ibm_apim_js_settings = array(
  'url' => $GLOBALS['base_url'] . '/?q=ibm_apim/swaggerjson/' . $content['api_apiid'][0]['#markup'] . '/' . $content['api_version'][0]['#markup'],
  'context_header' => $apim_session['org'] . '.' . $apim_session['env']);
if (isset($apim_session['auth'])) {
  $ibm_apim_js_settings['basic_auth'] = 'Basic ' . $apim_session['auth'];
}

drupal_add_js(array('ibm_apim' => $ibm_apim_js_settings), 'setting');

?>
<article id="node-<?php print $node->nid; ?>"
	class="<?php print $classes; ?> clearfix" <?php print $attributes; ?>>

  <?php print $unpublished; ?>

  <?php print render($title_prefix); ?>

  <div class="portal">
		<div class="apimTitleFloat">
			<p class="apimTitle">
				<span><?php print $title; ?></span>
			</p>
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
		<div class="planSelector">
    <?php
    if (user_is_logged_in()) {
      if (isset($plans) && count($plans) == 1) {
        $buttontarget = url("plan/" . check_plain($plans[0]['id']) . "/" . check_plain($plans[0]['version']));
        print "<button type='button' onclick=\"location.href='" . $buttontarget . "'\">" . t('Use this API') . "</button>";
      }
      elseif (isset($plans) && count($plans) > 1) {
        print '<ul class="menu apimenu"><li><a href="#">' . t('Select a plan') . ' <span class="dropit-icon ui-icon-triangle-1-s"></span></a>';
        print '<ul id="dropdown-menu" class="dropdown-menu">';
        foreach ($plans as $plan) {
          print '<li title="' . check_plain($plan['name']) . '">' . l(check_plain($plan['name']), 'plan/' . check_plain($plan['id']) . '/' . check_plain($plan['version']), array(
            'html' => TRUE,
            'attributes' => array('class' => array('elipsis-names')))) . '</li>';
        }
        print '</ul></li></ul>';
      }
    } else {
      print '<span>'.t("Login to use this API").'</span>';
    }
    ?>

    </div>

		<div class="section apimMainContent">
			<label for="api_description" class="label apimField apimDescription">Description</label>
			<div id="api_description"><?php print $api_description[0]['safe_value']; ?></div>
		</div>

  <?php
  // API Documentation
  $docs = api_documents_contents($content['api_apiid'][0]['#markup'], $content['api_version'][0]['#markup']);
  if (isset($docs) && count($docs) > 0) {
    print '<div class="section" class="clearBoth">';
    print '<span class="apiDocTitle">Documentation</span><br/>';

    print '<div class="apiDocRow">';
    foreach ($docs as $doc) {
      $leftdiv = "";
      $rightdiv = "";
      $leftdiv = $leftdiv . '<div class="docFile"><div class="docLink">';
      if ($doc['docURL'] != '') {
        $doctypeClass = api_getCSSforMimetype($doc['mimeType']);
        $linkContent = '<div class="apiDocumentIcon ' . $doctypeClass . '"></div><div class="apiDocumentFileName">' . check_plain($doc['name']) . '</div>';
        $leftdiv = $leftdiv . l($linkContent, 'api/docdownload/'. check_plain($content['api_apiid'][0]['#markup']) . '/v' . check_plain($content['api_version'][0]['#markup']) . '/' . check_plain($doc['name']), array(
          'attributes' => array('target' => '_blank'),
          'html' => TRUE));
      }
      $leftdiv = $leftdiv . '</span></div></div>';
      $rightdiv = $rightdiv . '<div>' . check_plain($doc['description']) . '</div>';
      print '<div class="apiDocLeft">';
      print $leftdiv;
      print '</div><div class="apiDocRight">';
      print $rightdiv;
      print '</div>';
      print '<div class="clearBoth"></div>';
    }
    print '</div></div>';
  }
  ?>


    <div class="section">
			<label for="api_identify" class="sectionHeader"><?php print t('Identify your application using')?></label>
			<span class="highlightText"
				id="api_identify"><?php print ibm_apim_get_ident_label($api_authorization[0]['value']) ?></span>
			<span class="explanationText">&nbsp;-&nbsp;&nbsp;&nbsp;</span><span
				class="explanationText">
  <?php print ibm_apim_get_ident_explanation($api_authorization[0]['value']); ?>
  </span>
		</div>

		<div class="section">
			<label for="api_auth"
				class="sectionHeader"><?php print t('Authenticate using')?></label>
			<span class="highlightText" id="api_auth"><?php print ibm_apim_get_auth_label($api_authentication[0]['value']) ?></span>
			<span class="explanationText">&nbsp;-&nbsp;&nbsp;&nbsp;</span><span
				class="explanationText">
  <?php

  switch ($api_authentication[0]['value']) {
    case 'basic' :
      print t('You must always provide your user ID and password to use this API.') . '</span>';
      break;
    case 'none' :
      print t('You can access this API without authentication.') . '</span>';
      break;
    case 'oauth' :

      // TODO put real OAUTH variables in here
      print t('You can use OAuth 2 to access this API.') . '</span>';
      print '<div class="oauthContainer">';
      print '<div><span class="explanationText">' . t('Authorization endpoint URL:') . ' </span>';
      print '<span class="oauthText"></span></div>';
      print '<div><span class="explanationText">' . t('Token endpoint URL:') . ' </span>';
      print '<span class="oauthText"></span></div>';
      print '<div><span class="explanationText">' . t('Default scope:') . ' </span>';
      print '<span class="oauthText"></span></div>';
      print '</div>';
      break;
  }
  ?>












		</div>

  <?php

  drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/call_swagger.js', array(
    'weight' => 20));
  ?>

  <div class="swagger-section">
			<div id="header">
				<div class="swagger-ui-wrap">
					<form id="api_selector">
     <?php
     if (!empty($apps)) {
       print '<div class="input clientIDSelector"><label for="input_clientKey">'.t("Client ID:").'</label> <select id="input_clientKey">';
       print '<option selected disabled>'. t('Select an application').'</option>';
       foreach ($apps as $app) {
          print '<option value="' . check_plain($app['clientID']) . '">'. check_plain($app['name']).'</option>';
       }
       print '</select></div>';
     } else {
       print '<div class="input clientIDSelector"><label for="input_clientKey">'.t("Client ID:").'</label> <select disabled id="input_clientKey" title="' . t("Register an App to specify a Client ID") . '"><option selected disabled>'. t('Register an App to specify a Client ID').'</option></select></div>';
     }
     ?>
     <?php if (user_is_logged_in()) { ?>
     <div class="input clientSecretSelector">
							<label for="input_secretKey"><?php print t("Client Secret:"); ?></label><input
								type="text" name="secretKey" id="input_secretKey"
								placeholder="clientSecret"/>
						</div>
						<?php } ?>
					</form>
					<div>
						<h2><?php print t("API Resources"); ?></h2>
    <?php print t("Client Identification"); ?></div>
				</div>
			</div>
			<div id="message-bar" class="swagger-ui-wrap" style="display: none;">&nbsp;</div>
			<div id="swagger-ui-container" class="swagger-ui-wrap"></div>
		</div>

		<div <?php print $content_attributes; ?>>
  <?php
  hide($content['comments']);
  hide($content['links']);
  // print render($content);
  if (isset($content['field_tags'])) {
    print render($content['field_tags']);
  }
  $autocreate_forum = variable_get('ibm_apim_autocreate_apiforum', 1);
  if ($autocreate_forum == 1) {
    print "<div class=\"apiForumLink\">" . $apiforumlink . "</div>";
  }
  ?>
  </div>

  <?php if ($links = render($content['links'])): ?>
    <nav <?php print $links_attributes; ?>><?php print $links; ?></nav>
  <?php endif; ?>

  <?php print render($content['comments']); ?>















</article>
