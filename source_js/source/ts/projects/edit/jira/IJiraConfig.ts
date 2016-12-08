module APP.Projects {
    export interface IJiraConfig {
        url: string;
        projectName: string;
        consumerKey: string;
        consumerName: string;
        privateKeyFileName: string;
    }
}