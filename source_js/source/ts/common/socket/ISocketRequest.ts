module APP.Common.Socket {
    export interface ISocketRequest {
        requestId: number;
        action: string;
        params: Object;
    }
}