module APP.Header {
    import IConfigurationService = APP.Configuration.IConfigurationService;
    import ProjectsService = APP.Projects.ProjectsService;
    import IStateService = angular.ui.IStateService;

    export class NavigationCtrl {
        public constructor(public configuration: IConfigurationService,
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
    .controller('NavigationCtrl', ['Configuration', 'ProjectsService', '$state', function (Configuration, projectsService, $state) {
        return new APP.Header.NavigationCtrl(Configuration, projectsService, $state);
    }]);