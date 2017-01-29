var gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var imagemin = require('gulp-imagemin'),
    cache = require('gulp-cache');
var minifycss = require('gulp-minify-css');
var stylus = require('gulp-stylus');
var browserSync = require('browser-sync');
var nib = require('nib');
gulp.task('browser-sync', function() {
    browserSync({
        server: {
            baseDir: "./",
            // baseDir: "./assets/",carpeta assets
            directory: true
        }
    });
});

gulp.task('bs-reload', function() {
    browserSync.reload();
});

gulp.task('images', function() {
    gulp.src('src/images/**/*')
        .pipe(cache(imagemin({ optimizationLevel: 3, progressive: true, interlaced: true })))
        .pipe(gulp.dest('dist/images/'));
});

gulp.task('styles', function() {
    gulp.src(['src/styles/*.styl'])
        .pipe(plumber({
            errorHandler: function(error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(stylus({
            paths: ['node_modules'],
            import: ['nib', 'rupture/rupture'],
            use: [nib()],
            'include css': true
        }))
        // .pipe(autoprefixer('last 2 versions'))Depreciado por la función de NIB
        .pipe(gulp.dest('dist/styles/'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(minifycss())
        .pipe(gulp.dest('dist/styles/'))
        .pipe(browserSync.reload({ stream: true }))
});

gulp.task('scripts', function() {
    // return gulp.src('src/scripts/**/*.js')//para todos los scripts
    return gulp.src(['src/scripts/slicknav/**/*.js', 'src/scripts/sliders/**/*.js', 'src/scripts/tools/**/*.js'])
        .pipe(plumber({
            errorHandler: function(error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(concat('main.js'))
        .pipe(gulp.dest('dist/scripts/'))
        .pipe(rename({ suffix: '.min' }))
        .pipe(uglify())
        .pipe(gulp.dest('dist/scripts/'))
        .pipe(browserSync.reload({ stream: true }))
});

gulp.task('default', ['browser-sync'], function() {
    gulp.watch("src/styles/**/*.styl", ['styles']);
    gulp.watch("src/scripts/**/*.js", ['scripts']);
    gulp.watch("**/*.html", ['bs-reload']);
});