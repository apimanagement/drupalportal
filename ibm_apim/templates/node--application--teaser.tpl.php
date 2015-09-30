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

	<?php $showplaceholders = variable_get('ibm_apim_show_placeholder_images', 1);?>
	<div class="apimSummaryContainer">
		<div class="apimOuterContainer">
			<div class="apimSummary">
				<div class="apimInnerContainer">
	 <?php if (isset($content['application_image'])) : ?>
						<div class="apimIcon">
							<?php print render($content['application_image']); ?>
						</div>
     <?php elseif($showplaceholders != 0): ?>
				<div class="apimIcon">
					<div class="field field-name-api-image field-type-image field-label-hidden view-mode-teaser">
					<div class="field-items">
					<figure class="clearfix field-item even">
					 <img typeof="foaf:Image" class="image-style-none" src="<?php print file_create_url(drupal_get_path('module', 'ibm_apim') . '/images/placeholder_image.png');?>" width="123" height="123" alt="">
					</figure>
					</div>
					</div>
				</div>
	  <?php else: ?>
	     <div class="apimIcon" style="display:none"></div>
      <?php endif; ?>
      <div class="apimTeaserRating">
      <?php if (isset($content['field_applicationrating'])) {
    $content['field_applicationrating']['#label_display'] = 'hidden';
    print render($content['field_applicationrating']);
  } ?></div>
					<h3 class="apimSummaryTitle"><?php print $titlelink; ?></h3>
					<div class="apimUpdated">
	<?php try {
			  if (is_numeric($application_updated[0]['value'])) {
			    $epoch = (int)($application_updated[0]['value'] / 1000);
			    $updateddate = new DateTime("@$epoch");
			  } else {
			    $updateddate = new DateTime($application_updated[0]['value']);
			  }
			   print $updateddate->format('Y-m-d');
    } catch (Exception $e) {
    } ?></div>

					   <div class="apimSummaryDescription">
					                             <?php
                        print '<div class="apimFade" title="'. $application_description[0]['safe_value'] .'">';
                        if (module_exists('markdown')) {
                          print _filter_markdown($application_description[0]['safe_value'], null);
                        } else {
                          print '<p>'. $application_description[0]['safe_value'] .'</p>';
                        }
                        print '</div>';
                        ?>
					   </div>
					</div>
				</div>
			</div>
		</div>
</article>
