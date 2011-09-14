<?php
// Wrangle the theme settings form so it makes sense and avoids any problems with logo's
function adaptivetheme_admin_form_system_theme_settings_alter(&$form, &$form_state)  {
  $form['theme_settings']['#collapsible'] = TRUE;
  $form['theme_settings']['#collapsed'] = TRUE;
  $form['theme_settings']['toggle_logo']['#type'] = 'hidden';
  $form['theme_settings']['toggle_logo']['#default_value'] = 0;
  $form['logo']['#type'] = 'hidden';
  $form['logo']['#default_value'] = 1;
  $form['favicon']['#collapsible'] = TRUE;
  $form['favicon']['#collapsed'] = TRUE;
}
