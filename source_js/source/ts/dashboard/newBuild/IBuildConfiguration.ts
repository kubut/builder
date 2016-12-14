module APP.Dashboard {
    export interface IBuildConfiguration {
        name: string;
        branch: string;
        checklistId?: number;
        jiraTaskSymbol?: string;
    }
}