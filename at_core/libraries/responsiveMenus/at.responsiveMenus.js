(function ($, Drupal, drupalSettings) {

  Drupal.behaviors.atResponsiveMenus = {
    attach: function (context) {

      // Verify that the user agent understands media queries. Complex admin
      // toolbar layouts require media query support.
      if (!window.matchMedia('only screen').matches) {
        return;
      }

      var activeTheme = drupalSettings['ajaxPageState']['theme'];

      // default and responsive
      var defaultStyleSettings = drupalSettings[activeTheme]['menu_style_default'];
      var responsiveStyleSettings = drupalSettings[activeTheme]['menu_style_responsive'];

      // Regions
      var activeRegion = drupalSettings[activeTheme]['responsive_menu_region'];
      var activeRegionClass = '.' + activeRegion;
      $(activeRegionClass).addClass('responsive-menu-region');

      var menu   = activeRegionClass + ' .block-menu__content'; // wrapper element around the root ul.menu
      var toggle = activeRegionClass + ' .block-menu__title';   // the toggle link

      // Clicking outside the menu will hide it.
      $('.page-root', context).on('touchstart click', function(){
        $('body').removeClass('menu-expanded');
      });

      // Default styles
      var defaultClasses = ['menu-style-' + defaultStyleSettings['style']];
      if (defaultStyleSettings['options']) {
        if (defaultStyleSettings['options']['offcanvas_position']) {
          defaultClasses.push(defaultStyleSettings['options']['offcanvas_position']);
        }
        if (defaultStyleSettings['options']['tiles_count']) {
          var defaultTiles = true;
          defaultClasses.push('tiles-count-' + defaultStyleSettings['options']['tiles_count']);
          if (defaultStyleSettings['options']['tiles_submenus']) {
            defaultClasses.push('tiles-submenus-true');
          }
        }
      }

      // responsive styles
      var responsiveClasses = ['menu-style-' + responsiveStyleSettings['style']];
      if (responsiveStyleSettings['options']) {
        if (responsiveStyleSettings['options']['offcanvas_position']) {
          responsiveClasses.push(responsiveStyleSettings['options']['offcanvas_position']);
        }
        if (responsiveStyleSettings['options']['tiles_count']) {
          var responsiveTiles = true;
          responsiveClasses.push('tiles-count-' + responsiveStyleSettings['options']['tiles_count']);
          if (responsiveStyleSettings['options']['tiles_submenus']) {
            responsiveClasses.push('tiles-submenus-true');
          }
        }
      }

      enquire
      .register(defaultStyleSettings['breakpoint'], {
        match : function() {
          Object.keys(defaultClasses).forEach(function (key) {
            $('body').addClass(defaultClasses[key]);
            if (defaultTiles) {
              $('.menu-style-tiles .responsive-menu-region .menu-level-1 > li').matchHeight();
            }
          });
        },
        unmatch : function() {
          Object.keys(defaultClasses).forEach(function (key) {
            $('body').removeClass(defaultClasses[key]);
          });
        }
      }, true)
      .register(responsiveStyleSettings['breakpoint'], {
        match : function() {
          Object.keys(responsiveClasses).forEach(function (key) {
            $('body').addClass(responsiveClasses[key]);
            if (responsiveTiles) {
              $('.menu-style-tiles .responsive-menu-region .menu-level-1 > li').matchHeight();
            }
          });
        },
        unmatch : function() {
          Object.keys(responsiveClasses).forEach(function (key) {
            $('body').removeClass(responsiveClasses[key]);
          });
        }
      });

      // Remove the visually-hidden class from block titles, in case the user forgets to set the title to show.
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
