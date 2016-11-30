module APP.Common.Socket {
    export class SocketStorage {
        private latestRequestId;
        private requests: ISocketRequest[];

        public constructor() {
            this.latestRequestId = 0;
            this.requests = [];
        }

        public getAvailableId(): number {
            return this.latestRequestId++;
        }

        public add(request: ISocketRequest): void {
            this.requests = _.union(this.requests, [request]);
        }

        public remove(requestId: number): void {
            _.remove(this.requests, {requestId: requestId});
        }

        public getRequestById(requestId: number): ISocketRequest {
            return _.find(this.requests, (request:ISocketRequest) => {
                return request.requestId === requestId;
            });
        }
    }
}