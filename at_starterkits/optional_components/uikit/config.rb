
# Required plugins
# -----------------------------------------------------------------------------
require "compass"
require "modular-scale"


# Directory paths
# -----------------------------------------------------------------------------
css_dir = "../"
sass_dir = "/"
images_dir = "images"


# SASS core
# -----------------------------------------------------------------------------
# Versions of Chrome need precision 7 to counter rounding errors.
Sass::Script::Number.precision = 7


# Output style and comments
# -----------------------------------------------------------------------------
# Set the Environment Variable
# Using :development enables the use of FireSass.
#environment = :development
environment = :production


# You can select your preferred output style here (:expanded, :nested, :compact
# or :compressed).
output_style = (environment == :production) ? :expanded : :expanded


# To enable relative paths to assets via compass helper functions.
relative_assets = true


# Conditionally enable line comments when in development mode.
line_comments = (environment == :production) ? false : true


# Output debugging info in development mode.
sass_options = (environment == :production) ? {} : {:debug_info => true}


# Pass options to sass.
# - For development, we turn on the FireSass-compatible debug_info.
# - For production, we force the CSS to be regenerated even though the source
#   scss may not have changed, since we want the CSS to be compressed and have
#   the debug info removed.
sass_options = (environment == :development) ? {:debug_info => true} : {:always_update => true}
