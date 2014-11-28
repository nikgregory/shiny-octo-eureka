module.exports = function(grunt) {

  var autoprefixer = require('autoprefixer-core');

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
    postcss: {
      options: {
        processors: [
          autoprefixer().postcss
        ]
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
		watch: {
			css: {
				files: 'components/**/*.scss',
				tasks: ['compass', 'postcss']
			}
		}
	});


	grunt.loadNpmTasks('grunt-contrib-compass');
	grunt.loadNpmTasks('grunt-postcss');
	grunt.loadNpmTasks('grunt-contrib-watch');

	grunt.registerTask('default', ['watch']);
}
