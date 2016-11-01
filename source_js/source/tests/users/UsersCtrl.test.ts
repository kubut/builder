module APP.Users {
    describe('UsersCtrl', () => {
        let ctrl:UsersCtrl,
            rootScope,
            mdDialogMock,
            userServiceMock,
            q;

        beforeEach(() => {
            angular.mock.module('users');

            mdDialogMock = {
                show: jasmine.createSpy('mdDialog.show')
            };

            userServiceMock = {
                addUser: jasmine.createSpy('userService.addUser')
            };

            angular.mock.module(($provide) => {
                $provide.value('$mdDialog', mdDialogMock);
                $provide.value('UsersService', userServiceMock);
            });

            angular.mock.inject(($rootScope, $q, $mdDialog, UsersService) => {
                rootScope = $rootScope;
                q = $q;

                ctrl = new UsersCtrl($mdDialog, UsersService);
            });
        });

        describe('addUser', () => {
            it('Should show modal', () => {
                let defer = q.defer(),
                    event = document.createEvent('MouseEvents');

                mdDialogMock.show.and.returnValue(defer.promise);

                ctrl.addUser(event);

                expect(mdDialogMock.show).toHaveBeenCalledWith({
                    templateUrl: '/templates/addUser.modal.html',
                    controller: 'AddUserModalCtrl as modalCtrl',
                    clickOutsideToClose: true,
                    targetEvent: event
                });
            });

            it('Should call addUser in service when modal is closed', () => {
                let defer = q.defer();

                mdDialogMock.show.and.returnValue(defer.promise);

                defer.resolve('test');

                ctrl.addUser(document.createEvent('MouseEvents'));

                rootScope.$apply();

                expect(userServiceMock.addUser).toHaveBeenCalledWith('test');
            });
        });
    });
}