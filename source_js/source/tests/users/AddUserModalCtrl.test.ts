module APP.Users {
    import UserRole = APP.Common.UserRole;
    describe('AddUserModalCtrl', () => {
        let ctrl:AddUserModalCtrl,
            mdDialogMock,
            userMock;

        beforeEach(() => {
            angular.mock.module('users');

            mdDialogMock = {
                cancel: jasmine.createSpy('mdDialog.cancel'),
                hide: jasmine.createSpy('mdDialog.hide')
            };

            userMock = {
                id: 10,
                name: 'name',
                surname: 'surname',
                email: 'mail@ma.il',
                role: UserRole.Admin
            };

            angular.mock.module(($provide) => {
                $provide.value('$mdDialog', mdDialogMock);
            });

            angular.mock.inject(($mdDialog) => {
                ctrl = new AddUserModalCtrl($mdDialog);
            });
        });

        describe('constructor', () => {
            it('should create empty user', () => {
                let user = ctrl.user;

                expect(user.name).toBe('');
                expect(user.surname).toBe('');
                expect(user.email).toBe('');
                expect(user.id).toBe(0);
                expect(user.role).toBe(UserRole.User);
            });
        });

        describe('close', () => {
            it('should call close on mdDialog service', () => {
                ctrl.close();

                expect(mdDialogMock.cancel).toHaveBeenCalled();
            });
        });

        describe('addUser', () => {
            it('should call hide on mdDialog service', () => {
                ctrl.user = userMock;

                ctrl.addUser();

                expect(mdDialogMock.hide).toHaveBeenCalledWith(userMock);
            });
        });

    });
}