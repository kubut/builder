var fs = require('fs'),
    del = require('del'),
    gulp = require('gulp'),
    merge = require('merge2'),
    karma = require('karma'),
    runSequence = require('run-sequence'),
    plugins = require('gulp-load-plugins')();

require('gulp-stats')(gulp);

plugins.fs = fs;
plugins.del = del;
plugins.merge = merge;
plugins.karma = karma;
plugins.runSequence = runSequence;
plugins.path = require('path');

var packageJson = JSON.parse(fs.readFileSync('./package.json')),
    appTasks = require('./gulp-tasks/app')(gulp, plugins, packageJson.options);

require('./gulp-tasks/appAliases')(gulp, plugins, packageJson.options, appTasks);