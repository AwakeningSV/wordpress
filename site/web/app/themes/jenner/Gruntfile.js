"use strict";

var sass = require('node-sass');
var path = require('path');

var banner = '/*\n' +
    'Copyright 2019 Reid Burke\n' +
    'Copyright 2018 Awakening Church\n' +
    'Copyright 2014 Brad Wrage and Reid Burke\n' +
    'All rights reserved.\n' +
    '*/';

module.exports = function (grunt) {
    process.env.SASS_PATH = path.join(__dirname, 'library/css');

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
                    implementation: sass
                },
                files: {
                    "build/all.css": "library/css/main.scss"
                }
            }
        },
        cssmin: {
            options: {
                banner: banner
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
    grunt.loadNpmTasks('grunt-sass');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    grunt.registerTask("default", ["sass:dist", "cssmin:minify"]);

};
