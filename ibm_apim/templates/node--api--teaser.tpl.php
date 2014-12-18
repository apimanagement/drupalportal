<?php

/**
 * @file
 * Default teaser theme implementation for apis.
 *
 * @see template_preprocess()
 * @see template_preprocess_api()
 * @see template_process()
 * @see theme_api()
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
					<div class="apimUpdated">
	<?php try {
			  if (is_numeric($api_updated[0]['value'])) {
			    $epoch = (int)($api_updated[0]['value'] / 1000);
			    $updateddate = new DateTime("@$epoch");
			  } else {
			    $updateddate = new DateTime($api_updated[0]['value']);
			  }
			   print $updateddate->format('Y-m-d');
    } catch (Exception $e) {
    } ?></div>
     <?php if (isset($content['api_image'])) : ?>
        <div>
						<div class="apimIcon">
							<img src="<?php print $api_apiid[0]['safe_value'] ?>"
								height="100" width="100" alt="<?php print $title; ?>" />
						</div>';
     <?php else: ?>
        <div>
							<div class="apimIcon" style="display: none"></div>
      <?php endif; ?>
      <div class="apimSummaryDescription ellipsis">
								<p><?php print $api_description[0]['safe_value']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</article>
