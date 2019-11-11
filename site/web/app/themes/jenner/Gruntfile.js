module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON("package.json"),
        watch: {
            scss: {
                files: ["library/css/*.scss"],
                tasks: ["default"],
                options: {
                    spawn: false
                }
            }
        },
        sass: {
            dist: {
                options: {
                    loadPath: ["library/css"] // includePaths for native
                },
                files: {
                    "build/all.css": "library/css/main.scss"
                }
            }
        },
        cssmin: {
            options: {
                banner: "/* Portions Copyright 2014 Brad Wrage and Reid Burke. All rights reserved. */"
            },
            minify: {
                expand: true,
                cwd: 'build/',
                src: ['*.css', '!*.min.css'],
                dest: 'build/',
                ext: '.min.css'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.registerTask("default", ["sass:dist", "cssmin:minify"]);

};
