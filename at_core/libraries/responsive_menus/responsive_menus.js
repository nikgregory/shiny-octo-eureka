(function ($) {

  "use strict";

  Drupal.behaviors.atResponsiveMenus = {
    attach: function (context, settings) {

      //console.log(drupalSettings);

      var activeTheme = drupalSettings['ajaxPageState']['theme'];
      var responsiveMenuStyle = drupalSettings[activeTheme]['responsive_menu_style'];

      // Style settings
      var offCanvasPosition = drupalSettings[activeTheme]['offcanvas_position'];
      var tilesCount = drupalSettings[activeTheme]['tiles_count'];
      var tilesSubmenus = drupalSettings[activeTheme]['tiles_submenus'];

      // Add body classes. TODO, probably move to preprocess_page since we are already running checks there?
      if (offCanvasPosition) { $(document.body).addClass(offCanvasPosition); }
      if (tilesCount) { $(document.body).addClass('tiles-count-' + tilesCount); }
      if (tilesSubmenus) { $(document.body).addClass('tiles-submenus-true'); }

      // Regions
      var activeRegion = drupalSettings[activeTheme]['responsive_menu_region'];
      var activeRegionClass = '.' + activeRegion;
      $(activeRegionClass).addClass('responsive-menu-region');

      var menu   = activeRegionClass + ' .block-menu__content'; // wrapper element around the root ul.menu
      var toggle = activeRegionClass + ' .block-menu__title';   // the toggle link

      // Remove the visually-hidden class from block titles, in case the user forgets to set the title to show.
      $(toggle).removeClass('visually-hidden');

      // Clicking outside the menu will hide it.
      //$('.page-root :not(' + activeRegionClass + ')', context).on('click', function(){
      $('.page-root', context).on('click', function(){
        $(activeRegionClass + ' .block-menu').removeClass('menu-expanded');
        $(document.body).removeClass('menu-expanded');
      });

      // Add the menu style class - tiles, offcanvas or slidedown menu.
      $(activeRegionClass + ' .block-menu').addClass('menu-style-' + responsiveMenuStyle);

      // We need to set the style on the body also, mainly for offcanvas so we can
      // position and animate .page-root.
      $(document.body).addClass('menu-style-' + responsiveMenuStyle);

      // Toggle the menu open/closed.

      // This doesnt work when theres more than one offcanvas menu, we need to lock it open and simply
      // toggle the z-index and opacity of the block__content.
      $(toggle, context).on('touchstart click', function(e) {
        $(this).parent(activeRegionClass + ' .block-menu').toggleClass('menu-expanded');
        $(this).parent(activeRegionClass + ' .block-menu').siblings('.block-menu').removeClass('menu-expanded');
        $(document.body).toggleClass('menu-expanded');
        e.stopPropagation();
      });

    }
  };
})(jQuery);


//$(activeRegionClass + ' .block-menu.menu-expanded .block-menu__content').toggleClass('menu-active');



/*
  if ($(this).parent(activeRegionClass + ' .block-menu').hasClass('menu-expanded') && $('body').hasClass('menu-expanded')) {
    $(this).parent(activeRegionClass + ' .block-menu').removeClass('menu-expanded');
  }

  if ($(this).parent(activeRegionClass + ' .block-menu').hasClass('menu-expanded')) {
    $(this).parent(activeRegionClass + ' .block-menu').removeClass('menu-expanded');
  }
  else {
    $(this).parent(activeRegionClass + ' .block-menu').addClass('menu-expanded');
    $(document.body).addClass('menu-expanded');
  }
*/





/*
        $('div.toggle').click(function(){
          if($(this).hasClass('togglethis')) {
              $(this).removeClass('togglethis').addClass('active');
              $('div.showthis').hide();
          }
          else {
              $(this).removeClass(active).addClass('togglethis');
              $('div.showthis').show();

          }
        });
*/











