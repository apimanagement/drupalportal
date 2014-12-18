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
		<div class="apimTitleFloat">
			<div class="planName displayInlineTop">
				<div class="planName"><?php print $title; ?></div>
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
			<div class="displayInlineTop">
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
		</div>

		<div class="planSelector">
			<button type="button" id="planSignupButton"
				data-href="?q=plan/subscribe/<?php print $plan_apiid[0]['safe_value'];?>/<?php print $plan_version[0]['safe_value'];?>"
				data-title="Select an application" data-name="content"
				data-rel="width:500;resizable:false;position:[center,60]"
				class="simple-dialog my-link-class">Use this Plan</button>
		</div>

		<div class="clearBoth section apimMainContent">
			<label for="app_description" class="label apimField apimDescription">Description</label>
			<div id="app_description"><?php print $plan_description[0]['safe_value']; ?></div>
		</div>

		<div class="includedAPIsTitle">APIs included</div>
		<div id="accordion">
    <?php

    foreach ($apis as $api) {
      switch ($api['authorization']) {
        case 'clientID' :
          $ident = "Client ID";
          break;
        case 'clientIDAndSecret' :
          $ident = "Client ID & Client Secret";
          break;
        case 'none' :
          $ident = "None";
          break;
      }
      switch ($api['authentication']) {
        case 'basic' :
          $auth = "Basic Auth";
          break;
        case 'none' :
          $auth = "None";
          break;
        case 'oauth' :
          $auth = "OAuth";
          break;
      }
      print "<div><h3>" . $api['name'] . "</h3>";
      print "<div class='portalApi animateMaxHeight'>
		<div class='tableHeaderBackground clearTable'>
			<div class='column resourceMethod'>Verb</div>
			<div class='column ascendingSort resourcePathShort'>Path</div>
			<div class='column resourceName'>Name</div>
			<div class='column resourceDesc'>Description</div>
			<div class='column resourceIdentification'>Identification</div>
			<div class='column resourceAuthentication'>Authentication</div>
			<div class='column resourceRateLimit'>Rate Limit</div>
		</div>
	 <div class='resourceView resourcePlanView'>";
      foreach ($api['resources'] as $resource) {
        print "<div class='displayInlineTop resourceHeadline'>
		 <div class='displayInlineTop resourceMethod resourceMethodBadge " . strtoupper(check_plain($resource['verb'])) . "'>" . strtoupper(check_plain($resource['verb'])) . "</div>
		 <div class='displayInlineTop resourcePathShort boundedText' title='" . check_plain($api['context']) . check_plain($resource['path']) . "'>" . check_plain($api['context']) . check_plain($resource['path']) . "</div>
		 <div class='displayInlineTop resourceName boundedText' title='" . check_plain($resource['name']) . "'>" . check_plain($resource['name']) . "</div>
		 <div class='displayInlineTop resourceDesc boundedText' title='" . check_plain($resource['description']) . "'>" . check_plain($resource['description']) . "</div>
		 <div class='displayInlineTop boundedText tableLabel'>Identification:</div>
		 <div class='displayInlineTop resourceIdentification'>" . check_plain($ident) . "</div>
		 <div class='displayInlineTop boundedText tableLabel'>Authentication:</div>
		 <div class='displayInlineTop resourceAuthentication'>" . check_plain($auth) . "</div>
		 <div class='displayInlineTop boundedText tableLabel'>Rate Limit:</div>
		 <div class='displayInlineTop resourceRateLimit'>" . check_plain($resource['rateLimit']['numRequests']) . " requests per " . check_plain($resource['rateLimit']['timePeriod']) . " " . check_plain($resource['rateLimit']['timeScale']) . "</div>
	   </div>";
      }
      print "</div></div></div>";
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
  </div>
</article>
