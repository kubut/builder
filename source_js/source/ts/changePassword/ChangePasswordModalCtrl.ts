module APP.ChangePassword {
    import IDialogService = angular.material.IDialogService;

    export class ChangePasswordModalCtrl {
        private _password: string;
        private _repeatedPassword: string;

        public constructor(private $mdDialog: IDialogService, private changePasswordService: ChangePasswordService) {

        }

        public changePassword(): void {
            this.changePasswordService.changePassword(this._password, this.$mdDialog.hide);
        }

        get password(): string {
            return this._password;
        }

        set password(value: string) {
            this._password = value;
        }

        get repeatedPassword(): string {
            return this._repeatedPassword;
        }

        set repeatedPassword(value: string) {
            this._repeatedPassword = value;
        }
    }
}

angular.module('security')
    .controller('ChangePasswordModalCtrl', ['$mdDialog', 'ChangePasswordService', ($mdDialog, changePasswordService) => {
        return new APP.ChangePassword.ChangePasswordModalCtrl($mdDialog, changePasswordService);
    }]);