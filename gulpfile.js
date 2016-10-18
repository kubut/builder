var fs = require('fs'),
    del = require('del'),
    gulp = require('gulp'),
    karma = require('karma'),
    merge = require('merge2'),
    runSequence = require('run-sequence'),
    plugins = require('gulp-load-plugins')();

require('gulp-stats')(gulp);

plugins.fs = fs;
plugins.del = del;
plugins.karma = karma;
plugins.merge = merge;
plugins.runSequence = runSequence;
plugins.path = require('path');

var packageJson = JSON.parse(fs.readFileSync('./package.json')),
    appTasks = requireTasks('app');

requireAliases('app', appTasks);

function requireTasks(name) {
    return require('./gulp-tasks/' + name)(gulp, plugins, packageJson.options);
}

function requireAliases(name, tasks) {
    require('./gulp-tasks/' + name + 'Aliases')(gulp, plugins, packageJson.options, tasks);
}