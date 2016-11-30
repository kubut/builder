module APP.Common {
    export class UserModel {
        public constructor(private _id: number,
                           private _role: UserRole,
                           private _token: string) {
        }

        public isAdmin(): boolean {
            return this._role === UserRole.Admin;
        }

        get id(): number {
            return this._id;
        }

        get role(): APP.Common.UserRole {
            return this._role;
        }

        get token(): string {
            return this._token;
        }
    }
}