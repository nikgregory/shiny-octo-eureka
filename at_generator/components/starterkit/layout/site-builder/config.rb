# Site Builder Compass/SASS Configuration
# NOTE: grunt will take its compass options from this file.

# Required
# -----------------------------------------------------------------------------
require "susy"


# Directory paths
# -----------------------------------------------------------------------------
css_dir = "css"
sass_dir = "sass"


# Precision
# -----------------------------------------------------------------------------
Sass::Script::Number.precision = 5


# Environment
# -----------------------------------------------------------------------------
#environment = :development
environment = :production


# Output Style
# -----------------------------------------------------------------------------
# Drupal will aggregate and compress stylesheets, we only need expanded, or
# nested if that's how you roll.
output_style = :expanded


# Line Comments
# -----------------------------------------------------------------------------
#line_comments = (environment == :development) ? true : false


# Sourcemaps
# -----------------------------------------------------------------------------
#sourcemap = (environment == :development) ? true : false
