module.exports = function(grunt) {
	grunt.initConfig({

		pkg: grunt.file.readJSON('package.json'),

    uglify: {
      min: {
        files: [{
          expand: true,
          mangle: false,
          preserveComments: 'some',
          src: '*.js',
          dest: 'at_core/scripts/min',
          cwd: 'at_core/scripts',
          rename: function(dest, src) { return dest + '/' + src.replace('.js', '.min.js'); }
        }]
      }
    },

    postcss: {
      atcore: {
        src: 'at_core/styles/css/*.css',
         options: {
          map: {
            inline: false,
            //annotation: 'at_core/styles/css/maps/',
          },
          processors: [
            require('autoprefixer')({browsers: 'last 5 versions'})
          ]
        }
      },
      atgen: {
        src: 'at_generator/styles/css/*.css',
        options: {
          map: {
            inline: false,
            //annotation: 'at_core/styles/css/maps/',
          },
          processors: [
            require('autoprefixer')({browsers: 'last 5 versions'})
          ]
        }
      },
      at_layout_plugin: {
        src: 'at_core/layout_plugin/css/**/*.css',
        options: {
          map: {
            inline: false,
            //annotation: 'at_core/styles/css/maps/',
          },
          processors: [
            require('autoprefixer')({browsers: 'last 5 versions'})
          ]
        }
      }
    },

		compass: {
			atcore: {
				options: {
          config: 'at_core/styles/config.rb',
          basePath: 'at_core/styles',
          bundleExec: true
				}
			},
			atgen: {
				options: {
          config: 'at_generator/styles/config.rb',
          basePath: 'at_generator/styles',
          bundleExec: true
				}
			},
      at_layout_plugin: {
        options: {
          config: 'at_core/layout_plugin/config.rb',
          basePath: 'at_core/layout_plugin',
          bundleExec: true
        }
      }
		},

    csslint: {
      atcore: {
        options: {
          csslintrc: '.csslintrc'
        },
        src: ['at_core/styles/css/*.css']
	    },
      atgen: {
        options: {
          csslintrc: '.csslintrc'
        },
        src: ['at_generator/styles/css/*.css']
			},
      at_layout_plugin: {
        options: {
          csslintrc: '.csslintrc'
        },
        src: ['at_core/layout_plugin/css/**/*.css']
      }
    },

		watch: {
			atcore: {
				files: 'at_core/styles/sass/*.scss',
				tasks: ['compass:atcore', 'postcss:atcore']
			},
			atgen: {
				files: 'at_generator/styles/sass/*.scss',
				tasks: ['compass:atgen', 'postcss:atgen']
			},
      at_layout_plugin: {
        files: 'at_core/layout_plugin/sass/**/*.scss',
        tasks: ['compass:at_layout_plugin', 'postcss:at_layout_plugin']
      }
		}
	});

  grunt.loadNpmTasks('grunt-postcss');
  grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['watch:atcore']);
}
