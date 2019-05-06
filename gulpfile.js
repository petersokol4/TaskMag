// --------------------------------------------
// Dependencies
// --------------------------------------------

var autoprefixer = require('gulp-autoprefixer'),
    concat = require('gulp-concat'),
    gulp = require('gulp'),
    plumber = require('gulp-plumber'),
    sass = require('gulp-sass'),
    uglify = require('gulp-uglify'),
    minify = require('gulp-clean-css'),
    imagemin = require('gulp-imagemin'),
    connectPHP = require('gulp-connect-php'),
    rename = require('gulp-rename'),
    browserSync = require('browser-sync').create();

// paths
var styleSrc = 'source/sass/**/*.sass',
    styleDest = 'build/public_html/assets/css/',
    vendorSrc = 'source/js/vendors/',
    vendorDest = 'build/public_html/assets/js/',
    scriptSrc = 'source/js/*.js',
    scriptDest = 'build/public_html/assets/js/';


// --------------------------------------------
// Stand Alone Tasks
// --------------------------------------------


// Compiles all bootstrap SASS files and add Autoprefixes
gulp.task('sass', function () {
    return gulp.src(['source/sass/main.sass'])
        .pipe(plumber())
        .pipe(sass({
            outputStyle: 'compressed'
        }).on('error', sass.logError))
        .pipe(autoprefixer({
            cascade: false
        }))
        .pipe(gulp.dest('build/public_html/assets/css'))
        .pipe(browserSync.stream());
});

//Minify images and move them to build folder
gulp.task('images', function () {
    return gulp.src('source/img/*.{gif,jpg,png,svg,.JPG}')
        .pipe(plumber())
        .pipe(imagemin())
        .pipe(gulp.dest('build/public_html/assets/img'));
});

// Move the javascript files into /source/js/vendors folder
gulp.task('js', function () {
    return gulp.src([
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.bundle.min.js',
        'node_modules/flatpickr/dist/flatpickr.min.js',
        'node_modules/flatpickr/dist/l10n/sk.js',
        'node_modules/jquery-validation/dist/jquery.validate.js',
        'jquery-ui-1.12.1.custom/jquery-ui_custom.min.js',
        'node_modules/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js',
        'node_modules/chart.js/dist/Chart.min.js',
        'node_modules/autosize/dist/autosize.min.js',
        // 'node_modules/timeago.js/dist/timeago.min.js',
        // 'node_modules/autogrow/autogrow.min.js',
        'node_modules/bs-custom-file-input/dist/bs-custom-file-input.min.js',
        'node_modules/chartjs-plugin-empty-overlay/dist/chartjs-plugin-empty-overlay.min.js',
        'node_modules/bootstrap-select/dist/js/bootstrap-select.min.js',
        'node_modules/bootstrap-select/dist/js/i18n/defaults-sk_SK.min.js',
        'node_modules/bootstrap-slider/dist/bootstrap-slider.min.js'

    ])
        .pipe(plumber())
        .pipe(gulp.dest("source/js/vendors"))
        .pipe(browserSync.stream());
});

// Move the css files into /source/css/vendors folder
gulp.task('css', function () {
    return gulp.src([
        'node_modules/flatpickr/dist/flatpickr.css',
        'node_modules/bootstrap-select/dist/css/bootstrap-select.min.css',
        'node_modules/bootstrap-slider/dist/css/bootstrap-slider.min.css'

    ])
        .pipe(plumber())
        .pipe(gulp.dest("source/css/vendors"))
        .pipe(browserSync.stream());
});

// Minify css files and move
gulp.task('stylesheet', ['css'], function () {
    gulp.src('source/css/vendors/*.css')
        .pipe(plumber())
        .pipe(concat('vendors.css'))
        .pipe(rename({suffix: ".min"}))
        .pipe(minify())
        .pipe(gulp.dest("build/public_html/assets/css/vendors"))
        .pipe(browserSync.stream());
});

// Minify js files and move
gulp.task('scripts', function () {
    gulp.src('source/js/*.js')
        .pipe(plumber())
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('build/public_html/assets/js'));
});

//Concat and Compress Vendor .js files
gulp.task('vendors', ['js'], function () {
    gulp.src([

        //zachovanie poradia
        'source/js/vendors/jquery.min.js',
        'source/js/vendors/jquery-ui_custom.min.js',
        'source/js/vendors/jquery.ui.touch-punch.min.js',
        'source/js/vendors/bootstrap.bundle.min.js', // must be after jquery UI - tooltip (popper.js) conflict
        'source/js/vendors/jquery.validate.js',
        'source/js/vendors/flatpickr.min.js',
        'source/js/vendors/sk.js',
        'source/js/vendors/Chart.min.js',
        'source/js/vendors/autosize.min.js',
        // 'source/js/vendors/timeago.min.js',
        // 'source/js/vendors/autogrow.min.js',
        'source/js/vendors/bs-custom-file-input.min.js',
        'source/js/vendors/chartjs-plugin-empty-overlay.min.js',
        'source/js/vendors/bootstrap-select.min.js',
        'source/js/vendors/defaults-sk_SK.min.js',
        'source/js/vendors/bootstrap-slider.min.js'

    ])
        .pipe(plumber())
        .pipe(concat('vendors.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('build/public_html/assets/js'));
});


// Watch for changes
//Apply and Configure PHP on Port 80
//TODO: changed

gulp.task('server', function () {
    connectPHP.server({
        base: "build/public_html",  //From which folder the webserver will be served. Defaults to the directory of the gulpfile.
        port: 80, //The port on which you want to access the webserver. Task will fail if the port is already in use.
        hostname: "0.0.0.0", //The hostname the webserver will use. | Use 0.0.0.0 if you want it to be accessible from the outside.
        keepalive: true
    });
});

//Apply and configure BrowserSync on Port 8081
gulp.task('browser-sync', ['server'], function () {
    browserSync.init({
        proxy: 'localhost/TaskMag/build/public_html',  //PHP server
        port: 8081, //new port
        open: true,
        notify: false
    });
});


gulp.watch(styleSrc, ['sass']);
gulp.watch(scriptSrc, ['scripts']);
gulp.watch(vendorSrc, ['vendors']);
gulp.watch(['build/public_html/*.php', 'build/public_html/assets/css/*.css', 'build/public_html/assets/js/*.js', 'build/public_html/assets/js/vendors/*.js', 'source/sass/**/*.sass']).on('change', browserSync.reload);

// use default task to launch all tasks
gulp.task('default', ['js', 'sass', 'scripts', 'server', 'browser-sync', 'images', 'css', 'stylesheet', 'vendors'], function () {
});
