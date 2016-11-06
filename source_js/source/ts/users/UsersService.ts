module APP.Users {
    import IRoutingService = APP.Common.IRoutingService;
    import IHttpService = angular.IHttpService;

    export class UsersService {
        private _busy: boolean;
        private _users: IUser[];

        public constructor(private routing: IRoutingService, private $http: IHttpService) {
            this._busy = true;
            this.reloadUserList();
        }

        public addUser(user: IUser) {
            this._busy = true;

            this.$http.post(this.routing.generate('user_add'), {
                name: user.name,
                surname: user.surname,
                email: user.email,
                role: user.role
            }).finally(() => {
                this.reloadUserList();
            });
        }

        public deleteUser(id: number) {
            this._busy = true;

            this.$http.delete(this.routing.generate('delete_user', {id: id})).finally(() => {
                this.reloadUserList();
            });
        }

        public reloadUserList() {
            this._users = [];
            this._busy = true;

            this.$http.get(this.routing.generate('get_users')).then((data: any) => {
                _.forEach(data.data, (userData) => {
                    let user: IUser = {
                        id: userData.id,
                        name: userData.name,
                        surname: userData.surname,
                        email: userData.email,
                        role: userData.role,
                        isActive: userData.isActive
                    };

                    if (!userData.isActive) {
                        user.activationCode = userData.activationCode;
                    }

                    this._users.push(user);
                });
            }).finally(() => {
                this._busy = false;
            });
        }

        get busy(): boolean {
            return this._busy;
        }

        get users(): APP.Users.IUser[] {
            return this._users;
        }
    }
}

angular.module('users')
    .service('UsersService', ['Routing', '$http', function (routing, $http) {
        return new APP.Users.UsersService(routing, $http);
    }]);