  
  Intallation and setup is strait forward, just follow the steps carefully:

  1) Copy and past the included starter subtheme (adaptivetheme_subtheme) 
     to your sites/all/themes folder or where ever you are installing your new subtheme.

  2) Rename the theme folder and info file to your preferred theme name, 
     then open up the info file and change the name and description.

  3) Open up your subthemes template.php file and search/replace all instances 
     of “adaptivetheme_subtheme” with your new theme name (there are 6 instances).

  4) Open up your subthemes theme-settings.php file and locate this functions: 

       function adaptivetheme_subtheme_settings($saved_settings) {
         // Get the default values from the .info file.
         $defaults = adaptivetheme_theme_get_default_settings('adaptivetheme_subtheme');

     a) You must first RENAME the function to match your theme name, and:
     b) replace the instance of ('adaptivetheme_subtheme') with ('mytheme').
     
        It should look like this when you are done:

        function mythemename_settings($saved_settings) {
          // Get the default values from the .info file.
          $defaults = adaptivetheme_theme_get_default_settings('mythemename');

  5) If you want to use the color schemes features open up the info file, 
     scroll to the bottom and change settings[color_enable_schemes] = ‘off’ to ‘on’.
 
     Now you can enable the theme as per usual.


  Trouble Shooting FAQ:
  
  Q: My theme settings aren't showing up in the theme config.
  A: If the Advanced Theme Settings don't show up you probably made a mistake in Step 4.
  
  Q: Skinr is not working for blocks.
  A: If the Skinr styles dont work check if you have the Nodewords module (Metatags) 6.x-1.x,
     if so upgrade it to the lastest version.
  
  Q: The custom Panel Layouts are not showing up.
  A: If the custom Panel Layouts don't show up make sure the Adaptivetheme basetheme is enabled (they 
     can't work unless the base theme is enabled as well).
  
  Q: I set some new defaults in the info file but they are not being recognized.
  A: If you make changes (any changes) to the theme setting defaults in the .info file you must
     click "Reset to defaults" otherwise your changes won't be saved to the database and won't show up.
  
  For other issues please see the issue queue first and post a new issue if you still have a problem:
  http://drupal.org/project/issues/adaptivetheme
  
  