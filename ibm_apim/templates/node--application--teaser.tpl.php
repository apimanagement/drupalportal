<?php

/**
 * @file
 * Default teaser theme implementation for applications.
 *
 * @see template_preprocess()
 * @see template_preprocess_application()
 * @see template_process()
 * @see theme_application()
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
			  if (is_numeric($application_updated[0]['value'])) {
			    $epoch = (int)($application_updated[0]['value'] / 1000);
			    $updateddate = new DateTime("@$epoch");
			  } else {
			    $updateddate = new DateTime($application_updated[0]['value']);
			  }
			   print $updateddate->format('Y-m-d');
    } catch (Exception $e) {
    } ?></div>
     <?php if (isset($appimagedata) && !empty($appimagedata)) : ?>
        <div>
						<div class="apimIcon">
							<img src="<?php print $appimagedata; ?>" height="100" width="100"
								alt="<?php print $title; ?>" />
						</div>
     <?php else: ?>
        <div>
							<div class="apimIcon" style="display: none"></div>
      <?php endif; ?>
      <div class="apimSummaryDescription ellipsis">
								<p><?php print $application_description[0]['safe_value']; ?></p>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
</article>
