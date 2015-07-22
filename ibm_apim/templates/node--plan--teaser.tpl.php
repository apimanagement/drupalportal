<?php

/**
 * @file
 * Default theme teaser implementation for plans.
 *
 * @see template_preprocess()
 * @see template_preprocess_plan()
 * @see template_process()
 * @see theme_plan()
 *
 * @ingroup themeable
 */
?>
<article id="node-<?php print $node->nid; ?>"
	class="<?php print $classes; ?> apimTeaser clearfix"
	<?php print $attributes; ?>>

    <div class="apimSummaryContainer">
		<div class="apimOuterContainer">
			<div class="apimSummary">
				<div class="apimInnerContainer">
					<h3 class="apimSummaryTitle"><?php print $titlelink; ?></h3>
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
					<div class="apimSummaryDescription">
					  <p class="apimFade" title="<?php print $plan_description[0]['safe_value']; ?>"><?php print $plan_description[0]['safe_value']; ?></p>
					</div>
				</div>
			</div>
		</div>
</article>
