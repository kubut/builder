module APP.Configuration {
    import IUrlRouterProvider = angular.ui.IUrlRouterProvider;
    import IStateService = angular.ui.IStateService;
    import ITimeoutService = angular.ITimeoutService;
    import IQService = angular.IQService;

    export class Routes {
        public static $inject = ['$stateProvider', '$urlRouterProvider'];

        public static configure(stateProvider: any, urlRouterProvider: IUrlRouterProvider) {
            let defaultState = 'app.dashboard';

            urlRouterProvider.otherwise('/');

            stateProvider
                .state('app', {
                    abstract: true,
                    templateUrl: '/templates/main.html'
                })
                .state('app.dashboard', {
                    templateUrl: '/templates/dashboard.html',
                    url: '/'
                })
                .state('app.admin', {
                    abstract: true,
                    resolve: {
                        isGranted: ['Configuration', '$q', '$state', '$timeout',
                            (configuration: IConfigurationService, $q: IQService, $state: IStateService, $timeout: ITimeoutService) => {
                                let deferred = $q.defer();

                                $timeout(() => {
                                    if (!configuration.user.isAdmin()) {
                                        $state.go(defaultState);
                                        deferred.reject();
                                    } else {
                                        deferred.resolve();
                                    }
                                });

                                return deferred.promise;
                            }]
                    },
                    template: '<ui-view>'
                })
                .state('app.admin.users', {
                    controller: 'UsersCtrl as usersCtrl',
                    url: '/users',
                    templateUrl: '/templates/users.html'
                });
        }
    }
}