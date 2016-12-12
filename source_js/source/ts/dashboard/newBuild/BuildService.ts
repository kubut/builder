module APP.Dashboard {
    import IHttpService = angular.IHttpService;
    import IRoutingService = APP.Common.IRoutingService;
    import IPromise = angular.IPromise;

    export class BuildService {
        private _checklists: {id: number, name: string}[] = [];
        private _branches: string[] = [];
        private _jiraTasks: {symbol: string, name: string}[] = [];

        public constructor(private $http: IHttpService,
                           private routing: IRoutingService) {

        }

        public getBuildInfo(projectId: number): IPromise<any> {
            return this.$http.get(this.routing.generate('get_build_options', {projectId: projectId})).then((response) => {
            // return this.$http.get('http://builder-dev.vagrant:3000/buildoptions').then((response:any) => {
                this._checklists = response.data.checklists;
                this._branches = response.data.branches;
                this._jiraTasks = response.data.jiraTasks;
            });
        }

        get checklists(): {id: number; name: string}[] {
            return this._checklists;
        }

        get branches(): string[] {
            return this._branches;
        }

        get jiraTasks(): {symbol: string; name: string}[] {
            return this._jiraTasks;
        }
    }
}

angular.module('dashboard')
    .service('BuildService', ['$http', 'Routing', function ($http, routing) {
        return new APP.Dashboard.BuildService($http, routing);
    }]);