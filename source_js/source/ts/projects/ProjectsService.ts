module APP.Projects {
    import IHttpService = angular.IHttpService;
    import IRoutingService = APP.Common.IRoutingService;

    export class ProjectsService {
        private _projects: {id: number, name: string}[];

        public constructor(private $http:IHttpService, private routing:IRoutingService) {

        }

        public loadProjectList() {
            return this.$http.get(this.routing.generate('get_projects_list')).then((data:any) => {
                this._projects = data.data;
            });
        }

        get projects(): {id: number; name: string}[] {
            return this._projects;
        }
    }
}

angular.module('projects')
    .service('ProjectsService', ['$http', 'Routing', function ($http, routing) {
        return new APP.Projects.ProjectsService($http, routing);
    }]);