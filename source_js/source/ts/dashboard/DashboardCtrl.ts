module APP.Dashboard {
    import ProjectsService = APP.Projects.ProjectsService;

    export class DashboardCtrl {
        public constructor(public projectsService:ProjectsService) {
        }
    }
}

angular.module('dashboard')
    .controller('DashboardCtrl', ['ProjectsService', function (projectsService) {
        return new APP.Dashboard.DashboardCtrl(projectsService);
    }]);