'use strict';

var gulp = require('gulp');

var plugins = require("gulp-load-plugins")({
    pattern: ['gulp-*', 'gulp.*', '@*/gulp{-,.}*'],
    scope: ['dependencies', 'devDependencies', 'peerDependencies'],
    rename: {
      'gulp-clean-css': 'cleancss',
      'gulp-ext-replace' : 'extreplace'
    },    
    replaceString: /\bgulp[\-.]/
});


/* --- File paths --- */
var srcPaths = {
  sass: './_source/scss/',
  js: './_source/js/'
};
var distPaths = {
  css: './deploy/html/css/pretty/',
  css_tmp: './_source/tmp/',
  css_min: './deploy/html/css/',
  js: './deploy/html/js/pretty/',
  js_min: './deploy/html/js/'
};

var watch_paths = [srcPaths.sass + '**/*.scss', srcPaths.js + '**/*.js']


/* --- gutils --- */
var gutil = require('gulp-util');
var changeEvent = function(evt) {
    gutil.log('File', gutil.colors.cyan(evt.path.replace(new RegExp('/.*(?=/' + srcPaths.sass + ')/'), '')), 'was', gutil.colors.magenta(evt.type));
};

/* --- SASS --- */
gulp.task('sass',  function () {
  return gulp.src(srcPaths.sass + '*.scss')
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.sass().on('error', plugins.sass.logError))
    .pipe(plugins.sourcemaps.write('.'))
    .pipe(gulp.dest(distPaths.css))
    .pipe(plugins.notify({message: 'Styles Compiled', onLast:true}));
});

gulp.task('minify-css', ['sass'], function() {
  return gulp.src(distPaths.css + '*.css')
    .pipe(plugins.cleancss())
    .pipe(gulp.dest(distPaths.css_tmp))
    .pipe(plugins.rename('nhspeakers.min.css'))
    .pipe(gulp.dest(distPaths.css_min))
    .pipe(plugins.notify({message: 'Styles Minified', onLast:true}));
});


gulp.task('js-copy', function() {
  return gulp.src([srcPaths.js + 'news.js', srcPaths.js + 'contact.js', srcPaths.js + 'staff.js'])
    .pipe(gulp.dest(distPaths.js));
});

// These Javascript files are used on all pages
gulp.task('js-base-concat', ['js-copy'], function() {
  return gulp.src([srcPaths.js + 'index.js', srcPaths.js + 'top-menu.js', srcPaths.js + 'img-club-slides.js'])
    .pipe(plugins.sourcemaps.init())
    .pipe(plugins.concat('base.js'))
    .pipe(plugins.sourcemaps.write())
    .pipe(gulp.dest(distPaths.js));
});

gulp.task('js-compress', ['js-base-concat'], function() {
  gulp.src(distPaths.js + '*.js')
    .pipe(plugins.minify({
        noSource: true,
        ext:{
            min:'.js'
        },
        exclude: ['tasks'],
        ignoreFiles: ['.combo.js', '.min.js']
    }))

    .pipe(gulp.dest(distPaths.js_min))
    .pipe(plugins.notify({message: 'Javascript Minified', onLast:true}))
});




gulp.task('watch', function(){
  gulp.watch([srcPaths.sass + '/**/*'], [ 'sass'])
    .on('change', function(evt){
      changeEvent(evt);
    });

  gulp.watch([srcPaths.js + '/**/*'], [ 'js-compress'])
    .on('change', function(evt){
      changeEvent(evt);
    });     
});

gulp.task('default', ['minify-css', 'watch']);