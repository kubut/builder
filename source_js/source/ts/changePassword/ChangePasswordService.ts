module APP.ChangePassword {
    import IDialogService = angular.material.IDialogService;
    import IHttpService = angular.IHttpService;
    import IRoutingService = APP.Common.IRoutingService;
    import IToastService = angular.material.IToastService;
    import IPromise = angular.IPromise;

    export class ChangePasswordService {
        public constructor(private $mdDialog: IDialogService,
                           private $http: IHttpService,
                           private routing: IRoutingService,
                           private $mdToast: IToastService) {

        }

        public showModal(): IPromise<any> {
            return this.$mdDialog.show({
                templateUrl: '/templates/changePassword.modal.html',
                controller: 'ChangePasswordModalCtrl as modalCtrl',
                clickOutsideToClose: false
            });
        }

        public changePassword(password: string, callback :Function): void {
            this.$http.patch(this.routing.generate('change_password'), {password: password}).then(() => {
                this.$mdToast.showSimple('Pomyślnie ustawiono nowe hasło');
                callback();
            });
        }
    }
}

angular.module('security')
    .service('ChangePasswordService', ['$mdDialog', '$http', 'Routing', '$mdToast', ($mdDialog, $http, routing, $mdToast) => {
        return new APP.ChangePassword.ChangePasswordService($mdDialog, $http, routing, $mdToast);
    }]);