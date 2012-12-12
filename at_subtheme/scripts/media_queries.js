/**
 * @file
 * Fire callbacks for media query breakpoints
 *
 * To use this file enable the OnMediaQuery.js polyfill in your subthemes
 * appearance settings - this will load the required plugin and this file.
 *
 * This allows you to write context (media query) specific JS without hard
 * coding the media queries, aka like matchMedia. Each context matches the
 * media queries you set in theme settings (by adding the font-family
 * declarations to the responsive layout CSS).
 *
 * SEE: https://github.com/JoshBarr/js-media-queries (really, go look, lots of
 * useful documentation available).
 *
 * IMPORTANT: do not rename or move this file, or change the directory name!
 */
var queries = [

  // README! The following are examples, remove what you don't need, for example
  // unless you ar using them remove all the calls to console.log()


  // Smalltouch
  {
    context: ['smalltouch_portrait', 'smalltouch_landscape'],
    call_in_each_context: false,
    callback: function() {
      // console.log('smalltouch');

      // Example: this will remove the search block in smalltouch devices
      // element = document.getElementById("block-search-form");
      // element.parentNode.removeChild(element);
    }
  },
  // Smalltouch portrait only
  {
    context: 'smalltouch_portrait',
    callback: function() {
      //console.log('smalltouch portrait');
    }
  },
  // Smalltouch landscape only
  {
    context: 'smalltouch_landscape',
    callback: function() {
      // console.log('smalltouch_landscape ');
    }
  },


  // Tablet
  {
    context: ['tablet_portrait', 'tablet_landscape'],
    call_in_each_context: false,
    callback: function() {
      // console.log('tablet');
    }
  },
  // Tablet portrait only
  {
    context: 'tablet_portrait',
    callback: function() {
      // console.log('tablet_portrait');
    }
  },
  // Tablet landscape only
  {
    context: 'tablet_landscape',
    callback: function() {
      // console.log('tablet_landscape');
    }
  },


  // Standard desktop
  {
    context: 'standard',
    callback: function() {
      // Debug
      // console.log('standard desktop');
    }
  },
];

// Go!
MQ.init(queries);
