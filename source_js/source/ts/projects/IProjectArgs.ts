module APP.Projects {
    export interface IProjectArgs {
        id: number;
        name: string;
        installScript: string;
        sqlFile: string;
        sqlUser: string;
        configScript: string;
        domain: string;
        gitPath: string;
        gitLogin: string;
        gitPass: string;
    }
}