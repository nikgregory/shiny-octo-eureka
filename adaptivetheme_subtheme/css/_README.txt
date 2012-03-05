
# First read this about SASS, its very important!

  There is more information regarding working with SASS in the SASS
  CSS folder README, however, you need to be aware that if you set
  Compass to watch the SASS folder or any SCSS file in this theme
  it will OVERWRITE the CSS file/s in your sub-themes CSS directory!

  To prevent this ever happening you can delete the config.rb file in
  the sub-theme root (unless you are actually planning on using SASS,
  in which case you will want to keep it).

  Note: this is actually complete bollocks at the moment because there
  are no .scss files, I removed them until I have finished writing the
  new modular global stylesheets...


# Working with Responsive Design in Adaptivetheme

  The subtheme is designed to be "mobile first", meaning you first load a set
  of global styles, the progressively add styles for larger devices using
  media queries to target specific device ranges (by screen size).

  Its important to note that you do not have to follow a mobile first approach.
  Many designers and themers simply load the majority of their themes design
  in the global styles and then override them in media queries.

  You can do both in Adaptivetheme - it's merely a matter of where you place
  the majority of your CSS. Lets examine the CSS file structure of Adaptivetheme
  and look at how each file is loaded. From this you will be able to deduce
  what approach might work for you, and where you should be placing your CSS.

  By default the sub-theme ships with 13 stylsheets. Six of these are for
  global styles, five of them are used for device specific styling, one is for
  print and one is for IE (less than IE9).

  The global styles are all loaded using the .info file, while the responsive
  stylesheets are loaded by the theme using drupal_add_css(). First lets look
  at the global stylesheets.

  ## Global Styles

  The global styles do not target any specific device. The files have been
  structured around Zen 7.x-5.x which is similar to how things have worked
  in the past for Adaptivetheme, however there are notable changes in how these
  files are loaded and their naming conventions.

  I used Zen as a benchmark to create a familiar CSS structure for those themers
  familiar with Zen, however you will note that there are additional CSS files
  in Adaptivetheme and the structure is slightly different. If you are using
  Firebug or Webkit/IE9+/Opera inspector then you will have no problem finding
  where the styles or selectors are.

  Only one file is loaded by the sub-theme:

  globals.css

  This global.css file uses @import to pull in all the smaller modular stylsheets.
  This is really a house-keeping exercise and you can change this if you prefer. I
  like it, and makes it easy for me to comment out an entire stylesheet
  rather than modifying the info file.

  All the global stylesheets are prefixed with the name "global", for example
  global.base.css, global.blocks.css and so on. The selectors are extensive
  and you should delete unused selectors before going live to reduce CSS weight.

  Each file includes a lot of comments and documentation, please review each of
  the global CSS files for more help.

  If you are doing mobile first then you will probably keep things to a minimum
  in these files. If you are doing mobile last approach, then you will place the
  majority of your CSS in these files.

  ## Responsive Styles

  Adaptivetheme 7.x-3.x has two "modes", a development mode and a production mode.
  Depending on what mode you are in the stylesheets will load differently.

  To change the mode see the Global Settings in the theme settings for your theme.

  In development mode the responsive stylesheets will load in invididual link elements
  with the media query in media attribute. This allows them to load directly into the
  browser and you will see your CSS edits immedialty, as per normal CSS development.

  When in production mode all the responsive stylesheets are aggregated into one file
  with embedded @media queries. This file loads from your public files directory.

  The responsive stylesheets also load on every page request, however they use
  media queries in the <link> element and are therefor targeted at specific device
  size ranges (whatever media query breakpoints you enter in the theme settings
  for your sub-theme). There are six of these responsive stylesheets - one for
  each breakpoint set in the theme settings:

  responsive.smartphone.landscape.css
  responsive.smartphone.portrait.css
  responsive.tablet.landscape.css
  responsive.tablet.portrait.css
  responsive.standard.bigscreen.css

  Its important to know that these files DO NOT contain the media queries, unlike
  previous versions of Adaptivetheme the new 7.x-3.x uses media quries on the <link>
  element instead. This allows you to concentrate on writing CSS for each device by
  simply using the correct file, and leave the heavy lifting of loading the fils and
  generting the media query breakpoints up to the base theme.

  For a mobile first approach you will place minimal styles in the global stylesheets
  and instead place the majority of your CSS in the responsive stylesheets - progressively
  enhancing the design for each device range. This is especially useful for avoiding
  things such as large background images for small screens and for reducing the CSS
  overhead for mobile connections.

  ## Overlapping Media queries

  By default the media queries in Adaptivetheme are "stacked", meaning they do not
  overlap. This makes it very easy to target one set of devices and not have those
  styles leak over into others. However it can also mean you need to duplicate a lot
  of CSS that you would rather cascades. To achive this  you can either modify the
  media queries to only use a min-width, or you can use a special file called:

  responsive.cascade.css

  This file has embedded media queries which means you MUST set them yourself. Defaults
  are provided.

  Allowing styles to cascade can result in a huge saving on total CSS weight and speed
  up development. It can also be harder to maintain and you really need to understand
  cascade and inheritance, not to mention selector specifity, to a high degree.


# Internet Explorer Overrides

  Adaptivetheme includes special conditional classes on the HTML element
  which allow you to easily target styles at specific version of IE.

  These are the classes you can use:

 .iem7 (IE7 Mobile)
 .lt-ie7 (less than IE7)
 .lt-ie8 (less than IE8)
 .lt-ie9 (less than IE9)

  If you prefer to use IE conditional stylesheets please see template.php - we
  have included a very simple method of loading conditional stylesheets, read more
  about this in our online docs:

  http://adaptivethemes.com/documentation/working-with-internet-explorer


# Support

  Ping me on Skype if you really need help: jmburnz, otherwise support my work
  by joining my theme club, it really does fund my contrib projects:

  http://adaptivethemes.com/pricing

  Or, you could get radical and file a support issue:

  http://drupal.org/project/issues/adaptivetheme

