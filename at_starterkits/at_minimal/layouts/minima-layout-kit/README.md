# Minima Layout Kit

This kit provides the bare bones for building layouts in Adaptivetheme:

    minima-layout-kit.layout.yml : define your layout rows and regions and other definitions.
    minima-layout-kit.css        : empty CSS file, change or remove.
    minima-layout-kit-ie8.css    : empty CSS file for the IE8 layout (if required otherwise remove).
    minima-layout-kit.png        : sample screenshot for the appearance settings page.

This layout assumes you want to choose how to write the CSS for your layout. It is completly agnostic in terms of layout framework, CSS preprocessor (no SASS files, partials etc are included).

It defines only Drupal core regions (the same as the sparta layout), but provides no actual CSS layout for those rows and regions.

Note this layout declares "hidden: true" in the yml file, remove that after you copy this kit so your new layout will show up on the appearance settings page.
