module.exports = function (gulp, plugins, paths, tasks) {
    gulp.task('app:clean', tasks.clean);
    gulp.task('app:tsd', tasks.tsd);
    gulp.task('app:tslint', tasks.tslint);
    gulp.task('app:scss', tasks.scss);
    gulp.task('app:minify-css', ['app:scss'], tasks.minifyCss);
    gulp.task('app:jade', tasks.jade);
    gulp.task('app:ng-templates', ['app:jade'], tasks.ngTemplates);
    gulp.task('app:typescript:default', ['app:tsd', 'app:tslint'], tasks.typescript.default);
    gulp.task('app:typescript:karma', tasks.typescript.karma);
    gulp.task('app:uglify:default', ['app:ng-templates', 'app:typescript:default'], tasks.uglify.default);
    gulp.task('app:uglify:libs', tasks.uglify.libs);
    gulp.task('app:uglify:default:templates', ['app:ng-templates'], tasks.uglify.default);
    gulp.task('app:uglify:default:sources', ['app:typescript:default'], tasks.uglify.default);

    gulp.task('app:build:css', ['app:minify-css']);
    gulp.task('app:build:js', ['app:build:js:default', 'app:build:js:libs']);
    gulp.task('app:build:js:default', ['app:uglify:default']);
    gulp.task('app:build:js:libs', ['app:uglify:libs']);

    gulp.task('app:build', ['app:build:css', 'app:build:js']);
    gulp.task('app:karma', ['app:typescript:karma'], tasks.karma.default);
    gulp.task('app:karma:dev', ['app:typescript:karma'], tasks.karma.dev);
};