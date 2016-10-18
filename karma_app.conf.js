module.exports = function (config) {

    config.set({
        basePath: '',

        frameworks: ['jasmine'],

        // list of files / patterns to load in the browser
        files: [
            'web/dist/js/app_lib.min.js',
            'node_modules/angular-mocks/angular-mocks.js',
            'web/dist/js/app.min.js',

            {pattern: 'source_js/app/test_build/**/*.js', include: false},

            'source_js/app/source/tests/**/*.test.ts'
        ],

        exclude: [],
        reporters: ['progress', 'junit', 'coverage', 'karma-remap-istanbul'],
        junitReporter: {
            // will be resolved to basePath (in the same way as files/exclude patterns)
            outputFile: 'test-results.xml'
        },

        typescriptPreprocessor: {
            // options passed to the typescript compiler
            options: {
                sourceMap: false, // (optional) Generates corresponding .map file.
                target: 'ES5', // (optional) Specify ECMAScript target version: 'ES3' (default), or 'ES5'
                module: 'amd', // (optional) Specify module code generation: 'commonjs' or 'amd'
                noImplicitAny: false, // (optional) Warn on expressions and declarations with an implied 'any' type.
                noResolve: false, // (optional) Skip resolution and preprocessing.
                removeComments: false, // (optional) Do not emit comments to output.
                concatenateOutput: true // (optional) Concatenate and emit output to single file. By default true if module option is omited, otherwise false.
            },
            // extra typing definitions to pass to the compiler (globs allowed)
            typings: [
                'source_js/app/build/js/app.ts.d.ts',
                'source_js/app/source/typings/tsd.d.ts',
                'source_js/utils/build/js/utils.ts.d.ts'
            ]
        },

        preprocessors: {
            'source_js/app/source/tests/**/*.test.ts': ['typescript'],
            'source_js/app/test_build/**/!(Routes|NavigationService|Abstract*).js': ['coverage'],
            'source_js/app/source/ts/**/*.ts': ['typescript']
        },

        coverageReporter: {
            type: 'json',
            dir: 'source_js/app/coverage/',
            subdir: '.',
            file: 'coverage-final.json',
            includeAllSources: true,
            check: {
                global: {
                    statements: 75,
                    functions: 75,
                    lines: 75
                }
            }
        },

        remapIstanbulReporter: {
            src: 'source_js/app/coverage/coverage-final.json',
            reports: {
                html: 'source_js/app/coverage/'
            },
            timeoutNotCreated: 1000,
            timeoutNoMoreFiles: 1000
        },

        // web server port
        // CLI --port 9876
        port: 9876,

        // enable / disable colors in the output (reporters and logs)
        // CLI --colors --no-colors
        colors: true,

        // level of logging
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        // CLI --log-level debug
        logLevel: config.LOG_INFO,

        // enable / disable watching file and executing tests whenever any file changes
        // CLI --auto-watch --no-auto-watch
        autoWatch: true,

        // Start these browsers, currently available:
        // - Chrome
        // - ChromeCanary
        // - Firefox
        // - Opera
        // - Safari (only Mac)
        // - PhantomJS
        // - IE (only Windows)
        // CLI --browsers Chrome,Firefox,Safari
        browsers: ['PhantomJS'],

        // If browser does not capture in given timeout [ms], kill it
        // CLI --capture-timeout 5000
        captureTimeout: 5000,

        // Auto run tests on start (when browsers are captured) and exit
        // CLI --single-run --no-single-run
        singleRun: false,

        // report which specs are slower than 100ms
        // CLI --report-slower-than 100
        reportSlowerThan: 100,

        plugins: [
            'karma-jasmine',
            'karma-chrome-launcher',
            'karma-firefox-launcher',
            'karma-phantomjs-launcher',
            'karma-junit-reporter',
            'karma-coverage',
            'karma-remap-istanbul',
            'karma-typescript-preprocessor'
        ]

    });
};