module APP.Checklist {
    import IPromise = angular.IPromise;
    import IHttpService = angular.IHttpService;
    import IRoutingService = APP.Common.IRoutingService;

    export class ChecklistService {
        private _list: ChecklistModel[];
        private _limit = 10;
        private _total: number;

        public constructor(private $http: IHttpService, private routing: IRoutingService) {

        }

        public loadListOfChecklists(page = 1): IPromise<any> {
            let offset = (page - 1) * this._limit;
            return this.$http.get(this.routing.generate('checklist'), {offset: offset, limit: this.limit}).then((response:any) => {
                this._total = response.headers('X-Total-Count');
                this._list = [];

                _.forEach(response.data, (checklist: any) => {
                    this._list.push(new ChecklistModel(checklist.id, checklist.name, checklist.items));
                });
            });
        }

        get list(): {id: number; name: string}[] {
            return this._list;
        }

        get limit(): number {
            return this._limit;
        }

        get total(): number {
            return this._total;
        }
    }
}

angular.module('checklist')
    .service('ChecklistService', ['$http', 'Routing', function ($http, Routing) {
        return new APP.Checklist.ChecklistService($http, Routing);
    }]);