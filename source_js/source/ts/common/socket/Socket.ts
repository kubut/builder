module APP.Common.Socket {
    export type EventType = 'connect' | 'message' | 'error';

    export class Socket {
        private readonly CLOSE_NORMAL_CODE = 1000;
        private readonly TIME_TO_REPEAT_CONNECTION = 5000;
        private socketStorage: SocketStorage;
        private pending: ISocketRequest[];
        private headers: Object;
        private ws: WebSocket;
        private closingState = false;
        private onEventListeners: {
            connect: Array <Function> [],
            message: Array <Function> [],
            error: Array <Function> []
        };

        public constructor() {
            this.socketStorage = new SocketStorage();
            this.onEventListeners = {
                connect: [],
                message: [],
                error: []
            };
            this.pending = [];
            this.headers = {};
        }

        public setHeaders(headers: Object): void {
            this.headers = headers;
        }

        public connect(url: string): void {
            this.closingState = false;

            this.ws = new WebSocket(url);
            this.ws.onopen = this.onConnect.bind(this);
            this.ws.onmessage = this.on.bind(this);
            this.ws.onclose = this.reconnect.bind(this);
        }

        public emit(action: string, params: Object) {
            let request = angular.copy(_.merge(this.headers, {
                requestId: this.socketStorage.getAvailableId(),
                action: action,
                params: params
            }));

            if (this.ws.readyState === WebSocket.OPEN) {
                this.socketStorage.add(request);
                this.ws.send(JSON.stringify(request));
            } else {
                this.pending.push(request);
            }
        }

        public reemit(requestId: number): void {
            let request = this.socketStorage.getRequestById(requestId);

            if (!_.isUndefined(request)) {
                this.emit(request.action, request.params);
                this.removeFromStorage(requestId);
            }
        }

        public addEventListener(event: EventType, listener: Function): void {
            this.onEventListeners[event] = _.union(this.onEventListeners[event], [listener]);
        }

        public removeEventListener(event: EventType, listener: Function): void {
            _.remove(this.onEventListeners[event], listener);
        }

        public removeFromStorage(requestId: number): void {
            this.socketStorage.remove(requestId);
        }

        public close(): void {
            this.closingState = true;
            this.ws.close(this.CLOSE_NORMAL_CODE);
        }

        private onConnect(): void {
            this.emitPending();
            _.forEach(this.onEventListeners['connect'], (listenerCallback: Function) => {
                listenerCallback();
            });
        }

        private reconnect(event: CloseEvent): void {
            if (event.code !== this.CLOSE_NORMAL_CODE) {
                window.setTimeout(() => {
                    if (!this.closingState) {
                        this.connect.call(this, event.currentTarget['url']);
                    }
                }, this.TIME_TO_REPEAT_CONNECTION);
            }
        }

        private on(event: MessageEvent): void {
            let message = JSON.parse(event.data);

            if (message.action === 'error') {
                _.forEach(this.onEventListeners['error'], (listenerCallback: Function) => {
                    listenerCallback(message.params);
                });
            } else {
                _.forEach(this.onEventListeners['message'], (listenerCallback: Function) => {
                    listenerCallback(message.action, message.params);
                    this.socketStorage.remove(message.params.requestId);
                });
            }
        }

        private emitPending(): void {
            let toEmit = this.pending;
            this.pending = [];

            _.forEach(toEmit, (request: ISocketRequest) => {
                this.emit(request.action, request.params);
            });
        }
    }
}