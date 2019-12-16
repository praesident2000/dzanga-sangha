var gulp         = require('gulp');
var gulpSass     = require('gulp-sass');
var uglifycss    = require('gulp-uglifycss');
var minify       = require('gulp-minify');
var concat       = require('gulp-concat');
var autoprefixer = require('gulp-autoprefixer');
var bro          = require('gulp-bro');

function sass(done) {
    gulp.src('./sass/**/*.scss')
      .pipe(gulpSass().on('error', gulpSass.logError))
      .pipe(gulp.dest('./css'));
    done();
};

function css(done) {
    gulp.src('./css/**/*.css')
        .pipe(autoprefixer({
          browsers: ['last 2 versions'],
          cascade: false
        }))
        .pipe(uglifycss({
          "uglyComments": true
        }))
        // .pipe(concat('style.min.css'))
        .pipe(gulp.dest('./dist/'));

    done();
};

function mainJs (done) {
    gulp.src('index.js')
        .pipe(bro())
        .pipe(minify({
            noSource: true
        }))
        .pipe(concat('index.min.js'))
        .pipe(gulp.dest('./dist'));
    done();
}

function js (done) {
    gulp.src('js/*.js')
        .pipe(bro())
        .pipe(minify({
            ext:{
                min:'.min.js',
            },
            noSource: true,
        }))
        .pipe(gulp.dest('./dist'));
    done();
}

function watchFiles() {
    gulp.watch('sass/**/*.scss', sass);
    gulp.watch('css/**/*.css', css);
    gulp.watch('js/**/*.js', js);
    gulp.watch('index.js', mainJs);
}

gulp.task("sass", sass);

gulp.task("css", css);

gulp.task("js", js);

gulp.task("mainJs", mainJs);

gulp.task("default", gulp.parallel(sass,css,js,mainJs,watchFiles));

gulp.task('run', gulp.parallel(sass,css,js,mainJs));

gulp.task('watch', gulp.series(watchFiles));
