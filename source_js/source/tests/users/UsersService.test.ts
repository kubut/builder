module APP.Users {
    import UserRole = APP.Common.UserRole;
    describe('UsersService', () => {
        let service: UsersService,
            routingMock,
            httpBackend,
            userListMock,
            userMock;

        beforeEach(() => {
            angular.mock.module('users');

            routingMock = {
                generate: jasmine.createSpy('routing.generate').and.returnValue('/url/')
            };

            userListMock = [
                {
                    'id': 0,
                    'name': 'Wincenty',
                    'surname': 'Rogala',
                    'email': 'Pawe_Adamski10@gmail.com',
                    'isActive': false,
                    'role': 0,
                    'activationCode': 'acuHqkkWEb'
                },
                {
                    'id': 1,
                    'name': 'Samuel',
                    'surname': 'Tarnowski',
                    'email': 'Arseniusz40@yahoo.com',
                    'isActive': true,
                    'role': 0
                },
                {
                    'id': 2,
                    'name': 'Jeremiasz',
                    'surname': 'Węgrzyn',
                    'email': 'Teresa.Krawczyk37@gmail.com',
                    'isActive': false,
                    'role': 1,
                    'activationCode': 'S3YqzVrtAE'
                }
            ];

            userMock = {
                id: 10,
                name: 'name',
                surname: 'surname',
                email: 'mail@ma.il',
                role: UserRole.Admin,
                isActive: false
            };

            angular.mock.module(($provide) => {
                $provide.value('Routing', routingMock);
            });

            angular.mock.inject((Routing, $http, $httpBackend) => {
                httpBackend = $httpBackend;

                httpBackend.expectGET('/url/').respond({});

                service = new UsersService(Routing, $http);
            });

        });

        afterEach(() => {
            httpBackend.verifyNoOutstandingExpectation();
            httpBackend.verifyNoOutstandingRequest();
        });

        describe('addUser', () => {
            beforeEach(() => {
                httpBackend.expectPOST('/url/').respond({});
            });

            it('should call proper API', () => {
                spyOn(service, 'reloadUserList');

                service.addUser(userMock);
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('user_add');
            });

            it('should call reloadUserList()', () => {
                spyOn(service, 'reloadUserList');

                service.addUser(userMock);
                httpBackend.flush();

                expect(service.reloadUserList).toHaveBeenCalled();
            });
        });

        describe('deleteUser', () => {
            beforeEach(() => {
                httpBackend.expectDELETE('/url/').respond({});
                spyOn(service, 'reloadUserList');
            });

            it('should call reloadUserList', () => {
                service.deleteUser(3);
                httpBackend.flush();

                expect(service.reloadUserList).toHaveBeenCalled();
            });

            it('should call proper API', () => {
                service.deleteUser(3);
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('delete_user', {id: 3});
            });
        });

        describe('resetPassword', () => {
            beforeEach(() => {
                httpBackend.expectPATCH('/url/').respond({});
                spyOn(service, 'reloadUserList');
            });

            it('should call reloadUserList', () => {
                service.resetPassword(3);
                httpBackend.flush();

                expect(service.reloadUserList).toHaveBeenCalled();
            });

            it('should call proper API', () => {
                service.resetPassword(3);
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('reset_user_password', {id: 3});
            });
        });

        describe('reloadUserList', () => {
            beforeEach(() => {
                httpBackend.expectGET('/url/').respond(userListMock);
            });

            it('should call proper API', () => {
                service.reloadUserList();

                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('get_users');
            });

            it('should load users', () => {
                service.reloadUserList();

                httpBackend.flush();

                expect(service.users.length).toBe(3);
            });

            it('should load activationCode for users[0] and shouldn\'t for users[1]', () => {
                service.reloadUserList();

                httpBackend.flush();

                expect(service.users[0].activationCode).toBe('acuHqkkWEb');
                expect(service.users[1].activationCode).toBeUndefined();
            });

            it('should change busy to false', () => {
                service.reloadUserList();
                expect(service.busy).toBeTruthy();

                httpBackend.flush();

                expect(service.busy).toBeFalsy();
            });

            it('shouldn\'t load double users', () => {
                service.reloadUserList();
                httpBackend.flush();

                httpBackend.expectGET('/url/').respond(userListMock);
                service.reloadUserList();
                httpBackend.flush();

                httpBackend.expectGET('/url/').respond(userListMock);
                service.reloadUserList();
                httpBackend.flush();

                expect(service.users.length).toBe(3);
            });
        });
    });
}