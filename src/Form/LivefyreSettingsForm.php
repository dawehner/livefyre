<?php

namespace Drupal\livefyre\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Admin settings of the livefyre module.
 */
class LivefyreSettingsForm extends ConfigFormBase {
  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['livefyre.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'livefyre_settings';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form = parent::buildForm($form, $form_state);

    $config = $this->config('livefyre.settings');

    $form['disabled'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Disable Livefyre comments?'),
      '#default_value' => $config->get('disabled'),
      '#description' => $this->t('Need to kill the comments real quick-like? Prevent Livefyre comments from being displayed by checking this box.'),
    ];
    $form['site_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site ID'),
      '#default_value' => $config->get('site_id'),
      '#description' => $this->t('This is the Livefyre account number, unique to each account.'),
    ];
    $form['site_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site Key'),
      '#default_value' => $config->get('site_key'),
      '#description' => $this->t('This is the Livefyre site key'),
    ];
    $form['network'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Network'),
      '#default_value' => $config->get('network'),
      '#description' => $this->t('This is the Livefyre network'),
    ];
    $form['network_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Network key'),
      '#default_value' => $config->get('network_key'),
      '#description' => $this->t('This is the Livefyre network key.'),
    ];
    if ($this->currentUser()->hasPermission('add livefyre html')) {
      $form['supplied_js'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Path to script supplied by Livefyre'),
        '#default_value' => $config->get('supplied_js'),
        '#description' => $this->t("Post the path to the script supplied by Livefyre. Paths only. No quotation marks or spaces allowed. <br /> <br /> According to the current Livefyre site, the current Livefyre javascript file references Version 3:<br /><strong>%url</strong><br /><br />For older installs using v1,<br /><strong>%urlv1</strong><br />...should still work fine.  <br /><br /><strong>Site Keys are not needed in the Drupal install at this time.</strong><br /><br />Please note: <br />I'm not affiliated with Livefyre so I rely on input from them and/or the community to update this module so these paths could change.", ['%url' => 'http://zor.livefyre.com/wjs/v3.0/javascripts/livefyre.js', '%urlv1' => 'http://zor.livefyre.com/wjs/v1.0/javascripts/livefyre_init.js']),
      ];
    }
    if ($this->currentUser()->hasPermission('add livefyre html')) {
      $form['advanced_settings'] = [
        '#type' => 'fieldset',
        '#title' => $this->t('Optional Advanced Settings: Custom HTML, CSS, Javascript'),
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      ];
      $form['advanced_settings']['parent_div'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Nest the Livefyre div within my custom div (Optional)'),
        '#default_value' =>  $config->get('parent_div'),
        '#description' => $this->t('By default, the Livefyre Comment javascript inserts a div with an id of "livefyre" into your page.  It looks like this:<br /><br /> <code>@<div></code><br /><br />For display/theming purposes, it\'s possible to surround the @<div> with your own custom div.  Please enter its ID here and note that <strong>only a div ID can be used.</strong>  For example, if you wanted to nest the @<div> within a div having the ID of "my_comment_div", then enter "my_comment_div" (without the quotes, obviously) into the textfield above.  <br /><br />The resulting html would look like this: <br /><br /><code> @<div2></code>', ['@<div>' => '<div id="livefyre"></div>', '@<div2>' => '<div id="my_comment_div"><div id="livefyre"></div></div>']),
      ];
      $form['advanced_settings']['custom_instantiate_lf'] = [
        '#type' => 'checkbox',
        '#title' => $this->t('<strong>v1.0 USERS ONLY:  Are you using a custom script that instantiates Livefyre\'s LF function?</strong><br />If so, check this box.  If not, leave it blank.'),
        '#default_value' => $config->get('custom_instantiate_lf'),
        '#description' => $this->t("It's possible to instantiate Livefyre's LF function with your own custom javascript.  If you are doing this, check this box.  This will ensure that the LF function does not get called twice.  If you will be instantiating the LF function with your own custom script be sure to include it in the \"Custom HTML Snippet\" box below."),
      ];
      $form['advanced_settings']['custom_script_snippet'] = [
        '#type' => 'textarea',
        '#title' => $this->t('Custom HTML Snippet'),
        '#default_value' => $config->get('custom_script_snippet'),
        '#rows' => 5,
        '#wysiwyg' => FALSE,
        '#description' => $this->t("Need tracking? Or other variables for your Livefyre comments? Put in your custom HTML and/or javascript here. For javascript make sure to include @<script> tags and be careful.  Messing this up could have bad effects on your site!", ['@<script>' => '<script>']),
      ];
    }

    $form['enterprise_settings'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Enterprise settings'),
      '#collapsed' => TRUE,
      '#collapsible' => TRUE,
    ];

    $form['enterprise_settings']['enable_enterprise'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable enterprise'),
      '#default_value' => $config->get('enterprise.enable'),
    ];

    $form['enterprise_settings']['customprofile_js'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Custom profile JS'),
      '#default_value' => $config->get('enterprise.customprofile_js'),
    ];

    $form['enterprise_settings']['fyre_authentication'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable fyre authentication'),
      '#default_value' => $config->get('enterprise.auth_fyre_enable'),
    ];

    $form['enterprise_settings']['fyre_authentication_url'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Enable fyre authentication url'),
      '#default_value' => $config->get('enterprise.fyre_authentication_url'),
    ];

    $form['location'] = [
      '#type' => 'select',
      '#title' => $this->t('Location'),
      '#description' => $this->t('Display the Livefyre Comments in the given location. If you select "Content Area" the comments will show up on a node view.  Choose what types of nodes below. <br /><br />When "Block" is selected, the comments will appear in the Livefyre Comments block on <a href=":livefyre">the "Blocks" page</a>.', [':livefyre' => Url::fromRoute('block.admin_display')->toString()]),
      '#default_value' => $config->get('location'),
      '#options' => [
        'content_area' => $this->t('Content Area'),
        'block' => $this->t('Block'),
      ],
      '#attached' => [
        'library' => 'livefyre/livefyre-admin',
      ],
    ];
    $form['weight'] = [
      '#type' => 'weight',
      '#delta' => 100,
      '#title' => $this->t('Weight'),
      '#description' => $this->t('When the comments are displayed in the content area, you can change the position at which they will be shown.'),
      '#default_value' => $config->get('weight'),
    ];

    $options = array_map(function ($bundle_info) {
      return $bundle_info['label'];
    }, \Drupal::entityManager()->getBundleInfo('node'));
    $form['node_types'] = [
      '#type' => 'checkboxes',
      '#default_value' => $config->get('node_types'),
      '#title' => $this->t('Show Livefyre Comments on these node Types'),
      '#description' => $this->t('Display comments only on the selected node types'),
      '#options' => $options,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

    if (!is_numeric($form_state->getValue('site_id'))) {
      $form_state->setErrorByName('site_id', $this->t('Livefyre site ID should be a number.'));
    }
    // Make sure the js file originates from Livefyre.com.
    $domain = parse_url($form_state->getValue('supplied_js'));
    $pieces = array_reverse(explode('.', $domain['host']));
    if (!preg_match("/^com$/", $pieces[0]) || !preg_match("/^livefyre$/", $pieces[1])) {
      $form_state->setErrorByName('supplied_js', $this->t('Path to Livefyre javascript file must originate from Livefyre.com.'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $config = $this->config('livefyre.settings');

    $config->set('disabled', $form_state->getValue('disabled'));
    $config->set('site_id', $form_state->getValue('site_id'));
    $config->set('site_key', $form_state->getValue('site_key'));
    $config->set('network', $form_state->getValue('network'));
    $config->set('network_key', $form_state->getValue('network_key'));
    $config->set('supplied_js', $form_state->getValue('supplied_js'));
    $config->set('parent_div', $form_state->getValue('parent_div'));
    $config->set('custom_instantiate_lf', $form_state->getValue('custom_instantiate_lf'));
    $config->set('custom_script_snippet', $form_state->getValue('custom_script_snippet'));
    $config->set('node_types', $form_state->getValue('node_types'));
    $config->set('location', $form_state->getValue('location'));
    $config->set('weight', $form_state->getValue('weight'));
    $config->save();
  }

}
