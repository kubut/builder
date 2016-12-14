module APP.Dashboard {
    import ProjectsService = APP.Projects.ProjectsService;
    import IScope = angular.IScope;
    import ITimeoutService = angular.ITimeoutService;
    import IRootScopeService = angular.IRootScopeService;
    import IDialogService = angular.material.IDialogService;

    export class DashboardCtrl {
        private _instances: IInstance[] = [];

        public constructor($scope: IScope,
                           public projectId: number,
                           public projectsService: ProjectsService,
                           public dashboardService: DashboardService,
                           private $rootScope: IRootScopeService,
                           private $timeout: ITimeoutService,
                           private $mdDialog: IDialogService) {
            this.dashboardService.connect();
            this.dashboardService.sendSynchronizationRequest(projectId);

            $rootScope.$on('Instances:changes', () => {
                $timeout(this.loadInstances.bind(this));
            });

            $scope.$on('$destroy', () => {
                this.dashboardService.close();
            });
        }

        public newBuildModal(ev: MouseEvent): void {
            this.$mdDialog.show({
                templateUrl: '/templates/newBuild.modal.html',
                controller: 'NewBuildModalCtrl as modalCtrl',
                clickOutsideToClose: true,
                locals: {
                    projectId: this.projectId
                },
                targetEvent: ev
            }).then((buildConfiguration: IBuildConfiguration) => {
                this.dashboardService.sendCreateRequest(this.projectId, buildConfiguration);
            });
        }

        public loadInstances(): void {
            this._instances = this.dashboardService.getInstancesForProjectId(this.projectId);
        }

        get instances(): APP.Dashboard.IInstance[] {
            return this._instances;
        }
    }
}

angular.module('dashboard')
    .controller('DashboardCtrl', ['$scope', 'projectId', 'ProjectsService', 'DashboardService', '$rootScope', '$timeout', '$mdDialog',
        function ($scope, projectId, projectsService, dashboardService, $rootScope, $timeout, $mdDialog) {
            return new APP.Dashboard.DashboardCtrl($scope, projectId, projectsService, dashboardService, $rootScope, $timeout, $mdDialog);
        }]);