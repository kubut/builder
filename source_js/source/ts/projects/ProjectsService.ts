module APP.Projects {
    import IHttpService = angular.IHttpService;
    import IRoutingService = APP.Common.IRoutingService;
    import IPromise = angular.IPromise;

    export class ProjectsService {
        private _projects: {id: number, name: string}[];
        private _sqlFiles: string[];

        public constructor(private $http: IHttpService, private routing: IRoutingService) {

        }

        public loadSqlFiles():IPromise<any> {
            return this.$http.get(this.routing.generate('get_databases_files')).then((data: any) => {
                this._sqlFiles = data.data;
            });
        }

        public loadProjectList():IPromise<any> {
            return this.$http.get(this.routing.generate('projects')).then((data: any) => {
                this._projects = data.data;
            });
        }

        public saveProject(project: ProjectModel):IPromise<any> {
            return this.$http.post(this.routing.generate('projects'), {
                name: project.name,
                installScript: project.installScript,
                sqlFile: project.sqlFile,
                sqlUser: project.sqlUser,
                configScript: project.configScript,
                domain: project.domain,
                gitPath: project.gitPath,
                gitLogin: project.gitLogin,
                gitPass: project.gitPass
            });
        }

        get projects(): {id: number; name: string}[] {
            return this._projects;
        }

        get sqlFiles(): string[] {
            return this._sqlFiles;
        }
    }
}

angular.module('projects')
    .service('ProjectsService', ['$http', 'Routing', function ($http, routing) {
        return new APP.Projects.ProjectsService($http, routing);
    }]);