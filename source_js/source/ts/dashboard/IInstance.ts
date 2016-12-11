module APP.Dashboard {
    export interface IInstance {
        id: number;
        name: string;
        status: InstanceStatus;
        buildDate: string;
        author: string;
        url: string;
    }
}