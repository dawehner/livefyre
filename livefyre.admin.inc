<?php


/**
 * Menu callback; Displays the administration settings for Livefyre.
 */
function livefyre_admin_settings() {
  $form['lf_disabled'] = array(
      '#type' => 'checkbox',
      '#title' => t('Disable Livefyre comments?'),
      '#default_value' => variable_get('livefyre_disabled', 0),
      '#description' => t('Need to kill the comments real quick-like? Prevent Livefyre comments from being displayed by checking this box.'),
      );
  $form['lf_free_version'] = array(
      '#type' => 'checkbox',
      '#title' => t('Yes, I\'m using the free version'),
      '#default_value' => variable_get('lf_free_version', 1),
      '#description' => t('Check the box if you\'re using the free version.'),
      );
  $form['advanced_settings'] = array(
      '#type' => 'fieldset',
      '#title' => 'Advanced Settings (Only works with paid version.)',
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
  );
  $form['advanced_settings']['custom_script_snippet'] = array(
      '#type' => 'textarea',
      '#title' => t('Custom Snippet'),
      '#default_value' => variable_get('custom_script_snippet', ''),
      '#rows' => 5,
      '#wysiwyg' => FALSE,
      '#description' => t("Need tracking? Or other variables for your comments? Put in your custom HTML and/or javascript here. For javascript make sure to include @<script> tags and be careful.  Messing this up could have bad effects on your site! Note:  The 'Yes, I'm using the free version' checkbox (above) needs to be unchecked in order for this to work.", array('@<script>' => '<script>')),
  );
  $form['livefyre_acct_num'] = array(
      '#type' => 'textfield',
      '#title' => t('Livefyre account number (for use with free version)'),
      '#default_value' => variable_get('livefyre_acct_num', ''),
      '#description' => t('This is the Livefyre account number, unique to each station.'),
      );
  $form['livefyre_supplied_js'] = array(
      '#type' => 'textfield',
      '#title' => t('Script supplied by Livefyre (for use with free version)'),
      '#default_value' => variable_get('livefyre_supplied_js', 'http://zor.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js'),
      '#description' => t('Post the path to the script supplied by Livefyre.  This value defaults to <em><strong>"http://zor.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js"</em></strong>.  This could change so feel free to change it if needed.'),
      );
  $form['node_types'] = array(
      '#type' => 'checkboxes',
      '#options' => node_get_types('names'),
      '#default_value' => variable_get('livefyre_node_types', array()),
      '#title' => t('Node Types'),
      '#description' => t('Display comments only on the selected node types'),
      '#multiple' => TRUE,
      );
  $form['location'] = array(
      '#type' => 'select',
      '#title' => t('Location'),
      '#description' => t('Display the livefyre comments in the given location. When "Block" is selected, the comments will appear in the <a href="@livefyre">livefyre Comments block</a>.', array('@livefyre' => url('admin/build/block'))),
      '#default_value' => variable_get('livefyre_location', 'content_area'),
      '#options' => array(
        'content_area' => t('Content Area'),
        'block' => t('Block'),
        ),
      );
  $form['weight'] = array(
      '#type' => 'select',
      '#title' => t('Weight'),
      '#description' => t('When the comments are displayed in the content area, you can change the position at which they will be shown.'),
      '#default_value' => variable_get('livefyre_weight', 50),
      '#options' => drupal_map_assoc(array(-100, -50, -25, 0, 25, 50, 100)),
      );
  $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save changes'),
      );
  return $form;
}

function livefyre_admin_settings_validate($form, &$form_state) {

}

function livefyre_admin_settings_submit(&$form_state, $form) {
  drupal_set_message(t("Your changes have been saved."));
  foreach ($form_state['node_types']['#value'] as $key => $value) {
    $lf_node_types[] = $key;
  }
  variable_set('livefyre_node_types', $lf_node_types);
  variable_set('livefyre_acct_num', $form_state['livefyre_acct_num']['#value']);
  variable_set('livefyre_disabled', $form_state['lf_disabled']['#value']);
  variable_set('livefyre_weight', $form_state['weight']['#value']);
  variable_set('livefyre_location', $form_state['location']['#value']);
  variable_set('livefyre_custom_css', $form_state['advanced_settings']['custom_css']['#value']);
  variable_set('livefyre_custom_js', $form_state['advanced_settings']['custom_js']['#value']);
  variable_set('livefyre_supplied_js', $form_state['livefyre_supplied_js']['#value']);
  variable_set('custom_script_snippet', $form_state['advanced_settings']['custom_script_snippet']['#value']);
  variable_set('lf_free_version', $form_state['lf_free_version']['#value']);

}

