
# Required plugins
# -----------------------------------------------------------------------------
# The default layout that ships with the minimal theme is built using Susy, a
# SASS framework for building responsive grid based layouts. You can swap this
# out for anything you want, see README.md in the /assets/sass/ directory.
# Susy website: susy.oddbird.net
require 'susy'


# Directory paths
# -----------------------------------------------------------------------------
css_dir = "./"
#sass_dir = "styles/sass"
images_dir = "styles/css/images"


# SASS core
# -----------------------------------------------------------------------------
# Chrome needs a precision of 7 to round properly
Sass::Script::Number.precision = 7


# Output style and comments
# -----------------------------------------------------------------------------
# You can select your preferred output style here - :expanded, :nested,
# :compact or :compressed).
output_style = (environment == :production) ? :compact : :expanded

# To enable relative paths to assets via compass helper functions.
relative_assets = true

# Conditionally enable line comments when in development mode.
line_comments = (environment == :production) ? false : true

# Pass options to sass.
# - For development, we turn on the FireSass-compatible debug_info.
# - For production, we force the CSS to be regenerated even though the source
#   scss may not have changed, since we want the CSS to be compressed and have
#   the debug info removed.
sass_options = (environment == :development) ? {:debug_info => true} : {:always_update => true}
