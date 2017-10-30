/**
 *
 * Gulp File for North Herts Speakers
 * Created 30th October 2017
 * Climbing Turn Ltd -<www.climbingturn.co.uk>
 * Version: 1
 *
 */

var gulp = require('gulp');
var compass = require('gulp-compass');
var concat = require('gulp-concat');
var cleanCSS = require('gulp-clean-css');
var rename = require("gulp-rename");
var notify = require("gulp-notify");
var watch = require('gulp-watch');
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');

// var cpy = require('gulp-copy');

/* --- File paths --- */
var srcPaths = {
    scss: './_source/scss/',
    scss_vendor: './_source/vendor_scss/',
    js_vendor: './_source/js/vendor/',
    js_custom: './_source/js'
};
var distPaths = {
    css_pretty: './deploy/html/css/pretty/',
    css_min: './deploy/html/css/',
    js_pretty: './deploy/html/js/pretty/',
    js_min: './deploy/html/js/'
};


var temp_path = './_source/tmp/'

/* -------------------------------------------------------------------------------------------- 
 * Minify vendor files that aren't already min,
 * then copy them all to the pretty/vendor folder
 * and concatentate them into vendor.min.css 
   --------------------------------------------------------------------------------------------*/


// // minify vendor css that aren't already minified and copy them to temp/pretty/vendor
// gulp.task('minify_vendor_css', function() {

//     gulp.src([srcPaths.scss_vendor + 'owl.carousel.css', srcPaths.scss_vendor + 'owl.transitions.css'])
//         .pipe(cleanCSS())
//         .pipe(gulp.dest(temp_folders.vendor));

// });


// // copy vendor files that are already minified to the pretty/vendor folder
// gulp.task('copy_minified_vendor_css', ['minify_vendor_css'], function() {

//     gulp.src([srcPaths.scss_vendor + 'bootstrap.min.css', srcPaths.scss_vendor + 'bootstrap-select.min.css', srcPaths.scss_vendor + 'jquery.slider.min.css'])
//         .pipe(gulp.dest(temp_folders.vendor));

// });


// // concatenate the minified vendor css
// gulp.task('vendor_css', ['copy_minified_vendor_css'], function() {

//     gulp.src([temp_folders.vendor + 'bootstrap.min.css', temp_folders.vendor + 'bootstrap.select.css', temp_folders.vendor + 'jquery.slider.min.css', temp_folders.vendor + 'owl.carousel.css', temp_folders.vendor + 'owl.transitions.css'])
//         .pipe(concat('vendor.min.css'))
//         .pipe(gulp.dest(distPaths.css_min))
//         .pipe(notify('VENDOR CSS compiled and Minified'))
// });


/* ---------------------------------------------------------------------------------------------
 * Compile and Minify the custom scss
 * --------------------------------------------------------------------------------------------*/

// gulp.task('compass', function() {
// 
//   gulp.src([srcPaths.scss + 'style.scss'])
//     .pipe(compass({
//       sass: srcPaths.scss,
//       css: temp_folders.custom
//     }))
//     .pipe(gulp.dest(temp_folders.custom));
// 
// });


gulp.task('sass2css', function() {

    gulp.src(srcPaths.scss + 'wheatley.scss')
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(distPaths.css_pretty));

});



gulp.task('minify_css', ['sass2css'], function() {

    gulp.src(distPaths.css_pretty + 'wheatley.css')
        .pipe(cleanCSS())
        .pipe(gulp.dest(temp_path));

});


gulp.task('css', ['minify_css'], function() {

    gulp.src(temp_path + 'wheatley.css')
        .pipe(rename(distPaths.css_min + 'wheatley.min.css'))
        .pipe(gulp.dest('./'))
        .pipe(notify('Custom styles wheatley.min.css compiled'));

});


// WATCH

gulp.task('watch', function () {
    gulp.watch([srcPaths.scss + '/**/*'], ['css']);
});

gulp.task('default', ['watch']);