<?php

function livefyre_admin_settings() {
  $form['lf_disabled'] = array(
      '#type' => 'checkbox',
      '#title' => t('Disable livefyre comments?'),
      '#default_value' => variable_get('livefyre_disabled', TRUE),
      '#description' => t('Prevent livefyre comments from being displayed.'),
      );

  $form['acct_num'] = array(
      '#type' => 'textfield',
      '#title' => t('Livefyre account number'),
      '#default_value' => variable_get('livefyre_acct', ''),
      '#description' => t('This is the livefyre account number, unique to each station.'),
      );
  $form['livefyre_js'] = array(
      '#type' => 'textfield',
      '#title' => t('Script supplied by Livefyre'),
      '#default_value' => variable_get('livefyre_js', 'http://i5.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js'),
      '#description' => t('Post the path to the script supplied by Livefyre.  Defaults to <em><strong>"http://i5.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js"</em></strong>.'),
      );
  $form['custom_css'] = array(
      '#type' => 'textfield',
      '#title' => t('Path to custom css file'),
      '#default_value' => variable_get('livefyre_custom_css', ''),
      '#description' => t('Post the path to your custom css file here.'),
      );
   $form['custom_js'] = array(
      '#type' => 'textfield',
      '#title' => t('Path to custom javscript file'),
      '#default_value' => variable_get('livefyre_custom_js', ''),
      '#description' => t('Post the path to your custom javascript file here.'),
      );
  
  $form['domain_name'] = array(
    '#type' => 'textfield',
    '#title' => t('Domain Name'),
    '#description' => t('<strong>This should already be filled in by default.</strong><br /> If not, please enter the domain name (ex. komonews.com, katu.com)'),
    '#default_value' => variable_get('domain_root', $_SERVER['HTTP_HOST']),
  );
  $form['fisher_lf_domain'] = array(
    '#type' => 'textfield',
    '#title' => t('Extra domain info for Fisher'),
    '#description' => t('Set to "fisher-0.fyre.co" by default.'),
    '#default_value' => variable_get('fisher_lf_domain', 'fisher-0.fyre.co'),
  );

  $form['test_mode'] = array(
      '#type'           => 'checkbox',
      '#title'          => t('Test Mode'),
      '#default_value'  => variable_get('livefyre_testmode', FALSE),
      '#description'    => t('When testing, check this so test comments will not show up in production.'),

      );

  $form['node_types'] = array(
      '#type' => 'select',
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
  $form['custom_script_snippet'] = array(
    '#type' => 'textarea',
    '#title' => t('Custom Javascript snippet'),
    '#default_value' => variable_get('custom_script_snippet', ''),
    '#rows' => 5,
    '#wysiwyg' => FALSE,
    '#description' => t("Need tracking? Or other variables for your comments? Put in your custom javascript here."),
  );

  $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save changes'),
      );
  return $form;
}

function livefyre_admin_settings_submit(&$form_state, $form) {
  drupal_set_message(t("Your changes have been saved."));
  foreach ($form_state['node_types']['#value'] as $key => $value) {
    $lf_node_types[] = $key;
  }
  variable_set('livefyre_node_types', $lf_node_types);
  variable_set('livefyre_acct', $form_state['acct_num']['#value']);
  variable_set('livefyre_disabled', $form_state['lf_disabled']['#value']);
  variable_set('livefyre_testmode', $form_state['test_mode']['#value']);
  variable_set('livefyre_weight', $form_state['weight']['#value']);
  variable_set('livefyre_location', $form_state['location']['#value']);
  variable_set('livefyre_domain', $form_state['domain_name']['#value']);
  variable_set('fisher_lf_domain', $form_state['fisher_lf_domain']['#value']);
  variable_set('livefyre_custom_css', $form_state['custom_css']['#value']);
  variable_set('livefyre_custom_js', $form_state['custom_js']['#value']);
  variable_set('livefyre_js', $form_state['livefyre_js']['#value']);
  variable_set('script_snippet', $form_state['script_snippet']['#value']);



}

