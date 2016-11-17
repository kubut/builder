module APP.Configuration {
    import IUrlRouterProvider = angular.ui.IUrlRouterProvider;
    import IStateService = angular.ui.IStateService;
    import ITimeoutService = angular.ITimeoutService;
    import IQService = angular.IQService;
    import ProjectsService = APP.Projects.ProjectsService;
    import IStateParamsService = angular.ui.IStateParamsService;

    export class Routes {
        public static $inject = ['$stateProvider', '$urlRouterProvider'];

        public static configure(stateProvider: any, urlRouterProvider: IUrlRouterProvider) {
            let defaultState = 'app.dashboard';

            urlRouterProvider.otherwise('/');

            stateProvider
                .state('app', {
                    abstract: true,
                    templateUrl: '/templates/main.html',
                    resolve: {
                        projects: ['ProjectsService', (projectService: ProjectsService) => {
                            return projectService.loadProjectList();
                        }]
                    }
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
                })
                .state('app.admin.project', {
                    abstract: true,
                    resolve: {
                        sqlFiles: ['ProjectsService', (projectsService: ProjectsService) => {
                            return projectsService.loadSqlFiles();
                        }]
                    },
                    url: '/project',
                    template: '<ui-view>'
                })
                .state('app.admin.project.create', {
                    controller: 'NewProjectCtrl as projectCtrl',
                    url: '/create',
                    templateUrl: '/templates/newProject.html'
                })
                .state('app.admin.project.edit', {
                    controller: 'EditProjectCtrl as projectCtrl',
                    resolve: {
                        project: ['ProjectsService', '$stateParams',
                            (projectsService: ProjectsService, $stateParams: IStateParamsService) => {
                                return projectsService.loadProjectSettings($stateParams['id']);
                            }]
                    },
                    url: '/edit/:id',
                    templateUrl: '/templates/editProject.html'
                });
        }
    }
}