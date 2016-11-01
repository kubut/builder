module.exports = function(config) {
    config.set({
        basePath: '',
        exclude: [],
        frameworks: ["jasmine", "karma-typescript"],
        files: [
            'web/dist/js/app_lib.min.js',
            'node_modules/angular-mocks/angular-mocks.js',
            'web/dist/js/app.min.js',
            {pattern: 'source_js/source/tests/**/*.ts'}
        ],
        preprocessors: {
            "**/*.ts": ["karma-typescript"]
        },
        singleRun: true,
        port: 9876,
        colors: true,
        reporters: ["nyan", "karma-typescript"],
        browsers: ["PhantomJS"],
        // possible values: config.LOG_DISABLE || config.LOG_ERROR || config.LOG_WARN || config.LOG_INFO || config.LOG_DEBUG
        logLevel: config.LOG_INFO,
        autoWatch: false,
        concurrency: Infinity
    });
};