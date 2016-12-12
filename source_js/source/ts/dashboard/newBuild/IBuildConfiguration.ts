module APP.Dashboard {
    export interface IBuildConfiguration {
        name: string;
        branch: string;
        instanceId?: number;
        checklist?: {id: number, name: string};
        jiraTask?: {symbol: string, name: string};
    }
}