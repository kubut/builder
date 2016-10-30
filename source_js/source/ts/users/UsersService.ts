module APP.Users {
    import IRoutingService = APP.Common.IRoutingService;
    import IHttpService = angular.IHttpService;

    export class UsersService {
        private _busy:boolean;

        public constructor(private routing: IRoutingService, private $http:IHttpService) {
            this._busy = true;
            this.reloadUserList();
        }

        public addUser(user: IUser) {
            this._busy = true;

            this.$http.post(this.routing.generate('user_add'), {
                name: user.name,
                surname: user.surname,
                role: user.role
            }).finally(() => {
                this.reloadUserList();
            });
        }

        public reloadUserList() {
            this._busy = false;
        }

        get busy(): boolean {
            return this._busy;
        }
    }
}

angular.module('users')
    .service('UsersService', ['Routing', '$http', function (routing, $http) {
        return new APP.Users.UsersService(routing, $http);
    }]);