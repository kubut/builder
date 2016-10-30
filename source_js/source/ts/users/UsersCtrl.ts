module APP.Users {
    import IDialogService = angular.material.IDialogService;

    export class UsersCtrl {
        public constructor(private $mdDialog:IDialogService, public usersService:UsersService) {

        }

        public addUser(ev:MouseEvent) {
            this.$mdDialog.show({
                templateUrl: '/templates/addUser.modal.html',
                controller: 'AddUserModalCtrl as modalCtrl',
                clickOutsideToClose: true,
                targetEvent: ev
            }).then((user:IUser) => {
                this.usersService.addUser(user);
            });
        }
    }
}

angular.module('users')
    .controller('UsersCtrl', ['$mdDialog', 'UsersService', function ($mdDialog, usersService) {
        return new APP.Users.UsersCtrl($mdDialog, usersService);
    }]);