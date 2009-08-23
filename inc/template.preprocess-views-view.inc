<?php // $Id$
// adaptivethemes.com
/**
 * Adds a class for the style of view  
 * (e.g., node, teaser, list, table, etc.)
 * (Requires views-view.tpl.php file in theme directory)
 */
$vars['css_name'] = $vars['css_name'] .' view-style-'. views_css_safe(strtolower($vars['view']->type));
