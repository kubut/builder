module APP.Common {
    export class UserModel {
        private role:UserRole;

        public constructor(role:UserRole) {
            this.role = role;
        }

        public isAdmin():boolean {
            return this.role === UserRole.Admin;
        }
    }
}