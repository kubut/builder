module APP {
    import IUrlRouterProvider = angular.ui.IUrlRouterProvider;

    export class Routes {
        public static $inject = ['$stateProvider', '$urlRouterProvider'];

        public static configure(stateProvider:any, urlRouterProvider:IUrlRouterProvider) {
            urlRouterProvider.otherwise('/users');

            stateProvider
                .state('app', {
                    abstract: true,
                    templateUrl: '/templates/main.html'
                })
                .state('app.users', {
                    controller: 'UsersCtrl as usersCtrl',
                    url: '/users',
                    templateUrl: '/templates/users.html'
                });
        }
    }
}