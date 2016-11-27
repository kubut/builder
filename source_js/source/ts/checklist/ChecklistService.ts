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

        public loadListOfChecklists(projectId, page = 1): IPromise<any> {
            let offset = (page - 1) * this._limit;

            return this.$http.get(this.routing.generate('checklist', {
                offset: offset,
                limit: this.limit,
                projectId: projectId
            })).then((response: any) => {
                this._total = response.headers('X-Total-Count');
                this._list = [];

                _.forEach(response.data, (checklist: any) => {
                    this._list.push(new ChecklistModel(+checklist.id, checklist.name, checklist.items));
                });
            });
        }

        public createChecklist(name): IPromise<any> {
            return this.$http.post(this.routing.generate('checklist'), {name: name});
        }

        public deleteChecklist(id: number, projectId: number): IPromise<any> {
            return this.$http.delete(this.routing.generate('checklist', {id: id})).then(() => {
                this.loadListOfChecklists(projectId);
            });
        }

        public saveChecklist(checklist: ChecklistModel): IPromise<any> {
            return this.$http.put(this.routing.generate('checklist', checklist.id), {
                name: checklist.name,
                items: checklist.items
            });
        }

        public getChecklist(id: number): ChecklistModel {
            return _.find(this.list, {id: id});
        }

        get list(): ChecklistModel[] {
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