module.exports = function (gulp, plugins, paths) {
    return {
        clean: function () {
            return plugins.del([
                paths.app_build + '**/*'
            ]);
        },
        pug: function () {
            return gulp.src(paths.app_src + 'ts/**/*.pug')
                .pipe(plugins.pug({
                    client: false,
                    pretty: true
                }))
                .pipe(plugins.rename({dirname: ''}))
                .pipe(gulp.dest(paths.app_build + 'templates/'))
        },
        tslint: function () {
            return gulp.src([paths.app_src + 'ts/**/*.ts', paths.app_src + 'tests/**/*.ts'])
                .pipe(plugins.tslint({
                    formatter: "verbose"
                }))
                .pipe(plugins.tslint.report({
                    emitError: false
                }))
        },
        typings: function () {
            return gulp.src('typings.json')
                .pipe(plugins.typings());
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
                    paths.libs + 'angular-material/angular-material.css',
                    paths.libs + 'angular-material-data-table/dist/md-data-table.min.css',
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
                        paths.app_src + 'ts/main.ts',
                        paths.app_src + 'ts/configuration/**/*.ts',
                        paths.app_src + 'ts/**/*.ts',
                        paths.app_build + 'typings/index.d.ts'
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
                        paths.libs + 'angular-ui-router/release/angular-ui-router.js',
                        paths.libs + 'angular-material/angular-material.js',
                        paths.libs + 'angular-animate/angular-animate.js',
                        paths.libs + 'angular-messages/angular-messages.js',
                        paths.libs + 'angular-aria/angular-aria.js',
                        paths.libs + 'angular-password/angular-password.js',
                        paths.libs + 'angular-material-data-table/dist/md-data-table.min.js'
                    ])
                    .pipe(plugins.concat('app_lib.min.js'))
                    .pipe(plugins.uglify({mangle: false}))
                    .pipe(gulp.dest(paths.dist + 'js'));
            }
        },
        karma: {
            default: function (cb) {
                new plugins.karma.Server({
                    configFile: __dirname + '/../karma.conf.js'
                }, cb).start();
            }
        }
    };
};