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

		autoprefixer: {
      atcore: {
        src: 'at_core/styles/css/*.css',
        options: {
          map: true
        }
      },
      atgen: {
        src: 'at_generator/styles/css/*.css',
        options: {
          map: true
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
			}
    },

		watch: {
			atcore: {
				files: 'at_core/styles/sass/*.scss',
				tasks: ['compass:atcore', 'autoprefixer:atcore']
			},
			atgen: {
				files: 'at_generator/styles/sass/*.scss',
				tasks: ['compass:atgen', 'autoprefixer:atgen']
			}
		}
	});

  grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['watch:atcore']);
}
