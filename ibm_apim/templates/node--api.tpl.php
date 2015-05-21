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

// Add Standard .css includes from Swagger-UI Distribution
drupal_add_css(drupal_get_path('module', 'ibm_apim') . '/swaggerui/css/screen.css');

// Add Standard .js includes from Swagger-UI Distribution
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/jquery.slideto.min.js', array(
  'weight' => 2));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/jquery.wiggle.min.js', array(
  'weight' => 3));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/jquery.ba-bbq.min.js', array(
  'weight' => 4));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/handlebars-2.0.0.js', array(
  'weight' => 5));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/underscore-min.js', array(
  'weight' => 6));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/backbone-min.js', array(
  'weight' => 7));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/swagger-ui.js', array(
  'weight' => 10));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/highlight.7.3.pack.js', array(
  'weight' => 11));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/marked.js', array(
'weight' => 12));
drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/swagger-oauth.js', array(
'weight' => 13));
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
  'context_header' => $apim_session['org'] . '.' . $apim_session['env']);
if (isset($apim_session['auth'])) {
  $ibm_apim_js_settings['basic_auth'] = 'Basic ' . $apim_session['auth'];
}

drupal_add_js(array('ibm_apim' => $ibm_apim_js_settings), 'setting');

drupal_add_css(drupal_get_path('module', 'ibm_apim') . '/css/ie-9.css', array('weight' => 115, 'browsers' => array('IE' => 'lte IE 9', '!IE' => FALSE)));

?>
<div class="breadcrumb"><?php print l("< " .t('Back to APIs'), 'api');?></div>
<article id="node-<?php print $node->nid; ?>"
	class="<?php print $classes; ?> clearfix" <?php print $attributes; ?>>

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
      if (isset($doc['docURL']) && $doc['docURL'] != '') {
        $doctypeClass = api_getCSSforMimetype($doc['mimeType']);
        $linkContent = '<div class="apiDocumentIcon ' . $doctypeClass . '"></div><div class="apiDocumentFileName">' . check_plain($doc['name']) . '</div>';
        $leftdiv = $leftdiv . l($linkContent, 'api/docdownload/'. base64_encode($doc['docURL']), array(
          'attributes' => array('target' => '_blank'),
          'html' => TRUE));
      }
      $leftdiv = $leftdiv . '</span></div></div>';
      if (isset($doc['description']) && $doc['description'] != '') {
        $rightdiv = $rightdiv . '<div>' . check_plain($doc['description']) . '</div>';
      }
      print '<div class="apiDocLeft">';
      print $leftdiv;
      print '</div><div class="apiDocRight">';
      print $rightdiv;
      print '</div>';
      print '<div class="clearBoth"></div>';
    }
    print '</div></div>';
  }

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


    <div class="section">
			<label class="sectionHeader"><?php print t('Identify your application using')?></label>
			<span class="highlightText"
				id="api_identify"><?php print ibm_apim_get_ident_label($api_authorization[0]['value']) ?></span>
			<span class="explanationText">&nbsp;-&nbsp;&nbsp;&nbsp;</span><span
				class="explanationText">
  <?php print ibm_apim_get_ident_explanation($api_authorization[0]['value']); ?>
  </span>
		</div>

		<div class="section">
			<label class="sectionHeader"><?php print t('Authenticate using')?></label>
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
  ?>


		</div></div>
 </div>
 <?php
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

  drupal_add_js(drupal_get_path('module', 'ibm_apim') . '/swaggerui/lib/call_swagger.js', array(
    'weight' => 20));

if ($protocol == "soap") {
  print "<div class='swagger-section soap'>";
} else {
  print "<div class='swagger-section rest'>";
}
?>
			<div id="header">
				<div class="swagger-ui-wrap">
					<form id="api_selector">
     <?php
     if($api_authentication[0]['value'] == 'basic' || $api_authentication[0]['value'] == 'oauth') {
       print '<div class="basicauthwrapper"><div class="access"><span class="api-ic ic-off" title="'.t('Click to authenticate').'"></span></div>';
       print '<div class="auth"><span class="api-ic ic-error"></span><div id="api_information_panel" style="top: 526px; left: 776px; display: none;"></div></div></div>';
     }
     switch ($api_authorization[0]['value']) {
       case 'clientID' :
         if (!empty($apps)) {
           print '<div class="input clientIDSelector"><label for="input_clientKey">'.t("Client ID:").'</label> <select id="input_clientKey">';
           print '<option selected disabled>'. t('Select an application').'</option>';
           foreach ($apps as $app) {
             foreach ($app['appCredentials'] as $cred) {
               if (isset($cred['description']) && $cred['description'] != null) {
                 $origstring = check_plain($cred['description']);
                 $truncated = (strlen($origstring) > 13) ? substr($origstring,0,10).'...' : $origstring;
                 $descr = " - " . $truncated;
               } else {
                 $descr = '';
               }
             print '<option value="' . check_plain($cred['clientID']) . '">'. check_plain($app['name']) . $descr . '</option>';
             }
           }
           print '</select></div>';
         } else {
           $ibm_apim_show_register_app = variable_get('ibm_apim_show_register_app', 1);
           if ($ibm_apim_show_register_app == 1) {
             print '<div class="registerAppLink">' . l(t('Register a new application'), 'application/new') . '</div>';
           }
           print '<div class="input clientIDSelector"><label for="input_clientKey">'.t("Client ID:").'</label> <select disabled id="input_clientKey" title="' . t("Register an App to specify a Client ID") . '"><option selected disabled>'. t('Register an App to specify a Client ID').'</option></select></div>';
         }
         break;
       case 'clientIDAndSecret' :
         if (!empty($apps)) {
           print '<div class="input clientIDSelector"><label for="input_clientKey">'.t("Client ID:").'</label> <select id="input_clientKey">';
           print '<option selected disabled>'. t('Select an application').'</option>';
           foreach ($apps as $app) {
             print '<option value="' . check_plain($app['clientID']) . '">'. check_plain($app['name']).'</option>';
           }
           print '</select></div>';
           print '<div class="input clientSecretSelector"><label for="input_secretKey">'. t("Client Secret:").'</label>
                <input type="text" name="secretKey" id="input_secretKey" placeholder="clientSecret"/></div>';
         } else {
           $ibm_apim_show_register_app = variable_get('ibm_apim_show_register_app', 1);
           if ($ibm_apim_show_register_app == 1) {
             print '<div class="registerAppLink">' . l(t('Register a new application'), 'application/new') . '</div>';
           }
           print '<div class="input clientIDSelector"><label for="input_clientKey">'.t("Client ID:").'</label> <select disabled id="input_clientKey" title="' . t("Register an App to specify a Client ID") . '"><option selected disabled>'. t('Register an App to specify a Client ID').'</option></select></div>';
           print '<div class="input clientSecretSelector"><label for="input_secretKey">'. t("Client Secret:").'</label>
                <input type="text" disabled name="secretKey" id="input_secretKey" placeholder="Register an app to specify a clientSecret"/></div>';
         }

         break;
       case 'none' :
         break;
     }


     ?>

					</form>
					<div class="swaggerHeader">
						<span class="swaggerTitle"><?php print t("API Resources"); ?></span>
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
