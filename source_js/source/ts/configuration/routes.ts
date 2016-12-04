module APP.Configuration {
    import IUrlRouterProvider = angular.ui.IUrlRouterProvider;
    import IStateService = angular.ui.IStateService;
    import ITimeoutService = angular.ITimeoutService;
    import IQService = angular.IQService;
    import ProjectsService = APP.Projects.ProjectsService;
    import IStateParamsService = angular.ui.IStateParamsService;
    import ChecklistService = APP.Checklist.ChecklistService;

    export class Routes {
        public static $inject = ['$stateProvider', '$urlRouterProvider'];

        public static configure(stateProvider: any, urlRouterProvider: IUrlRouterProvider) {
            let defaultState = 'app.project.dashboard';

            urlRouterProvider.otherwise('/project//dashboard');
            urlRouterProvider.deferIntercept();

            stateProvider
                .state('app', {
                    abstract: true,
                    templateUrl: '/templates/main.html',
                    resolve: {
                        projects: ['ProjectsService', (projectsService: ProjectsService) => {
                            return projectsService.loadProjectList();
                        }]
                    }
                })
                .state('app.project', {
                    abstract: true,
                    resolve: {
                        project: ['projects', '$stateParams', 'ProjectsService', '$state',
                            (projects, $stateParams: IStateParamsService, projectsService: ProjectsService, $state: IStateService) => {
                                let id = $stateParams['id'] || undefined,
                                    isCorrectId = !_.isUndefined(id) && _.findIndex(projectsService.projects, {id: +id}) >= 0;

                                if (!isCorrectId && projectsService.projects.length > 0) {
                                    $state.go('app.project.dashboard', {id: projectsService.projects[0].id});
                                }

                                return true;
                            }],
                        checklists: ['ChecklistService', '$stateParams',
                            (checklistService: ChecklistService, $stateParams: IStateParamsService) => {
                                return checklistService.loadListOfChecklists(+$stateParams['id']);
                            }]
                    },
                    url: '/project/:id',
                    template: '<ui-view></ui-view>'
                })
                .state('app.project.dashboard', {
                    controller: 'DashboardCtrl as dashboardCtrl',
                    templateUrl: '/templates/dashboard.html',
                    url: '/dashboard'
                })
                .state('app.project.checklist', {
                    controller: 'ChecklistCtrl as checklistCtrl',
                    resolve: {
                        checklist: ['checklists', 'ChecklistService', '$stateParams', '$state',
                            (checklists, checklistService: ChecklistService, $stateParams: IStateParamsService, $state: IStateService) => {
                                let checklist = checklistService.getChecklist(+$stateParams['checklistId']);

                                if (_.isUndefined(checklist)) {
                                    $state.go('app.project.dashboard');
                                }

                                return checklist;
                            }]
                    },
                    templateUrl: '/templates/checklist.html',
                    url: '/checklist/:checklistId'
                })
                .state('app.admin', {
                    abstract: true,
                    resolve: {
                        isGranted: ['UserConfiguration', '$q', '$state', '$timeout',
                            (configuration: IUserConfigurationService, $q: IQService, $state: IStateService, $timeout: ITimeoutService) => {
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