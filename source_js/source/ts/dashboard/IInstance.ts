module APP.Dashboard {
    export interface IInstance {
        id: number;
        name: string;
        status: InstanceStatus;
        branchName: string;
        buildDate: string;
        author: string;
        url: string;
    }
}