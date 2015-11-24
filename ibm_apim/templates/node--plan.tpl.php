<?php

/**
 * @file
 * Default theme implementation for plans.
 *
 *
 * @see template_preprocess()
 * @see template_preprocess_plan()
 * @see template_process()
 * @see theme_plan()
 *
 * @ingroup themeable
 */
drupal_add_library('system', 'ui.dialog');

drupal_add_library('system', 'ui.accordion');
drupal_add_js('jQuery(document).ready(function(){
      jQuery("div#accordion").accordion({
        header: "> div > h3",
        collapsible: true,
        active: false,
        heightStyle: "content",
      });
    });', 'inline');

?>
<article id="node-<?php print $node->nid; ?>"
	class="<?php print $classes; ?> clearfix" <?php print $attributes; ?>>

  <?php print $unpublished; ?>

  <?php print render($title_prefix); ?>

  <div class="portal">
		<div class="apimTitleContainer plan">
			<div class="planName displayInlineTop">
			<?php
			$showversion = variable_get('ibm_apim_show_versions', 1);
			$versiontext = '';
			if ($showversion == 1) {
			  $versiontext = ' (v' . $plan_version[0]['value'] . ')';
			}
			print "<div class='planName'>" . $title . $versiontext . "</div>";
				?>
				<div class="apimUpdated"><?php try {
			  if (is_numeric($plan_updated[0]['value'])) {
			    $epoch = (int)($plan_updated[0]['value'] / 1000);
			    $updateddate = new DateTime("@$epoch");
			  } else {
			    $updateddate = new DateTime($plan_updated[0]['value']);
			  }
			  print $updateddate->format('Y-m-d');
    } catch (Exception $e) {
    } ?></div>
								<?php
  if (isset($content['field_planrating'])) {
    $content['field_planrating']['#label_display'] = 'hidden';
    print render($content['field_planrating']);
  }
  ?>
			</div>
			<div class="authInfo displayInlineTop">
		  <?php

    switch ($plan_requiresapproval[0]['value']) {
      case '1' :
        $usageTitle = t("Restricted");
        $usageText = t("This plan requires approval to use.");
        break;
      default :
        $usageTitle = t("Not restricted");
        $usageText = t("This plan is available to use without approval.");
        break;
    }
    ?>
			<div class="highlightText"><?php print $usageTitle ?></div>
				<div class="explanationText"><?php print $usageText ?></div>
			</div>

		   <div class="planSelector">
		   <?php
		 if (module_exists('application')) {
		   $isdev = ibm_apim_check_is_developer();
		   if ((isset($isdev) && $isdev == TRUE) && (!isset($details['subscribable']) || $details['subscribable'] != false)) {
		      print '<button type="button" id="planSignupButton"
		         data-href="?q=plan/subscribe/' . $plan_apiid[0]['safe_value'] . '/' . $plan_version[0]['safe_value']. '"
		         data-title="'.t('Select an application').'" data-name="content"
		         data-rel="width:500;resizable:false;position:[center,60]"
		         class="simple-dialog my-link-class">'. t('Subscribe').'</button>';
		   }
		 }
		   ?>

		   </div>
        </div>

		<div class="clearBoth section apimMainContent">
			<label class="label apimField apimDescription"><?php print t('Description'); ?></label>
			<div id="app_description">
						   <?php
			   if (module_exists('markdown')) {
                 print _filter_markdown($plan_description[0]['safe_value'], null);
               } else {
                 print '<p>'. $plan_description[0]['safe_value'] .'</p>';
               }
               ?>
			</div>
		</div>
<?php
if (is_array($customfields) && count($customfields) > 0) {
  foreach($customfields as $customfield) {
    print render($content[$customfield]);
  }
}

if (module_exists('api')) {
	print '<div class="includedAPIsTitle">'. t('APIs included') .'</div>';
	print '<div id="accordion">';

    foreach ($apis as $api) {
      switch ($api['authorization']) {
        case 'clientID' :
          $ident = t('Client ID');
          break;
        case 'clientIDAndSecret' :
          $ident = t('Client ID & Client Secret');
          break;
        case 'none' :
          $ident = t('None');
          break;
      }
      switch ($api['authentication']) {
        case 'basic' :
          $auth = t('Basic Auth');
          break;
        case 'none' :
          $auth = t('None');
          break;
        case 'oauth' :
          $auth = t('OAuth');
          break;
      }
      $showversion = variable_get('ibm_apim_show_versions', 1);
      $versiontext = '';
      if ($showversion == 1) {
        $versiontext = ' (v' . $api['version'] . ')';
      }
      print "<div><h3>" . $api['name'] . $versiontext . "</h3>";
      print "<div class='portalApi animateMaxHeight'>
		<div class='tableHeaderBackground clearTable'>
			<div class='column resourceMethod'>". t('Verb') ."</div>
			<div class='column ascendingSort resourcePathShort'>". t('Path') ."</div>
			<div class='column resourceName'>". t('Name') ."</div>
			<div class='column resourceDesc'>". t('Description') ."</div>
			<div class='column resourceIdentification'>". t('Identification') ."</div>
			<div class='column resourceAuthentication'>". t('Authentication') ."</div>
			<div class='column resourceRateLimit'>". t('Rate Limit') ."</div>
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
		 <div class='displayInlineTop boundedText tableLabel'>". t('Identification:') ."</div>
		 <div class='displayInlineTop resourceIdentification'>" . check_plain($ident) . "</div>
		 <div class='displayInlineTop boundedText tableLabel'>". t('Authentication:') ."</div>
		 <div class='displayInlineTop resourceAuthentication'>" . check_plain($auth) . "</div>
		 <div class='displayInlineTop boundedText tableLabel'>". t('Rate Limit:') ."</div>
		 <div class='displayInlineTop resourceRateLimit'>" . $ratelimitstr . "</div>
	   </div>";
      }
      print "</div></div></div>";
    }

 print "</div>";
}
?>

		<div <?php print $content_attributes; ?>>
  <?php
  hide($content['comments']);
  hide($content['links']);
  if (isset($content['field_plantags'])) {
    print render($content['field_plantags']);
  }
  ?>
  </div>

  <?php if ($links = render($content['links'])): ?>
    <nav <?php print $links_attributes; ?>><?php print $links; ?></nav>
  <?php endif; ?>

  <?php print render($content['comments']); ?>
  </div>
</article>
