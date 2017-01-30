module APP.Dashboard {
    import IHttpService = angular.IHttpService;
    import IRoutingService = APP.Common.IRoutingService;
    import IPromise = angular.IPromise;

    export class BuildService {
        private _checklists: {id: number, name: string}[] = [];
        private _branches: string[] = [];
        private _databases: {id: number, name: string, comment: string}[] = [];

        public constructor(private $http: IHttpService,
                           private routing: IRoutingService) {

        }

        public getBuildInfo(projectId: number): IPromise<any> {
            return this.$http.get(this.routing.generate('get_build_options', {projectId: projectId})).then((response:any) => {
                this._checklists = response.data.checklists;
                this._branches = response.data.branches;
                this._databases = response.data.databases;
            });
        }

        get databases(): {id: number; name: string}[] {
            return this._databases;
        }

        get checklists(): {id: number; name: string}[] {
            return this._checklists;
        }

        get branches(): string[] {
            return this._branches;
        }
    }
}

angular.module('dashboard')
    .service('BuildService', ['$http', 'Routing', function ($http, routing) {
        return new APP.Dashboard.BuildService($http, routing);
    }]);