module APP.Common {
    export interface IRoutingService {
        generate(route: string, data?:any): string;
    }

    export class RoutingProvider {
        private Routing;

        public setRouting(routing) {
            this.Routing = routing;
        }

        public $get(): IRoutingService {
            return this.Routing;
        }
    }
}

angular.module('app')
    .provider('Routing', [function () {
        return new APP.Common.RoutingProvider();
    }]);