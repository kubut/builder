module APP.Dashboard {
    export interface IInstance {
        id: number;
        name: string;
        status: InstanceStatus;
        jiraInformation?: IJiraInformation;
        checklist?: IDashboardChecklist;
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