'use strict';

var gulp = require('gulp');
var zip = require('gulp-zip');
var notify = require('gulp-notify');

/**
 * Build Plugin Zip
 */
gulp.task('zip', function () {
    return gulp.src( [
        // Include
        './**/*',

        // Exclude
        '!./**/.DS_Store',
        '!./node_modules/**',
        '!./node_modules',
        '!./package.json',
        '!./gulpfile.js'
    ])
        .pipe ( zip ( 'simple-support-system.zip' ) )
        .pipe ( gulp.dest ( '../' ) )
        .pipe ( notify ( {
            message : 'Simple Support System plugin zip is ready.',
            onLast : true
        } ) );
});