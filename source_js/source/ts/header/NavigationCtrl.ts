module APP.Header {
    import IUserConfigurationService = APP.Configuration.IUserConfigurationService;
    import ProjectsService = APP.Projects.ProjectsService;
    import IStateService = angular.ui.IStateService;

    export class NavigationCtrl {
        public constructor(public userConfiguration: IUserConfigurationService,
                           public projectsService: ProjectsService,
                           private $state: IStateService) {
        }

        public newProject(): void {
            this.$state.go('app.admin.project.create');
        }

        public editProject(id:number): void {
            this.$state.go('app.admin.project.edit', {id: id});
        }
    }
}

angular.module('users')
    .controller('NavigationCtrl', ['UserConfiguration', 'ProjectsService', '$state', function (UserConfiguration, projectsService, $state) {
        return new APP.Header.NavigationCtrl(UserConfiguration, projectsService, $state);
    }]);