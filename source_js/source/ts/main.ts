/// <reference path="configuration/routes.ts"/>
/// <reference path="configuration/mdConfig.ts"/>
import IHttpProvider = angular.IHttpProvider;
(() => {
    let app;

    angular.module('users', ['ngMessages']);
    angular.module('common', []);
    angular.module('configuration', []);

    app = angular.module('app', [
        'ui.router',
        'templates',
        'ngMessages',
        'ngMaterial',
        'users',
        'common',
        'configuration'
    ]);

    app.config(['$stateProvider', '$urlRouterProvider', APP.Configuration.Routes.configure]);
    app.config(['$mdThemingProvider', APP.Configuration.MdConfig.configure]);
    app.config(['$httpProvider', ($httpProvider:IHttpProvider) => {
        $httpProvider.interceptors.push('httpInterceptor');
    }]);
})();