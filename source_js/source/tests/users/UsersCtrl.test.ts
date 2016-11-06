module APP.Users {
    import UserRole = APP.Common.UserRole;
    describe('UsersCtrl', () => {
        let ctrl: UsersCtrl,
            rootScope,
            mdDialogMock,
            userServiceMock,
            dialogMock,
            userMock,
            q;

        beforeEach(() => {
            angular.mock.module('users');

            dialogMock = {
                title: () => {
                    return dialogMock;
                },
                textContent: () => {
                    return dialogMock;
                },
                targetEvent: () => {
                    return dialogMock;
                },
                ok: () => {
                    return dialogMock;
                },
                cancel: () => {
                    return dialogMock;
                }
            };

            mdDialogMock = {
                show: jasmine.createSpy('mdDialog.show'),
                confirm: jasmine.createSpy('mdDialog.confirm').and.returnValue(dialogMock)
            };

            userServiceMock = {
                addUser: jasmine.createSpy('userService.addUser'),
                deleteUser: jasmine.createSpy('userService.deleteUser')
            };

            userMock = {
                id: 10,
                name: 'name',
                surname: 'surname',
                email: 'mail@ma.il',
                role: UserRole.Admin,
                isActive: false
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

        describe('deleteUser', () => {
            it('should show confirmation modal', () => {
                let defer = q.defer();
                mdDialogMock.show.and.returnValue(defer.promise);
                defer.reject();

                ctrl.deleteUser(document.createEvent('MouseEvents'), userMock);

                expect(mdDialogMock.confirm).toHaveBeenCalled();
                expect(mdDialogMock.show).toHaveBeenCalledWith(dialogMock);
            });

            it('should call deleteUser method in service when user confirm action', () => {
                let defer = q.defer();
                mdDialogMock.show.and.returnValue(defer.promise);
                defer.resolve();

                ctrl.deleteUser(document.createEvent('MouseEvents'), userMock);

                rootScope.$apply();

                expect(userServiceMock.deleteUser).toHaveBeenCalledWith(10);
            });

            it('shouldn\'t call deleteUser method in service when user don\'t confirm action', () => {
                let defer = q.defer();
                mdDialogMock.show.and.returnValue(defer.promise);
                defer.reject();

                ctrl.deleteUser(document.createEvent('MouseEvents'), userMock);

                rootScope.$apply();

                expect(userServiceMock.deleteUser).not.toHaveBeenCalled();
            });
        });
    });
}