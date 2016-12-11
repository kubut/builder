module APP.Dashboard {
    export interface IInstance {
        id: number;
        name: string;
        status: InstanceStatus;
        jiraInformation?: IJiraInformation;
        branchName: string;
        buildDate: string;
        author: string;
        url: string;
    }

    export interface IJiraInformation {
        title: string;
        description: string;
        reporter: string;
        url: string;
    }
}