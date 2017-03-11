var gulp = require('gulp');
var stylus = require('gulp-stylus');
var autoprefixer = require('gulp-autoprefixer');
var rename = require('gulp-rename');
// var browserSync = require('browser-sync').create();
var minifycss = require('gulp-clean-css');
var bootstrap = require('bootstrap-styl')
var sourcemaps = require('gulp-sourcemaps');
var nib = require('nib');
var connect = require('gulp-connect');

gulp.task('connect', function() {
    connect.server({
        root: './',
        livereload: true,
        open: true
    });
});
gulp.task('styles', function() {
    gulp.src('styles/*.styl')
        .pipe(sourcemaps.init())
        .pipe(stylus({
            paths: ['node_modules', 'styles/globals'],
            import: ['nib', 'rupture/rupture', 'variables', 'mixins'],
            use: [nib(), bootstrap()],
            'include css': true
        }))
        .pipe(rename({ suffix: '.min' }))
        .pipe(minifycss())
        .pipe(gulp.dest('css'))
        .pipe(connect.reload())
});
///////////////////////////
//Compila archivos .js   //
//////////////////////////

//////////////////////////////
// observa los cambios hechos dentro de las carpetas espef¡cificadas y corre la tarea 'styles'
//////////////////////////////
gulp.task('watch', function() {
    gulp.watch('styles/globals/**/*.styl', ['styles']);
    gulp.watch('styles/**/*.styl', ['styles']);
});
gulp.task('start', ['connect', 'watch']);
