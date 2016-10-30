module APP.Users {
    import UserRole = APP.Common.UserRole;

    export interface IUser {
        id: number;
        name: string;
        surname: string;
        role: UserRole;
        activationCode?: string;
    }
}