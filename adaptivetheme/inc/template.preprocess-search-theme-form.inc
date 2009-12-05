<?php // $Id$
/**
 * @file template.preprocess-search-theme-form.inc
 */

if (theme_get_setting('display_search_form_label') == 0) {
  $vars['search_label_class'] = ' class="element-invisible"';
}
