module APP.Header {
    import IUserConfigurationService = APP.Configuration.IUserConfigurationService;
    import ProjectsService = APP.Projects.ProjectsService;
    import IStateService = angular.ui.IStateService;
    import IStateParamsService = angular.ui.IStateParamsService;

    export class NavigationCtrl {
        public constructor(public userConfiguration: IUserConfigurationService,
                           public projectsService: ProjectsService,
                           private $state: IStateService,
                           private $stateParams: IStateParamsService) {
        }

        public newProject(): void {
            this.$state.go('app.admin.project.create');
        }

        public editProject(id: number): void {
            this.$state.go('app.admin.project.edit', {projectId: id});
        }

        public isActiveState(projectId: number): boolean {
            return this.$stateParams['projectId'] === projectId.toString();
        }
    }
}

angular.module('users')
    .controller('NavigationCtrl', ['UserConfiguration', 'ProjectsService', '$state', '$stateParams',
        function (UserConfiguration, projectsService, $state, $stateParams) {
            return new APP.Header.NavigationCtrl(UserConfiguration, projectsService, $state, $stateParams);
        }]);