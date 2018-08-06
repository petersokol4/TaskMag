// --------------------------------------------
// Dependencies
// --------------------------------------------
var autoprefixer = require('gulp-autoprefixer'),
    concat = require('gulp-concat'),
    gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    sass = require('gulp-sass'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    connectPHP = require('gulp-connect-php'),
    browserSync = require('browser-sync').create();

//gulp-clean-css

// paths
var styleSrc = 'source/sass/**/*.sass',
    styleDest = 'build/assets/css/',
    htmlSrc = 'source/',
    htmlDest = 'build/',
    vendorSrc = 'source/js/vendors/',
    vendorDest = 'build/assets/js/',
    scriptSrc = 'source/js/*.js',
    scriptDest = 'build/assets/js/';




// --------------------------------------------
// Stand Alone Tasks
// --------------------------------------------


// Compiles all SASS files
gulp.task('sass', function() {
    return gulp.src('source/sass/**/*.sass')
        .pipe(plumber())
        .pipe(sass({
            style: 'compressed'
        }))
        .pipe(rename({
            basename: 'main',
            suffix: '.min'
          }))
        .pipe(gulp.dest('build/assets/css'))
        .pipe(browserSync.stream());
});


gulp.task('images', function() {
    return gulp.src('source/img/*.{gif,jpg,png,svg}')
        .pipe(gulp.dest('build/assets/img'));
});

// Move the javascript files into our /source/js/vendors
gulp.task('js', function() {
    return gulp.src(['node_modules/bootstrap/dist/js/bootstrap.min.js', 'node_modules/jquery/dist/jquery.min.js', 'node_modules/popper.js/dist/umd/popper.min.js'])
        .pipe(gulp.dest("source/js/vendors"))
        .pipe(browserSync.stream());
});

// Uglify js files
gulp.task('scripts', function() {
    gulp.src('source/js/*.js')
        .pipe(plumber())
        .pipe(uglify())
        .pipe(gulp.dest('build/assets/js'));
});

//Concat and Compress Vendor .js files
gulp.task('vendors', function() {
    gulp.src('source/js/vendors/*.js')
        .pipe(plumber())
        .pipe(concat('vendors.js'))
        .pipe(uglify())
        .pipe(gulp.dest('build/assets/js'));
});



// Watch for changes
//Apply and Configure PHP on Port 8010
gulp.task('watch', function() {
    connectPHP.server({
        base: "src",
        port: 8080,
        hostname:"0.0.0.0",
        keepalive: true
    });
});

//Apply and configure BrowserSync on Port 8080 to proxy the php instance on Port 8010
gulp.task('browser-sync',['watch'], function() {
    browserSync.init({
        proxy: '127.0.0.1:8080/TaskMag/build',
        port: 8081,
        open: true,
        notify: false
    });
});


    gulp.watch(styleSrc,['sass']);
    gulp.watch(scriptSrc,['scripts']);
    gulp.watch(vendorSrc,['vendors']);
    gulp.watch(['build/*.php', 'build/assets/css/*.css', 'build/assets/js/*.js', 'build/assets/js/vendors/*.js']).on('change', browserSync.reload);

// use default task to launch Browsersync and watch JS files
gulp.task('default', ['js', 'sass', 'scripts', 'vendors', 'watch', 'browser-sync', 'images'], function () {});
