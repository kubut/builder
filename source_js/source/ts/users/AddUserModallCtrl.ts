module APP.Users {
    import UserRole = APP.Common.UserRole;
    import IDialogService = angular.material.IDialogService;

    export class AddUserModalCtrl {
        private _user:IUser;

        public constructor(private $mdDialog:IDialogService) {
            this._user = {
                id: 0,
                name: '',
                surname: '',
                email: '',
                role: UserRole.User
            };
        }

        public close() {
            this.$mdDialog.cancel();
        }

        public addUser() {
            this.user.role = +this.user.role;
            this.$mdDialog.hide(this.user);
        }

        get user(): APP.Users.IUser {
            return this._user;
        }

        set user(value: APP.Users.IUser) {
            this._user = value;
        }
    }
}

angular.module('users')
.controller('AddUserModalCtrl', ['$mdDialog', function($mdDialog) {
    return new APP.Users.AddUserModalCtrl($mdDialog);
}]);