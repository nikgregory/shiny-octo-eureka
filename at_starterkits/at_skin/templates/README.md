# Templates

Here you can place template overrides and suggestions. By default only page.html.twig is included, unless you choose to include all AT Cores template overrides during when generating a new sub-theme (or you copy them in manually).

Note that all AT Core templates are used by this sub-theme due to Drupals template inheritance feature - sub-themes automatically use any template in the base theme, unless overridden in the sub-theme.

SEE: at_core/templates/README.md for more information on AT Cores templates and the changes made compared to Drupal core.

---

## Skin theme templates

Skin themes ship with an emtpy templates directory. The directory is entirely overwritten with the source themes (base themes) templates.

---

## Developing with Twig

Please see your sites settings.php file, there are three important settings you can uncomment to aid in twig template editing:

$settings['twig_debug'] = TRUE;
$settings['twig_auto_reload'] = TRUE;
$settings['twig_cache'] = FALSE;

The final two of these are most useful when making a lot of changes to templates - normally changes will not show until you clear the sites cache.

---