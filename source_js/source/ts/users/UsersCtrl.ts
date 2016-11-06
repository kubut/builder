module APP.Users {
    import IDialogService = angular.material.IDialogService;

    export class UsersCtrl {
        public constructor(private $mdDialog: IDialogService, public usersService: UsersService) {

        }

        public addUser(ev: MouseEvent) {
            this.$mdDialog.show({
                templateUrl: '/templates/addUser.modal.html',
                controller: 'AddUserModalCtrl as modalCtrl',
                clickOutsideToClose: true,
                targetEvent: ev
            }).then((user: IUser) => {
                this.usersService.addUser(user);
            });
        }

        public deleteUser(ev: MouseEvent, user: IUser) {
            let confirm = this.$mdDialog.confirm()
                .title('Przemyśl to')
                .textContent('Czy jesteś pewien, że chcesz usunąć uzytkownika ' + user.name + ' ' + user.surname + '?')
                .targetEvent(ev)
                .ok('Tak, jestem pewien')
                .cancel('Jeszcze to przemyślę');

            this.$mdDialog.show(confirm).then(() => {
                this.usersService.deleteUser(user.id);
            });
        }

        public resetPassword(ev: MouseEvent, user: IUser) {
            let confirm = this.$mdDialog.confirm()
                .title('Przemyśl to')
                .textContent(
                    'Czy jesteś pewien, że chcesz zresetować hasło ' + user.name + ' ' + user.surname + '? ' +
                    'Dla użytkownika zostanie wygenerowany nowy kod aktywacyjny.'
                )
                .targetEvent(ev)
                .ok('Tak, jestem pewien')
                .cancel('Jeszcze to przemyślę');

            this.$mdDialog.show(confirm).then(() => {
                this.usersService.resetPassword(user.id);
            });
        }
    }
}

angular.module('users')
    .controller('UsersCtrl', ['$mdDialog', 'UsersService', function ($mdDialog, usersService) {
        return new APP.Users.UsersCtrl($mdDialog, usersService);
    }]);