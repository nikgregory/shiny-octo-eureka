# Build Your First Layout - Step by Step

A layout is a set of files that collectively apply a layout to page.html.twig or a template suggestion.

In short they work like this - the main yml file declares rows and regions (and CSS files), Adaptivetheme generates page templates from that yml file. You write the CSS layout and Adaptivethee will load this at the right time (if the layout is selected).

**Why Do This?**

It's fast. Writing twig markup is tedious. Writing CSS is slow. Layouts provide a very quick (and  accurate) way of generating markup and CSS (using SASS). For theme developers this is a huge time saving tool. Page template and layout development can be compressed into minutes, instead of hours. Accuracy is improved because there is less code to manage.

Additionally you can ship multiple layouts with your theme. Imagine this - your client wants to prototype 3 alternative layouts. That's a lot of work done the traditional way - using Adaptivetheme you can prototype these layouts in a few minutes. Client is impressed and gives out a bonus.


## 1: Copy "[type]-layout-kit"

There are two layout starterkits, one is really minimal (less regions, no ELF), the other has many regions and includes the ELF (Easy Layout Framework).

* AT Minimal type sub-themes have the "minima-layout-kit".
* AT Standard type sub-themes have the "elf-layout-kit".

The rest of this guide assumes you are using the "elf-layout-kit".

You can use any layout type in any theme. The minima kit is a clean slate for those who want to use their own SASS framework (the default is Susy Next) or write old school CSS.

## 2: Rename Folders and Files

First choose a name for your new layout and rename the folder. Use letters, underscores and hyphens only. You should have something like this:

    themes/my-theme/layouts/my-layout

Next rename files, for example rename *elf-layout-kit.layout.yml* to *[my-layout].layout.yml*.

Rename all the following files (assuming you are using the standard-layout-starterki):

    elf-layout-kit.layout.yml
    elf-layout-kit.css
    elf-layout-kit-ie8.css
    elf-layout-kit.png

If using SASS look in the /sass/ folder and rename files there also:

    /sass/elf-layout-kit.scss
    /sass/elf-layout-kit-ie8.scss

## 3: Modify [my-layout].layout.yml

Here we step through the file one item (or key) at a time and discuss. Follow this carefully, once you have done this you will understand how this file works and this task will become fast and easy in the future. Its not complicated, but you need to be accurate.

#### Change the 'series' name:

Layouts needs to belong to a series - the main thing is that all layouts in a series have the same reigons - the only real difference between layouts in a series is their CSS layout. We'll deal with  wirting CSS later, for now choose a series.

    series: 'My Series'

* All layouts must belong to a series (even if there is only one layout in the series).
* All layouts in a series MUST have the same regions.
* If this is the first layout in the series and you plan on changing the regions and rows, create a new series.

#### Optional description:
Description is optional, you can remove it or add a brief description.

    description: '... short description here... '


#### Version String:
Set a version of you want need to track this, otherwise you can remove it.

    version: '8.x-1.0-dev'


#### Remove "hidden: true"
This hides the starterkit on the Appearance settings page, remove it now.

    hidden: true # delete me


#### Rows and Regions

Here you add your rows and regions. Rows are containers for a group of regions. Rows can have attributes like id, role and classes.

Use this to add or remove rows and regions in your layout. This is what Adaptivetheme uses to generate page templates - you never write any real code or markup for page templates - instead Adaptivetheme will generate this when you select a layout in the Appearance settings for your sub-theme.

Remember - this is a yml file, indentation is very important! Use two spaces, not tabs, follow the exsiting file you are modifying carefully.

Schema:

    rows:
      [row_name]
        [attributes:]
          [attribute type]: attribute-value
        [regions:]
          [region_machine_name]: 'Region Friendly Name'

**Special Case Row Names**

'header', 'main' and 'footer' will use their html5 counterparts when the page template is generated. These are used in all Adaptivetheme layouts.


#### Change CSS File Names

These need to match your new files names.

There are only two keys available - *styles:* for the main CSS file and *no_mq* for the IE8 (or no-media-query) CSS file.

    css:
      styles: [my-layout].css
      no_mq: [my-layout]-ie8.css

Now you can edit these CSS files and create the CSS layout required. Everthing up to this point has been about generating markup code, but layouts are CSS and markup. See the guide on "Layout CSS".


#### Screenshot

Update the name of the screenshot file name:

    screenshot: [my-layout].png


## 4. Using your new layout

After you have saved your new layout visit the Appearance settings for your theme. Assuming you have enabled layouts you will see the new layout in the "Select Layout" table.

Follow the steps to apply your layout as "Default" (replaces page.html.twig) or use it in a template suggestion. Note that template suggestion layouts must be from the same series as the default layout.

See the guide on "Generating Layouts" for more details.




