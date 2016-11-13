module APP.Header {
    import IConfigurationService = APP.Configuration.IConfigurationService;
    import ProjectsService = APP.Projects.ProjectsService;

    export class NavigationCtrl {
        public constructor(public configuration:IConfigurationService,
                           public projectsService:ProjectsService) {
        }
    }
}

angular.module('users')
    .controller('NavigationCtrl', ['Configuration', 'ProjectsService', function (Configuration, projectsService) {
        return new APP.Header.NavigationCtrl(Configuration, projectsService);
    }]);