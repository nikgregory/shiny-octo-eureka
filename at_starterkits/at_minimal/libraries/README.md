# Libraries Help

In Adaptivetheme a *library* is a collection of JavaScript and/or CSS files.

Libraries and their files are auto-discovered by AT Core and presented in your sub-theme configuration where you can toggle them on and off. When toggled on AT Core will load the file/s globally.

You can also load a library manually using drupal_add_library('themename', 'library_name'), where 'library_name' is the name of the folder (directory) holding the library files. You can also use #attached, for example in hook_page_alter().

---

## Creating a Library

To add a library create a new folder inside your /libraries/ folder and add JS (and/or CSS) files.

For example to add a jQuery plugin the structure should be like this:

libraries/myplugin/jquery.myplugin.js

You can add as many JS or CSS files as you want, for example your plugin might have additional JS files and a CSS file and you need as a dependancy:

lib/myplugin/jquery.myplugin.js
lib/myplugin/jquery.myplugin.extra.js
lib/myplugin/jquery.myplugin.css

Clear the site cache after you have added your files, or any new files. You must do this so they are added to the library info data.

---

## Caveats

1. Do not put files in additional sub-folders. AT Core's auto discovery feature will not find the files, i.e. it is not recursive.
2. There is no provision for setting dependancies, browser options or any of the advanced library info features.
3. You must clear the site cache to get a library to show up in theme settings and be used - AT Core caches its library data!
