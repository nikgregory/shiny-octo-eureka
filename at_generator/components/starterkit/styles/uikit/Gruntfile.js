module.exports = function(grunt) {
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		autoprefixer: {
      css: {
        src: '../css/components/**.css',
        options: {
          map: true
        }
      }
		},
		compass: {
			dist: {
				options: {
          config: 'config.rb',
          bundleExec: true
				}
			}
		},
    csslint: {
      options: {
        csslintrc: '.csslintrc'
      },
      strict: {
        options: {
          import: 2
        },
        src: ['../css/components/**.css']
      }
    },
		watch: {
			scss: {
				files: 'components/**/*.scss',
				tasks: ['compass:dist', 'autoprefixer:css']
			}
		}
	});
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-contrib-csslint');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.registerTask('default', ['watch']);
}
