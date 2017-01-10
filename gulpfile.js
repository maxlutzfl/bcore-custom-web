/**
 * gulpfile.js
 */

// Setup
var themeName = 'bcore-child',
	developmentUrl = 'bcore.com.local',
	baseThemeDir = 'web/wp-content/themes/' + themeName,
	baseResourcesDir = baseThemeDir + '/resources',
	baseSassFile = baseResourcesDir + '/css/sass/base.scss',
	baseSassDir = baseResourcesDir + '/css/sass',
	baseCssDir = baseResourcesDir + '/css/css',
	baseJsDir = baseResourcesDir + '/js'
;


// Imports
var browsersync = require('browser-sync'),
	gulp = require('gulp');
	autoprefix = require('gulp-autoprefixer'),
	concat = require('gulp-concat'),
	size = require('gulp-size'),
	jshint = require('gulp-jshint'),
	plumber = require('gulp-plumber'),
	rename = require('gulp-rename'),
	sass = require('gulp-sass'),
	sasslint = require('gulp-sass-lint'),
	sourcemap = require('gulp-sourcemaps'),
	uglify = require('gulp-uglify'),
	watch = require('gulp-watch'),
	stylish = require('jshint-stylish')
;

// Compiles all of our .scss files into minified 
// and unminified CSS files and generates a sourcemap
gulp.task(
	'bcore:css',
	function() {
		return gulp.src(baseSassFile)

			// Run sass for un-minified CSS file
			.pipe(sass().on('error', sass.logError))
			.pipe(autoprefix())
			.pipe(rename('main.css'))
			.pipe(gulp.dest(baseCssDir))
			.pipe(size({showFiles: true}))

			// Run sass for minified CSS file
			.pipe(sourcemap.init())
			.pipe(sass({outputStyle: 'compressed'}))
			.pipe(autoprefix())
			.pipe(rename('main.min.css'))
			.pipe(sourcemap.write('./'))
			.pipe(gulp.dest(baseCssDir))
			.pipe(size({showFiles: true}))
		;
	}
);

// Compiles all js plugin files and custom script files
// into minified and unminified files and generates a sourcemap
gulp.task(
	'bcore:js',
	function() {
		return gulp.src([baseJsDir + '/vendor/*', baseJsDir + '/theme/*'])

			// Handle errors
			.pipe(plumber({errorHandler: function() { this.emit('end'); }}))
			.pipe(jshint())
			.pipe(jshint.reporter(stylish))

			// Unminified
			.pipe(concat('main.js'))
			.pipe(gulp.dest(baseJsDir))
			.pipe(size({showFiles: true}))

			// Minify with sourcemap
			.pipe(sourcemap.init())
			.pipe(rename('main.min.js'))
			.pipe(uglify())
			.pipe(sourcemap.write('./'))
			.pipe(gulp.dest(baseJsDir))
			.pipe(size({showFiles: true}))
		;
	}
);

gulp.task(
	'bcore:server',
	function() {
		gulp.watch(baseSassDir + '/**/*.scss', ['bcore:css']);
		gulp.watch(baseJsDir + '/theme/*.js', ['bcore:js']);
		browsersync({
			proxy: developmentUrl,
			open: false,
			ghostMode: false,
			files: [
				baseThemeDir + '**/*.php',
				baseCssDir + 'main.css',
				baseJsDir + 'main.js',
			]
		});
	}
);

gulp.task(
	'default', [
		'bcore:server'
	]
);




