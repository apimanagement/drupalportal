<?php
/**
 * @file
 * Banner block bean plugin.
 */
class BannerBlockBean extends BeanPlugin {
  private $_bean;

  /**
   * Builds extra settings for the block edit form.
   */
  public function form($bean, $form, &$form_state) {
    $form = array();
    // add JS to update editor background with current image
    $form['#attached']['js'][] = array(
        'data' => 'jQuery(document).ready(function(){
          var href = jQuery(".image-widget-data").find(".file a:first").attr("href");
          jQuery("#edit-bannercontent-und-0-value_ifr").css("background-image","url("+href+")");

          setTimeout(function () {
            var href = jQuery(".image-widget-data").find(".file a:first").attr("href");
            jQuery("#edit-bannercontent-und-0-value_ifr").css("background-image","url(\'"+href+"\')");
          }, 1000);

        });',
        'type' => 'inline');

    return $form;
  }

  /**
   * Displays the bean.
   */
  public function view($bean, $content, $view_mode = 'default', $langcode = NULL) {
    // Set the bean instance
    $this->_bean = $bean;

    $alt = '';
    $title = '';
    $url = file_create_url($bean->bannerbgimage['und'][0]['uri']);
    $width = $bean->bannerbgimage['und'][0]['width'];
    $height = $bean->bannerbgimage['und'][0]['height'];
    if (isset($bean->bannerbgimage['und'][0]['title'])) {
      $title = 'title="' . $bean->bannerbgimage['und'][0]['title'] . '"';
    }
    if (isset($bean->bannerbgimage['und'][0]['alt'])) {
      $alt = 'alt="' . $bean->bannerbgimage['und'][0]['alt'] . '"';
    }

    $markup = '<div class="banner_block" style="margin-left: auto; margin-right: auto; background-image: url(' . $url . '); width: ' . $width . 'px; height: ' . $height . 'px" ' . $alt . ' ' . $title . '>';
    $markup .= $bean->bannercontent['und'][0]['value'] . "</div>";

    // hide the raw fields since we're handling the rendering
    $id = $bean->delta;
    unset($content['bean'][$id]['bannerbgimage']);
    unset($content['bean'][$id]['bannercontent']);

    // Add the markup to the render array
    $content[] = array('#markup' => $markup);

    return $content;
  }
}