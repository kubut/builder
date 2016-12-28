module APP.Dashboard {
    export interface IBuildConfiguration {
        name: string;
        branch: string;
        databaseId: number;
        instanceId?: number;
        checklistId?: number;
        jiraTaskSymbol?: string;
    }
}