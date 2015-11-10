# Templates

Drupal core template overrides. By default all sub-themes are generated with page.html.twig.

If you select the option to include templates when generating a sub-theme the templates in this directory are copied and placed in your sub-themes templates directory.

If you manually create a sub-theme you can pick and choose which templates you want to copy and modify.

---

## Developing with Twig

Please see your sites services.yml file, there are three important settings you can modify to aid in twig template editing:

  debug: false
  auto_reload: null
  cache: true

The final two of these are most useful when making a lot of changes to templates - normally changes will not show until you clear the sites cache.

---

## Included Template Overrides and Suggestions

In essence all templates are copied from the Classy theme and then modified. We do this because we don't want all the Classy CSS libraries but we do want the templates.

