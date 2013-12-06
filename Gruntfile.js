module.exports = function(grunt) {

    grunt.initConfig({
        uglify: {
            my_target: {
                files: {
                    'src/js/dist/build.min.js': [
                        'src/js/jquery-1.10.2.min.js',
                        'src/js/lightbox.js',
                        'src/js/modernizr.custom.js',
                        'src/js/main.js'
                    ]
                }
            }
        }
    });

    // Load the plugin that provides the "uglify" task.
    grunt.loadNpmTasks('grunt-contrib-uglify');

    // Default task(s).
    grunt.registerTask('default', ['uglify']);

};
