module.exports = function (config) {
    config.set({
        frameworks: ["jasmine", "karma-typescript"],
        files: [
            'web/dist/js/app_lib.min.js',
            'node_modules/angular-mocks/angular-mocks.js',
            'web/dist/js/app.min.js',
            {pattern: 'source_js/build/typings/globals/**/*', included: false},
            {pattern: 'source_js/source/tests/**/*.ts', included: true},
            {pattern: 'source_js/source/ts/**/*.ts', included: false}
        ],
        preprocessors: {
            "source_js/source/**/*.ts": ["karma-typescript"]
        },
        karmaTypescriptConfig: {
            compilerOptions: {
                noImplicitAny: false,
                target: 'ES5',
                module: 'amd',
                removeComments: true,
                declaration: true,
                experimentalDecorators: true
            },
            include: ['source_js/source/tests/**/*.ts', 'source_js/source/ts/**/*.ts', 'source_js/build/typings/globals/**/*']
        },
        singleRun: true,
        port: 9876,
        colors: true,
        reporters: ["nyan", "karma-typescript"],
        browsers: ["PhantomJS"],
        logLevel: config.LOG_INFO, //config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        autoWatch: false,
        concurrency: Infinity
    });
};