/*
(function ($) {

  "use strict";

  Drupal.behaviors.atResponsiveMenus = {
    attach: function (context, settings) {

      console.log(drupalSettings);

      var activeTheme = drupalSettings['ajaxPageState']['theme'];
      var responsiveMenuStyle = drupalSettings[activeTheme]['responsive_menu_style'];

      // Style settings
      var offCanvasPosition = drupalSettings[activeTheme]['offcanvas_position'];
      var tilesCount = drupalSettings[activeTheme]['tiles_count'];
      var tilesSubmenus = drupalSettings[activeTheme]['tiles_submenus'];

      // Add body classes. TODO, probably move to preprocess_page since we are already running checks there?
      if (offCanvasPosition) { $('body').addClass(offCanvasPosition); }
      if (tilesCount) { $('body').addClass('tiles-count-' + tilesCount); }
      if (tilesSubmenus) { $('body').addClass('tiles-submenus-true'); }

      // Regions
      var activeRegion = drupalSettings[activeTheme]['responsive_menu_regions'];

      Object.keys(activeRegion).forEach(function (key) {

        var activeRegion = '.' + key;

        //console.log(activeRegion);

        $(activeRegion).addClass(activeRegion[key]);

        var menu   = activeRegion + ' .block-menu__content'; // wrapper element around the root ul.menu
        var toggle = activeRegion + ' .block-menu__title';   // the toggle link

        // Remove the visually-hidden class from block titles, in case the user forgets to set the title to show.
        $(toggle).removeClass('visually-hidden');

        // Clicking outside the menu will hide it.
        $('.page-root', context).on('click', function(){
          $(activeRegion + ' .block-menu').removeClass('menu-expanded');
          $('body').removeClass('menu-expanded');
        });

        // Add the menu style class - tiles, offcanvas or slidedown menu.
        $(activeRegion + ' .block-menu').addClass('menu-style-' + responsiveMenuStyle);
        $('body').addClass('menu-style-' + responsiveMenuStyle);

        // Toggle the menu open/closed.
        $(toggle, context).on('touchstart click', function(e) {
          $(activeRegion + ' .block-menu').toggleClass('menu-expanded');
          $('body').toggleClass('menu-expanded');
          e.stopPropagation();
        });

      });

    }
  };
})(jQuery);
*/





/*
(function ($) {

  "use strict";

  Drupal.behaviors.atResponsiveMenus = {
    attach: function (context, settings) {

      console.log(drupalSettings);

      var activeTheme = drupalSettings['ajaxPageState']['theme'];
      var responsiveMenuStyle = drupalSettings[activeTheme]['responsive_menu_style'];

      // Style settings
      var offCanvasPosition = drupalSettings[activeTheme]['offcanvas_position'];
      var tilesCount = drupalSettings[activeTheme]['tiles_count'];
      var tilesSubmenus = drupalSettings[activeTheme]['tiles_submenus'];

      // Add body classes. TODO, probably move to preprocess_page since we are already running checks there?
      if (offCanvasPosition) { $('body').addClass(offCanvasPosition); }
      if (tilesCount) { $('body').addClass('tiles-count-' + tilesCount); }
      if (tilesSubmenus) { $('body').addClass('tiles-submenus-true'); }

      // Regions
      var activeRegion = drupalSettings[activeTheme]['responsive_menu_regions'];

      Object.keys(activeRegion).forEach(function (key) {
        $('.region__' + key).addClass(activeRegion[key]);



      });


      var menu   = '.region__navbar .block-menu__content'; // wrapper element around the root ul.menu
      var toggle = '.region__navbar .block-menu__title';   // the toggle link



      // Remove the visually-hidden class from block titles, in case the user forgets to set the title to show.
      $(toggle).removeClass('visually-hidden');

      // Clicking outside the menu will hide it.
      $('.page-root', context).on('click', function(){
        $('body').removeClass('menu-expanded');
      });

      // Add the menu style class - tiles, offcanvas or slidedown menu.
      $('body').addClass('menu-style-' + responsiveMenuStyle);

      // Toggle the menu open/closed.
      $(toggle, context).on('touchstart click', function(e) {
        $('body').toggleClass('menu-expanded');
        e.stopPropagation();
      });
    }
  };
})(jQuery);
*/