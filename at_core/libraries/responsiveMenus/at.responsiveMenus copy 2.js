(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.atResponsiveMenus = {
    attach: function (context) {

      // Verify that the user agent understands media queries. Complex admin
      // toolbar layouts require media query support.
      if (!window.matchMedia('only screen').matches) {
        return;
      }

      var activeTheme = drupalSettings['ajaxPageState']['theme'];
      var responsiveMenus = drupalSettings[activeTheme]['responsive_menus'];

      //console.log(responsiveMenus);

      // Region
      var activeRegionClass = '.' + responsiveMenus['region'];
      $(activeRegionClass).addClass('responsive-menu-region');

      // Menu and toggle link variables
      var menu   = activeRegionClass + ' .block-menu__content'; // wrapper element around the root ul.menu
      var toggle = activeRegionClass + ' .block-menu__title';   // the toggle link

      // Clicking outside the menu will hide it.
      $('.page-root', context).on('touchstart click', function(){
        $(document.body).removeClass('menu-expanded');
      });

      enquire
      .register(responsiveMenus['breakpoint'], {

        setup: function() {

          // setup fires strait away, but if there is a match it will fire immediatly after.
          $(document.body).addClass(responsiveMenus['default']['style']);

          if (responsiveMenus['default']['style'] == 'menu-style-offcanvas') {
            $(document.body).addClass(responsiveMenus['default']['offcanvas_position']);
          }

          if (responsiveMenus['default']['style'] == 'menu-style-tiles') {
            $(document.body).addClass(responsiveMenus['default']['tiles_count']);

            if (responsiveMenus['default']['tiles_submenus']) {
              $(document.body).addClass(responsiveMenus['default']['tiles_submenus']);
            }
          }
        },

        match : function() {

          // The responsive menu system only uses one breakpoint, if it matches this triggers immedialty,
          // onload, strait after setup, then listens for future matches.

          // On run a match if responsive style is not none.
          if(responsiveMenus['responsive']['style'] !== 'menu-style-none') {

            // Default and responsive are different menu styles
            if(responsiveMenus['responsive']['style'] !== responsiveMenus['default']['style']) {

              // Remove default menu style, add responisve menu style
              $(document.body).removeClass(responsiveMenus['default']['style']).addClass(responsiveMenus['responsive']['style']);

              // Check if the responsive style is offcanvas and add the offcanvas position
              if(responsiveMenus['responsive']['style'] == 'menu-style-offcanvas') {
                $(document.body).addClass(responsiveMenus['responsive']['offcanvas_position']);
              }

              // Check if the responsive style is tiles and add option classes
              if(responsiveMenus['responsive']['style'] == 'menu-style-tiles') {
                $(document.body).addClass(responsiveMenus['responsive']['tiles_count']);

                if(responsiveMenus['responsive']['tiles_submenus']) {
                  $(document.body).addClass(responsiveMenus['responsive']['tiles_submenus']);
                }
              }
            } else {

              // else means the menu styles are the same for both default and responsive, we only
              // need to check if the options are the same and add/remove classes accordingly.

              // Check if the responsive style is offcanvas
              if(responsiveMenus['responsive']['style'] == 'menu-style-offcanvas') {

                // Check if default offcanvas position is the same as the responisve offcanvas position
                if(responsiveMenus['default']['offcanvas_position'] != responsiveMenus['responsive']['offcanvas_position']) {

                  // They are different, remove default and add responsive
                  $(document.body).removeClass(responsiveMenus['default']['offcanvas_position']).addClass(responsiveMenus['responsive']['offcanvas_position']);

                }

                //else {
                  // do nothing, setup has already added the correct offcanvas position
                  // They are the same, add default offcanvas position
                  //$(document.body).addClass(responsiveMenus['default']['offcanvas_position']);
                //}
              }

              // Check if the responsive style is tiles
              if (responsiveMenus['responsive']['style'] == 'menu-style-tiles') {

                // Check if default offcanvas position is the same as the responsive offcanvas position
                if(responsiveMenus['default']['tiles_count'] != responsiveMenus['responsive']['tiles_count']) {

                  // They are different, remove default add responsive
                  $(document.body).removeClass(responsiveMenus['default']['tiles_count']).addClass(responsiveMenus['responsive']['tiles_count']);

                }

                //else {
                  // do nothing, setup has already added the correct tiles count
                  // They are the same, add default tiles count
                  //$(document.body).addClass(responsiveMenus['responsive']['tiles_count']);
                //}

                // Check if tiles submenus is set
                //if(responsiveMenus['responsive']['tiles_submenus']) {

                  // If either of these are true add the sub menus class
                  //if(responsiveMenus['default']['tiles_submenus'] || responsiveMenus['responsive']['tiles_submenus']) {
                   // $(document.body).addClass('tiles-submenus-true');
                  //}
                //}
              }
            }
          }
        },

        unmatch : function() {

          // unmatch fires the first time the media query is unmatched, which is why we have to run code in setup, and
          // then duplicate some of it here again. I need to look into if there is a better way of doing this.

          $(document.body).addClass(responsiveMenus['default']['style']);

          // Default and responsive are different menu styles
          if(responsiveMenus['responsive']['style'] !== responsiveMenus['default']['style']) {

            // Remove responsive menu style
            $(document.body).removeClass(responsiveMenus['responsive']['style']);

            // Check if the default style is offcanvas and add the offcanvas position
            if(responsiveMenus['default']['style'] == 'menu-style-offcanvas') {
              $(document.body).addClass(responsiveMenus['default']['offcanvas_position']);
            }

            // Check if the default style is tiles and add option classes
            if(responsiveMenus['default']['style'] == 'menu-style-tiles') {
              $(document.body).addClass(responsiveMenus['default']['tiles_count']);

              if(responsiveMenus['default']['tiles_submenus']) {
                $(document.body).addClass(responsiveMenus['default']['tiles_submenus']);
              }
            }
          } else {

            // else means the menu styles are the same for both default and repsonsive, we only
            // need to check if the options are the same and add/remove classes accordingly.

            // Check if the responsive style is offcanvas
            if(responsiveMenus['default']['style'] == 'menu-style-offcanvas') {

              // Check if default offcanvas position is the same as the responisve offcanvas position
              if(responsiveMenus['default']['offcanvas_position'] != responsiveMenus['responsive']['offcanvas_position']) {

                // They are different, remove responsive and add default
                $(document.body).removeClass(responsiveMenus['responsive']['offcanvas_position']).addClass(responsiveMenus['default']['offcanvas_position']);

              }

              //else {
                // do nothing, setup has already added the correct offcanvas position and match did not remove it
                // They are the same, add default offcanvas position
                //$(document.body).addClass(responsiveMenus['default']['offcanvas_position']);
              //}
            }

            // Check if the default style is tiles
            if (responsiveMenus['default']['style'] == 'menu-style-tiles') {

              // Check if default offcanvas position is the same as the responsive offcanvas position
              if(responsiveMenus['default']['tiles_count'] != responsiveMenus['responsive']['tiles_count']) {

                // They are different, remove responsive add default
                $(document.body).removeClass(responsiveMenus['responsive']['tiles_count']).addClass(responsiveMenus['default']['tiles_count']);

              }

              //else {
                // do nothing, setup has already added the correct tiles count and match did not remove it
                // They are the same, add default tiles count
                //$(document.body).addClass(responsiveMenus['responsive']['tiles_count']);
              //}

              // Check if tiles submenus is set
              //if(responsiveMenus['default']['tiles_submenus']) {
                // If either of these are true add the sub menus class
                //if(responsiveMenus['default']['tiles_submenus'] || responsiveMenus['responsive']['tiles_submenus']) {
                  //$(document.body).addClass('tiles-submenus-true');
                //}
              //}

            }
          }
        }
      });

      // Remove the visually-hidden class from block titles, in case the user forgets to set the title to show.
      // Add a span, we use this for styling some menu style toggle links, e.g. Flip Slide style.
      $(toggle).removeClass('visually-hidden').wrapInner('<span class="title-inner" />').end();


      $(toggle, context).on('touchstart click', function(e) {
        $(this).parent(activeRegionClass + ' .block-menu').toggleClass('menu-expanded');
        $(this).parent(activeRegionClass + ' .block-menu').siblings('.block-menu').removeClass('menu-expanded');
        $('body').toggleClass('menu-expanded');
        e.stopPropagation();
      });

    }
  };

}(jQuery, Drupal, drupalSettings));















