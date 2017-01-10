module APP.Projects {
    export interface IJiraConfig {
        url: string;
        projectSymbol: string;
        login: string;
        password: string;
    }
}