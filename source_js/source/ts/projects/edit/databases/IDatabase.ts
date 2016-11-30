module APP.Projects {
    export interface IDatabase {
        id: number;
        name: string;
        comment: string;
        status: DatabaseStatus;
    }
}