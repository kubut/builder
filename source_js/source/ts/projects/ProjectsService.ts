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

        public loadProjectSettings(id: number): IPromise<ProjectModel> {
            return this.$http.get(this.routing.generate('project_details', {id: id})).then((respond: any) => {
                return new ProjectModel({
                    id: respond.data.id,
                    name: respond.data.name,
                    installScript: respond.data.installScript,
                    sqlFile: respond.data.sqlFile,
                    sqlUser: respond.data.sqlUser,
                    configScript: respond.data.configScript,
                    domain: respond.data.domain,
                    gitPath: respond.data.gitPath,
                    gitLogin: respond.data.gitLogin,
                    gitPass: respond.data.gitPass
                });
            });
        }

        public saveProject(project: ProjectModel): IPromise<any> {
            let path: string;

            if (project.id >= 0) {
                path = this.routing.generate('edit_project', {id: project.id});
            } else {
                path = this.routing.generate('projects');
            }

            return this.$http.put(path, {
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