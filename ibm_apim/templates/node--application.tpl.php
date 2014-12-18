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
<article id="node-<?php print $node->nid; ?>"
	class="<?php print $classes; ?> clearfix" <?php print $attributes; ?>>

  <?php print $unpublished; ?>

  <?php print render($title_prefix); ?>

  <div class="portal">
		<div class="apimTitleFloat">
			<p class="apimTitle">
				<span><?php print $title; ?></span>
				<?php if (isset($notificationsettings) && isset($notificationsettings['enabled'])) {
				  print $notificationsettings_enabledlink;
				} else {
				  print $notificationsettings_disabledlink;
				}
				print $editlink; print $deletelink;?>
      </p>
			<div class="apimUpdated"><?php try {
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

		<div class="appIcon">
			<img src="<?php print $appimagedata ?>" height="100" width="100"
				alt="No Image" /><br />
    <?php

print $uploadimagelink;
    if ($appimagedata && !(empty($appimagedata))) {
      print " " . $removeimagelink;
    }
    ?>

    </div>
	</div>

	<div class="clearBoth section apimMainContent">
		<label for="app_description" class="label apimField apimDescription"><?php print t('Description');?></label>
		<div id="app_description"><?php print $application_description[0]['safe_value']; ?></div>
	</div>

	<div class="clearBoth section apimMainContent">
		<label for="app_client_id" class="label apimField apiClientID"><?php print t('Client ID')?></label>
		<div id="app_client_id" class="app_client_id">
			<input class="toggle-password" id="clientID" type="password" readonly
				value="<?php print $appclientid; ?>">
			<div class="passwordToggleContainer">
				<input type="checkbox" id="show-clientID"> <label
					for="show-clientID">Show Client ID</label> &nbsp;&nbsp;&nbsp;&nbsp;<?php print $resetclientidlink; ?>
    </div>
		</div>
		<label for="app_oauth_redirecturi"
			class="label apimField apiOauthRedirectionURL"><?php print t('OAuth Redirection URL');?></label>
		<div id="app_oauth_redirecturi" class="app_oauth_redirecturi"><?php print $application_oauthredirecturi[0]['safe_value']; ?></div>
	</div>

	<div class="clearBoth section apimMainContent">
		<label class="label apimField apiClientSecret"><?php print t('Client Secret');?></label>
		<div class="client_secret"><?php print $verifysecretlink; ?>
    &nbsp;&nbsp;&nbsp;&nbsp;
    <?php print $resetsecretlink; ?></div>
	</div>
	<div class="clearBoth portal section apimMainContent">
		<label class="label apimField"><?php print t('Subscribed Plans');?></label>
    <?php

foreach ($subscriptions as $sub) {
      $pendingapp = '';
      if (isset($sub['approved']) && $sub['approved'] == false) {
        $pendingapp = ' (' . t('Pending Approval') . ')';
      }
      print "<div class='appPlanName'>" . check_plain($sub['name']) . $pendingapp . " <span class='planUnsubscribeIcon'>" . t(' <a href="!link"><img title="!title" src="' . file_create_url(drupal_get_path('module', 'ibm_apim') . '/images/remove.png') . '" height="16" width="16" alt="!title" /></a> ', array(
        '!title' => t('Unsubscribe'),
        '!link' => url('application/' . $variables['application_apiid'][0]['safe_value'] . '/unsubscribe/' . check_plain($sub['subId'])))) . "</span></div>";
      print "<div id='accordion'>";
      foreach ($sub['apis'] as $api) {
        $ident = ibm_apim_get_ident_label($api['authorization']);
        $auth = ibm_apim_get_auth_label($api['authentication']);
        print "<div><h3>" . check_plain($api['name']) . "</h3>";
        print "<div class='portalApi animateMaxHeight'>
		<div class='tableHeaderBackground clearTable'>
			<div class='column resourceMethod'>" . t('Verb') . "</div>
			<div class='column ascendingSort resourcePathShort'>" . t('Path') . "</div>
			<div class='column resourceName'>" . t('Name') . "</div>
			<div class='column resourceDesc'>" . t('Description') . "</div>
			<div class='column resourceRateLimit'>" . t('Rate Limit') . "</div>
		</div>
	 <div class='resourceView resourcePlanView'>";
        foreach ($api['resources'] as $resource) {
          print "<div class='displayInlineTop resourceHeadline'>
		 <div class='displayInlineTop resourceMethod resourceMethodBadge " . strtoupper(check_plain($resource['verb'])) . "'>" . strtoupper(check_plain($resource['verb'])) . "</div>
		 <div class='displayInlineTop resourcePathShort boundedText' title='" . check_plain($api['context']) . check_plain($resource['path']) . "'>" . check_plain($api['context']) . check_plain($resource['path']) . "</div>
		 <div class='displayInlineTop resourceName boundedText' title='" . check_plain($resource['name']) . "'>" . check_plain($resource['name']) . "</div>
		 <div class='displayInlineTop resourceDesc boundedText' title='" . check_plain($resource['description']) . "'>" . check_plain($resource['description']) . "</div>
		 <div class='displayInlineTop boundedText tableLabel'>" . t('Rate Limit:') . "</div>
		 <div class='displayInlineTop resourceRateLimit'>" . check_plain($resource['rateLimit']['numRequests']) . " requests per " . check_plain($resource['rateLimit']['timePeriod']) . " " . check_plain($resource['rateLimit']['timeScale']) . "</div>
	   </div>";
        }
        print "</div></div></div>";
      }
      print "</div>";
    }
    ?>
    </div>

	<div <?php print $content_attributes; ?>>
  <?php
  hide($content['comments']);
  hide($content['links']);
  if (isset($content['field_tags'])) {
    print render($content['field_tags']);
  }
  ?>
  </div>

  <?php if ($links = render($content['links'])): ?>
    <nav <?php print $links_attributes; ?>><?php print $links; ?></nav>
  <?php endif; ?>

  <?php print render($content['comments']); ?>



</article>
