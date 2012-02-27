
# SASS: PLEASE READ !!!
  
  There is more information regarding working with SASS in the SASS
  CSS folder readme, however, you need to be aware that if you set
  Compass to watch the SASS folder or any SCSS file in this theme
  it will OVERWRITE the CSS file/s in your sub-themes CSS directory!

  To prevent this ever happening you can delete the config.rb file in
  the sub-theme root (unless you are actually planning on using SASS, 
  in which case you will want to keep it).


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
  
  The global styles do not target any specific device. Four of these global 
  stylesheets are loaded by default:
  
  style.base.css     (html elements, forms, tables, fields)
  style.modules.css  (used for styling contrib module specific output)
  style.settings.css (for styling theme setting output)
  style.theme.css    (style page, nodes, comments, blocks and all other output)
  
  Three will depend on what you need such as color module support, print styles 
  or you need to add some custom overrides. If you want to use one of these you 
  need to enable it in the info file for your sub-theme:
  
  style.colors.css (when using color module)
  style.custom.css (custom overrides, usually used later in a project)
  print.css        (starter print styles - enable and modify to suit)
  
  If you are doing mobile first then you will probably keep things to a minimum 
  in these files. If you are doing mobile last approach, then you will place the 
  majority of your CSS in these files.
  
  The responsive stylesheets also load on every page request, however they use
  media queries in the <link> element and are therefor targeted at specific device
  size ranges (whatever media query breakpoints you enter in the theme settings
  for your sub-theme). There are five of these responsive stylesheets - one for
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

