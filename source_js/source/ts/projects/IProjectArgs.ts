module APP.Projects {
    export interface IProjectArgs {
        id: number;
        name: string;
        installScript: string;
        sqlFile: string;
        sqlUser: string;
        configScript: string;
        gitPath: string;
        gitLogin: string;
        gitPass: string;
    }
}