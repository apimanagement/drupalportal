<?php

/**
 * @file
 * Default theme implementation for applications.
 *
 *
 * @see template_preprocess()
 * @see template_preprocess_application()
 * @see template_process()
 * @see theme_application()
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
      jQuery(".detailsLink").on("click", null, null, function(event) {
        window.location.href = jQuery(this).attr("href");
        event.preventDefault();
      });
    });', 'inline');
drupal_add_js('jQuery(document).ready(function(){
        jQuery("#show-clientID").change(function(){
          jQuery("#clientID").hideShowPassword(jQuery(this).prop("checked"));
        });
      });', 'inline');
drupal_add_js('jQuery(document).ready(function(){
        jQuery("#show-clientSecret").change(function(){
          jQuery("#clientSecret").hideShowPassword(jQuery(this).prop("checked"));
        });
      });', 'inline');
?>
<div class="pagebreadcrumb"><?php print l("< " .t('Back to Apps'), 'application');?></div>
<article id="node-<?php print $node->nid; ?>"
	class="<?php print $classes; ?> clearfix" <?php print $attributes; ?>>

  <?php print $unpublished; ?>

  <?php print render($title_prefix); ?>
  <?php $showplaceholders = variable_get('ibm_apim_show_placeholder_images', 1);?>

  <div class="portal">
  <?php
  if (isset($application_state[0]['safe_value']) && $application_state[0]['safe_value'] == 'SUSPENDED') {
    print '<div class="appSuspended">' . t('This application is currently suspended. The client id is blocked from accessing APIs.') . '</div>';
  }
  ?>

    <div class="apimTopSection">
    		<div class="apimAPIImage">
    		<?php if (isset($content['application_image']) && !(empty($content['application_image']))) {
		  print render($content['application_image']);
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
		  print "<span class=\"appNoImage\">". t("No Image") ."</span>";
		}?>
    <?php

print '<span class="apimImageActions">'.$uploadimagelink;
    if (isset($content['application_image']) && !(empty($content['application_image']))) {
      print " | " . $removeimagelink . '</span>';
    }
    ?>

    </div>
    <div class="centralspacer"></div>
    <div class="apimInfoSection">
		<div class="apimTitleContainer">
			<p class="apimTitle">
				<span><?php print $title; ?></span>
				<?php print '<span class="apimAppActions">' . $analyticslinks . ' | ' . $notificationsettingslink;
				if (isset($isdev) && $isdev == TRUE) {
				  print ' | '. $editlink . ' | ' . $deletelink;
				}
				print '</span>';
				?>
      </p>
			<div class="apimUpdated clearBoth"><?php try {
			  if (is_numeric($application_updated[0]['value'])) {
			    $epoch = (int)($application_updated[0]['value'] / 1000);
			    $updateddate = new DateTime("@$epoch");
			  } else {
			    $updateddate = new DateTime($application_updated[0]['value']);
			  }
      print $updateddate->format('Y-m-d');
    } catch (Exception $e) {
    } ?></div>
					<?php
  if (isset($content['field_applicationrating'])) {
    $content['field_applicationrating']['#label_display'] = 'hidden';
    print render($content['field_applicationrating']);
  }
  ?>
		</div>

	<div class="clearBoth section apimMainContent">
		<label class="label apimField apimDescription"><?php print t('Description');?></label>
		<div id="app_description"><?php print $application_description[0]['safe_value']; ?></div>
	</div>


	<?php
 if (isset($isdev) && $isdev == TRUE) {
   print '<div class="credentialList clearBoth section apimMainContent"><div class="credentialTitle">' . t('Client Credentials') . '<span class="addCredentials">' . $addcredentialslink . '</span></div>';
    $credcount = count($credentials);
    $index = 0;
    foreach ($credentials as $cred) {
      drupal_add_js('jQuery(document).ready(function(){
        jQuery("#show-clientID'.$index.'").change(function(){
          jQuery("#clientID'.$index.'").hideShowPassword(jQuery(this).prop("checked"));
        });
      });', 'inline');
     print '<div class="credentialTable"><div class="credentialPreSpacer"><div class="credentialSpacer"></div><div class="credentialSpacer"></div></div><div class="credentialContainer">
       <div class="credentialInfo">
        <div class="credentialInfoDescription">'. check_plain($cred['description']) .'</div>
        <label for="clientID" class="label apimField apiClientID">'. t('Client ID') .'</label>
        <div id="app_client_id" class="app_client_id">
		  <input class="toggle-password" id="clientID'.$index.'" type="password" readonly value="'. $cred['clientID'] .'" />
          <div class="passwordToggleContainer">
			<input type="checkbox" id="show-clientID'.$index.'" /> <label for="show-clientID'.$index.'">'. t('Show'). '</label> &nbsp;&nbsp;&nbsp;&nbsp; <a class="buttonLink" href="' . url("application/" . $application_apiid[0]['value'] . "/reset-clientid/" . $cred['id']) . '">' . t('Reset') . '</a>
          </div>
		</div>
		<label for="clientSecret" class="label apimField apiClientSecret">'. t('Client Secret') . '</label>
		<div class="client_secret">
          <input id="clientSecret'.$index.'" class="clientSecretInput" disabled readonly /> <a class="buttonLink" href="' . url("application/" . $application_apiid[0]['value'] . "/verify/". $cred['id']) . '">' . t('Verify') . '</a> &nbsp;&nbsp;&nbsp;&nbsp; <a class="buttonLink" href="' . url("application/" . $application_apiid[0]['value'] . "/reset-secret/" . $cred['id']) . '">' . t('Reset') . '</a>
        </div>
       </div>';

       print '<div class="credentialActions">
        <a href="' . url("application/" . $application_apiid[0]['value'] . "/update-clientcreds/". $cred['id']) . '">' . t('Update') . '</a>';
       if ($credcount > 1) {
          print ' | <a href="' . url("application/" . $application_apiid[0]['value'] . "/delete-clientcreds/". $cred['id']) . '">' . t('Delete') . '</a>';
       }
       print '</div>';
     print '</div><div class="credentialPostSpacer"><div class="credentialSpacer"></div><div class="credentialSpacer"></div></div></div>';
     $index++;
   }
   print '</div>';
  }
		?>

	 <div class="clearBoth section apimMainContent">
     <label class="label apimField apiOauthRedirectionURL"><?php print t('OAuth Redirection URL');?></label>
		<div id="app_oauth_redirecturi" class="app_oauth_redirecturi"><?php print $application_oauthredirecturi[0]['safe_value']; ?></div>

    </div>
        <div>
	<?php if (is_array($customfields) && count($customfields) > 0) {
  			foreach($customfields as $customfield) {
   	 		  print render($content[$customfield]);
  			}
		  } ?>
	</div>
  </div>
 </div>
<?php
  if (module_exists('plan')) {
	print '<div class="clearBoth portal section apimMainContent apimPlansApis">';
	print '<span class="apimTitle">' . t('Plans & APIs'). '</span>';

  foreach ($subscriptions as $sub) {
      $pendingapp = '';
      if (isset($sub['approved']) && $sub['approved'] == false) {
        $pendingapp = ' (' . t('Pending Approval') . ')';
      }
      $unsubscribelink = '';
      if (isset($isdev) && $isdev == TRUE) {
        $unsubscribelink = "<a href='" . url('application/' . $variables['application_apiid'][0]['safe_value'] . '/unsubscribe/' . check_plain($sub['subId'])) . "'>".t('Unsubscribe') . "</a>";
      }
      $showversion = variable_get('ibm_apim_show_versions', 1);
      $versiontext = '';
      if ($showversion == 1) {
        $versiontext = ' (v' . $sub['version'] . ')';
      }
      print "<div class='appPlanName'>" . check_plain($sub['name']) . $versiontext . $pendingapp . " <span class='planUnsubscribe'>" . $unsubscribelink . "</span></div>";
      if (isset($sub['supersededBy']) && !empty($sub['supersededBy']) && isset($isdev) && $isdev == TRUE) {
        print "<div class='migratePlanContainer'><div class='migrateButton'>" . l(t('Migrate'),'application/'.$application_apiid[0]['value'].'/migrate/' . $sub['id'] .'/'.base64_encode($sub['supersededBy'])) . "</div><div class='migratePlanText'>" . t('A new version of this plan has been published.') . "</div></div>";
      }
      print "<div id='accordion'>";
      foreach ($sub['apis'] as $api) {
        $ident = ibm_apim_get_ident_label($api['authorization']);
        $auth = ibm_apim_get_auth_label($api['authentication']);
        $versiontext = '';
        if ($showversion == 1) {
          $versiontext = ' (v' . $api['version'] . ')';
        }
        print "<div><h3>" . check_plain($api['name']) . $versiontext . "<span class='testAPILink'>" . l(t('Details'), "api/" . $api['id'] . "/" . $api['version'], array('attributes' => array('class' => array('detailsLink')))) . "</span></h3>";
        print "<div class='portalApi animateMaxHeight'>";
        		print "<div class='tableHeaderBackground clearTable'>
			<div class='column resourceMethod'>" . t('Verb') . "</div>
			<div class='column ascendingSort resourcePathShort'>" . t('Path') . "</div>
			<div class='column resourceName'>" . t('Name') . "</div>
			<div class='column resourceDesc'>" . t('Description') . "</div>
			<div class='column resourceRateLimit'>" . t('Rate Limit') . "</div>
		    <div class='column resourceAnalytics'>" . t('Analytics') . "</div>
		</div>
	 <div class='resourceView resourcePlanView'>";
        foreach ($api['resources'] as $resource) {
        if (isset($resource['rateLimit']['numRequests'])) {
          $ratelimitstr = t('@requests requests per @period @timescale', array('@requests'=> check_plain($resource['rateLimit']['numRequests']), '@period' => check_plain($resource['rateLimit']['timePeriod']), '@timescale' => check_plain($resource['rateLimit']['timeScale'])));
        } else {
          $ratelimitstr = t('unlimited');
        }
          print "<div class='displayInlineTop resourceHeadline'>
		 <div class='displayInlineTop resourceMethod resourceMethodBadge " . strtoupper(check_plain($resource['verb'])) . "'>" . strtoupper(check_plain($resource['verb'])) . "</div>
		 <div class='displayInlineTop resourcePathShort boundedText' title='" . check_plain($api['context']) . check_plain($resource['path']) . "'>" . check_plain($api['context']) . check_plain($resource['path']) . "</div>
		 <div class='displayInlineTop resourceName boundedText' title='" . check_plain($resource['name']) . "'>" . check_plain($resource['name']) . "</div>
		 <div class='displayInlineTop resourceDesc boundedText' title='" . check_plain($resource['description']) . "'>" . check_plain($resource['description']) . "</div>
		 <div class='displayInlineTop boundedText tableLabel'>" . t('Rate Limit:') . "</div>
		 <div class='displayInlineTop resourceRateLimit'>" . $ratelimitstr . "</div>";
          $ibm_apim_analytics_latencies = variable_get('ibm_apim_analytics_latencies', 1);
          $ibm_apim_analytics_successrate = variable_get('ibm_apim_analytics_successrate', 1);
          $ibm_apim_analytics_datausage = variable_get('ibm_apim_analytics_datausage', 1);
          $urlstring = NULL;
          if ($ibm_apim_analytics_datausage == 1) {
            $urlstring = 'datausage';
          } else if ($ibm_apim_analytics_successrate == 1) {
            $urlstring = 'successrate';
          } else if ($ibm_apim_analytics_latencies == 1) {
            $urlstring = 'latency';
          }
          if ((isset($sub['approved']) && $sub['approved'] == false ) || $urlstring == NULL) {
		    print "<div class='displayInlineTop resourceAnalytics'>&nbsp;</div>";
          } else {
            print "<div class='displayInlineTop resourceAnalytics'><a href='" . url('ibm_apim/analytics/'.$urlstring.'/' . $variables['application_apiid'][0]['safe_value'] . '/' . ibm_apim_base64_url_encode($resource['path']) . '/' . strtolower(check_plain($resource['verb'])). '/' . strtolower(check_plain($resource['id']))) . "'><span class='analyticsIcon'></span></a></div>";
          }
	   print "</div>";
        }
        print "</div></div></div>";
      }
      print "</div>";
    }
    print "</div>";
}
?>
	<div <?php print $content_attributes; ?>>
  <?php
  hide($content['comments']);
  hide($content['links']);
  if (isset($content['field_applicationtags'])) {
    print render($content['field_applicationtags']);
  }
  ?>
  </div>

  <?php if ($links = render($content['links'])): ?>
    <nav <?php print $links_attributes; ?>><?php print $links; ?></nav>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

</article>
