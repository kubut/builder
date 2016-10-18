module.exports = function (gulp, plugins, paths) {
    return {
        clean: function () {
            return plugins.del([
                paths.app_build + '**/*',
                paths.app_test + '**/*',
                '!' + paths.app_test + '.gitkeep'
            ]);
        },
        jade: function () {
            return gulp.src(paths.app_src + 'ts/**/*.jade')
                .pipe(plugins.jade({
                    client: false,
                    pretty: true
                }))
                .pipe(plugins.rename({dirname: ''}))
                .pipe(gulp.dest(paths.app_build + 'templates/'))
        },
        tslint: function () {
            return gulp.src([paths.app_src + 'ts/**/*.ts', paths.app_src + 'tests/**/*.ts'])
                .pipe(plugins.tslint())
                .pipe(plugins.tslint.report("full", {
                    emitError: false
                }))
        },
        tsd: function (callback) {
            return plugins.tsd({
                command: 'reinstall',
                config: 'tsd_app.json',
                latest: false
            }, callback);
        },
        less: function () {
            return gulp.src(paths.app_src + 'less/main.less')
                .pipe(plugins.less({
                    compress: true
                }))
                .pipe(plugins.rename('main.min.css'))
                .pipe(gulp.dest(paths.app_build + 'css/'));
        },
        scss: function () {
            return gulp
                .src([
                    paths.app_layout_src + 'main.scss'
                ], {
                    base: paths.app_layout_src + '/'
                })
                .pipe(plugins.changed(paths.app_build))
                .pipe(plugins.sass.sync().on('error', plugins.sass.logError))
                .pipe(plugins.autoprefixer())
                .pipe(plugins.rename('main.min.css'))
                .pipe(gulp.dest(paths.app_build + 'css/'));
        },
        minifyCss: function () {
            return gulp.src([
                    paths.libs + 'font-awesome/css/font-awesome.css',
                    paths.libs + 'bootstrap/dist/css/bootstrap.css',
                    paths.libs + 'angular-ui-bootstrap/dist/ui-bootstrap-csp.css',
                    paths.libs + 'angular-growl-v2/build/angular-growl.css',
                    paths.libs + 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.min.css',
                    paths.app_build + 'css/main.min.css'
                ])
                .pipe(plugins.cleanCss())
                .pipe(plugins.concat('main.min.css'))
                .pipe(gulp.dest(paths.dist + 'css/'));
        },
        ngTemplates: function () {
            return gulp.src(paths.app_build + 'templates/*.html')
                .pipe(plugins.ngTemplate({
                    moduleName: 'templates',
                    prefix: '/templates/',
                    standalone: true,
                    filePath: 'template.js'
                }))
                .pipe(gulp.dest(paths.app_build + 'js'));
        },
        typescript: {
            default: function () {
                var tsResult = gulp.src([
                        paths.app_src + 'ts/**/*.ts',
                        paths.app_src + 'typings/tsd.d.ts',
                        paths.utils_build + 'js/utils.ts.d.ts'
                    ])
                    .pipe(plugins.sourcemaps.init())
                    .pipe(plugins.typescript({
                        noImplicitAny: false,
                        out: 'app.ts.js',
                        target: 'ES5',
                        module: 'amd',
                        removeComments: true,
                        declaration: true,
                        experimentalDecorators: true
                    }));

                return plugins.merge([
                    tsResult.dts.pipe(gulp.dest(paths.app_build + 'js')),
                    tsResult.js
                        .pipe(plugins.sourcemaps.write('./'))
                        .pipe(gulp.dest(paths.app_build + 'js'))

                ]);
            },
            karma: function () {
                return gulp.src([
                        paths.app_src + 'ts/**/*.ts',
                        paths.app_src + 'typings/tsd.d.ts',
                        paths.utils_build + 'js/utils.ts.d.ts'
                    ])
                    .pipe(plugins.sourcemaps.init())
                    .pipe(plugins.typescript({
                        noImplicitAny: false,
                        target: 'ES5',
                        module: 'amd',
                        removeComments: true,
                        declaration: true
                    }))
                    .pipe(plugins.sourcemaps.write('./'))
                    .pipe(gulp.dest(paths.app_test));
            }
        },
        uglify: {
            default: function () {
                return gulp.src([
                        paths.app_build + 'js/template.js',
                        paths.app_build + 'js/app.ts.js'
                    ])
                    .pipe(plugins.concat('app.min.js'))
                    .pipe(plugins.uglify({mangle: false}))
                    .pipe(gulp.dest(paths.dist + 'js'));
            },
            libs: function () {
                return gulp.src([
                        paths.libs + 'lodash/lodash.js',
                        paths.libs + 'jquery/dist/jquery.min.js',
                        paths.libs + 'angular/angular.js',
                        paths.libs + 'bootstrap/dist/js/bootstrap.min.js',
                        paths.libs + 'angular-messages/angular-messages.js',
                        paths.libs + 'angular-ui-router/release/angular-ui-router.js',
                        paths.libs + 'angular-translate/dist/angular-translate.js',
                        paths.libs + 'angular-translate/dist/angular-translate-loader-static-files/angular-translate-loader-static-files.js',
                        paths.libs + 'angular-growl-v2/build/angular-growl.js',
                        paths.libs + 'angular-ui-bootstrap/dist/ui-bootstrap.js',
                        paths.libs + 'angular-ui-bootstrap/dist/ui-bootstrap-tpls.js',
                        paths.libs + 'moment/min/moment.min.js',
                        paths.libs + 'moment/locale/pl.js',
                        paths.libs + 'angular-moment/angular-moment.min.js',
                        paths.libs + 'angular-bowser/src/angular-bowser.js',
                        paths.libs + 'angular-password/angular-password.js',
                        paths.libs + 'angular-cookies/angular-cookies.js',
                        paths.libs + 'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
                        paths.utils_build + 'js/utils.ts.js'
                    ])
                    .pipe(plugins.concat('app_lib.min.js'))
                    .pipe(plugins.uglify({mangle: false}))
                    .pipe(gulp.dest(paths.dist + 'js'));
            }
        },
        karma: {
            default: function (cb) {
                new plugins.karma.Server({
                    configFile: __dirname + '/../karma_app.conf.js',
                    singleRun: true
                }, cb).start();
            },
            dev: function (cb) {
                new plugins.karma.Server({
                    configFile: __dirname + '/../karma_app.conf.js',
                    singleRun: false
                }, cb).start();
            }
        }
    };
};