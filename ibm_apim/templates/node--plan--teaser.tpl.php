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
					                          <?php
                        print '<div class="apimFade" title="'. $plan_description[0]['safe_value'] .'">';
                        if (module_exists('markdown')) {
                          print _filter_markdown($plan_description[0]['safe_value'], null);
                        } else {
                          print '<p>'. $plan_description[0]['safe_value'] .'</p>';
                        }
                        print '</div>';
                        ?>
					</div>
					<div class="extraFields">
						<?php if (is_array($customfields) && count($customfields) > 0) {
  							foreach($customfields as $customfield) {
   	 							print render($content[$customfield]);
  							}
						} ?>
						</div>
				</div>
			</div>
		</div>
</article>
