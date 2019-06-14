const { src, dest, watch, series, parallel } = require('gulp');
const clean = require('gulp-clean');
const gulpSass = require('gulp-sass');
const rename = require('gulp-rename');
const cleanCss = require('gulp-clean-css');
const sourcemaps = require('gulp-sourcemaps');
const browserSync = require('browser-sync').create("My Proxy server");

/* --- File paths --- */
var srcPaths = {
    sassSrc: './_source/scss/',
    js: './_source/js/'
  };
  var distPaths = {
    css: './deploy/html/css/pretty/',
    css_min: './deploy/html/css/',
    js: './deploy/html/js/pretty/',
    js_min: './deploy/html/js/',
    baseDirectory:'./deploy/html/',
    templates: './deploy/sys/user/templates/default_site/'
    
  };

  function cleanCssFolder() {
    return src([distPaths.css + '*.css', distPaths.css_min + '*.css'])
      .pipe(clean());
  }

  function compileSass() {
    return src(srcPaths.sassSrc + '**/*.scss')
      .pipe(gulpSass.sync().on('error', gulpSass.logError))
      .pipe(dest(distPaths.css));
  }
  
  function cssMinify() {
    return src(distPaths.css + '*.css')
      .pipe(sourcemaps.init())
      .pipe(cleanCss())
      .pipe(rename({ suffix: '.min'}))
      .pipe(sourcemaps.write('./'))
      .pipe(dest(distPaths.css_min))
  }
  
  function cleanJs() {
    return src([distPaths.js + '*.js', distPaths.js_min + '*.js'])
      .pipe(clean())
  }  

/* ========= BrowserSynce ===========*/

function simpleBs() {
    browserSync.init({
      proxy:"http://dev.speakers",
      serveStatic: [distPaths.baseDirectory],
      watch: true,
      files: [srcPaths.sassSrc + '**/*.scss',
        distPaths.templates + "**/*.html"]
    });
  }

function watcher () {
    simpleBs();
  
  
    watch([srcPaths.sassSrc + '**/*.scss'],
      series(cleanCssFolder, compileSass, cssMinify)
      );
  
    // watch([srcPaths.js + '**/*.js'],
    //   series(cleanJs, jsConcat, jsMinify)
    //   );  
  }
  
  
  exports.default = watcher;