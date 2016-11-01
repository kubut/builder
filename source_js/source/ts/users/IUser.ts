module APP.Users {
    import UserRole = APP.Common.UserRole;

    export interface IUser {
        id: number;
        name: string;
        surname: string;
        email: string;
        role: UserRole;
        activationCode?: string;
    }
}