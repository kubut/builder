module APP.ChangePassword {
    describe('ChangePasswordService', () => {
        let service: ChangePasswordService,
            mdDialogMock,
            httpBackend,
            routingMock,
            mdToastMock;

        beforeEach(() => {
            angular.mock.module('security');

            mdDialogMock = {
                show: jasmine.createSpy('$mdDialog.show')
            };

            routingMock = {
                generate: jasmine.createSpy('routing.generate').and.returnValue('url/5')
            };

            mdToastMock = {
                showSimple: jasmine.createSpy('mdToast.showSimple')
            };

            angular.mock.module(($provide) => {
                $provide.value('$mdDialog', mdDialogMock);
                $provide.value('Routing', routingMock);
                $provide.value('$mdToast', mdToastMock);
            });

            angular.mock.inject(($mdDialog, Routing, $mdToast, $httpBackend, $http) => {
                httpBackend = $httpBackend;

                service = new ChangePasswordService($mdDialog, $http, Routing, $mdToast);
            });
        });

        afterEach(() => {
            httpBackend.verifyNoOutstandingExpectation();
            httpBackend.verifyNoOutstandingRequest();
        });

        describe('showModal', () => {
            it('should show modal', () => {
                service.showModal();

                expect(mdDialogMock.show).toHaveBeenCalledWith({
                    templateUrl: '/templates/changePassword.modal.html',
                    controller: 'ChangePasswordModalCtrl as modalCtrl',
                    clickOutsideToClose: false
                });
            });
        });

        describe('changePassword', () => {
            beforeEach(() => {
                httpBackend.expectPATCH('url/5', {password: 'secretPass'}).respond({});
            });

            it('should call proper API', () => {
                service.changePassword('secretPass', jasmine.createSpy(''));
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('change_password');
            });

            it('should call callback and show toast', () => {
                let spy = jasmine.createSpy('spy');

                service.changePassword('secretPass', spy);
                httpBackend.flush();

                expect(spy).toHaveBeenCalled();
                expect(mdToastMock.showSimple).toHaveBeenCalledWith('Pomyślnie ustawiono nowe hasło');
            });
        });
    });
}