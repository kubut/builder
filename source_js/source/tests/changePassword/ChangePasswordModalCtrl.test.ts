module APP.ChangePassword {
    describe('ChangePasswordModalCtrl', () => {
        let ctrl:ChangePasswordModalCtrl,
            $mdDialogMock,
            changePasswordServiceMock;

        beforeEach(() => {
            angular.mock.module('security');

            $mdDialogMock = {
                hide: jasmine.createSpy('mdDialog.hide')
            };

            changePasswordServiceMock = {
                changePassword: jasmine.createSpy('changePasswordService.changePassword')
            };

            angular.mock.module(($provide) => {
                $provide.value('$mdDialog', $mdDialogMock);
                $provide.value('ChangePasswordService', changePasswordServiceMock);
            });

            angular.mock.inject(($mdDialog, ChangePasswordService) => {
                ctrl = new ChangePasswordModalCtrl($mdDialog, ChangePasswordService);
            });
        });

        describe('changePassword', () => {
            it('should call proper method from service', () => {
                ctrl.password = 'secretPass';
                ctrl.changePassword();

                expect(changePasswordServiceMock.changePassword).toHaveBeenCalledWith('secretPass', $mdDialogMock.hide);
            });
        });
    });
}