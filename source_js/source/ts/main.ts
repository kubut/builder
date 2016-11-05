/// <reference path="routes.ts"/>
/// <reference path="configuration/mdConfig.ts"/>
import IHttpProvider = angular.IHttpProvider;
(() => {
    let app;

    angular.module('users', ['ngMessages']);

    app = angular.module('app', [
        'ui.router',
        'templates',
        'ngMessages',
        'ngMaterial',
        'users'
    ]);

    app.config(['$stateProvider', '$urlRouterProvider', APP.Routes.configure]);
    app.config(['$mdThemingProvider', APP.Configuration.MdConfig.configure]);
    app.config(['$httpProvider', ($httpProvider:IHttpProvider) => {
        $httpProvider.interceptors.push('httpInterceptor');
    }]);
})();