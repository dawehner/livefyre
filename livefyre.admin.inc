<?php
/**
 * @file
 * Administrative page callbacks for the Livefyre module.
 */

/**
 * Menu callback; Displays the administration settings for Livefyre.
 */
function livefyre_admin_settings() {
  $form = array();
  $form['livefyre_disabled'] = array(
    '#type' => 'checkbox',
    '#title' => t('Disable Livefyre comments?'),
    '#default_value' => variable_get('livefyre_disabled', 0),
    '#description' => t('Need to kill the comments real quick-like? Prevent Livefyre comments from being displayed by checking this box.'),
  );
  $form['livefyre_acct_num'] = array(
    '#type' => 'textfield',
    '#title' => t('Livefyre account number'),
    '#default_value' => variable_get('livefyre_acct_num', ''),
    '#description' => t('This is the Livefyre account number, unique to each account.'),
  );
  
  if (user_access('add livefyre html')) {
  $form['livefyre_supplied_js'] = array(
    '#type' => 'textfield',
    '#title' => t('Path to script supplied by Livefyre'),
    '#default_value' => variable_get('livefyre_supplied_js', ''),
    '#description' => t('Post the path to the script supplied by Livefyre.  According to the current Livefyre site, the current Livefyre javascript file is <em><strong>"http://zor.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js"</em></strong> but this could change so feel free to enter something else if needed. Paths only. No quotation marks allowed.'),
  );
  }
  
  if (user_access('add livefyre html')) {
  $form['advanced_settings'] = array(
    '#type' => 'fieldset',
    '#title' => 'Advanced Settings (Custom HTML, CSS, Javascript)',
    '#collapsible' => TRUE,
    '#collapsed' => FALSE,

  );
  $form['advanced_settings']['livefyre_custom_script_snippet'] = array(
    '#type' => 'textarea',
    '#title' => t('Custom HTML Snippet'),
    '#default_value' => variable_get('livefyre_custom_script_snippet', ''),
    '#rows' => 5,
    '#wysiwyg' => FALSE,
    '#description' => t("Need tracking? Or other variables for your Livefyre comments? Put in your custom HTML and/or javascript here. For javascript make sure to include @<script> tags and be careful.  Messing this up could have bad effects on your site!", array('@<script>' => '<script>')),
  );
  }
  
  $form['livefyre_node_types'] = array(
    '#type' => 'checkboxes',
    '#default_value' => variable_get('livefyre_node_types', array()),
    '#title' => t('Show Livefyre Comments on these node Types'),
    '#description' => t('Display comments only on the selected node types'),
    '#options' => node_get_types('names'),
  );
  $form['livefyre_location'] = array(
    '#type' => 'select',
    '#title' => t('Location'),
    '#description' => t('Display the livefyre comments in the given location. When "Block" is selected, the comments will appear in the <a href="@livefyre">Livefyre Comments block</a>.', array('@livefyre' => url('admin/build/block'))),
    '#default_value' => variable_get('livefyre_location', 'content_area'),
    '#options' => array(
      'content_area' => t('Content Area'),
      'block' => t('Block'),
    ),
  );
  $form['livefyre_weight'] = array(
    '#type' => 'select',
    '#title' => t('Weight'),
    '#description' => t('When the comments are displayed in the content area, you can change the position at which they will be shown.'),
    '#default_value' => variable_get('livefyre_weight', 50),
    '#options' => drupal_map_assoc(array(-100, -50, -25, 0, 25, 50, 100)),
    );
  return system_settings_form($form);
}

function livefyre_admin_settings_validate($form, &$form_state) {
  if (!is_numeric($form_state['values']['livefyre_acct_num'])){
    form_set_error('livefyre_acct_num', t("Livefyre account number should be a number."));
  }
  //  Make sure the js file originates from Livefyre.com.
  $domain = parse_url($form_state['values']['livefyre_supplied_js']);
  $pieces = explode('.', $domain['host']);
  if (!preg_match("/com/", $pieces[2])  ||  !preg_match("/livefyre/", $pieces[1])){
  form_set_error('livefyre_supplied_js', t("Path to Livefyre javascript file must originate from Livefyre.com."));
  }
}