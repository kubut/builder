module APP.Users {
    import UserRole = APP.Common.UserRole;
    describe('UsersService', () => {
        let service:UsersService,
            routingMock,
            httpBackend,
            userMock;

        beforeEach(() => {
            angular.mock.module('users');

            routingMock = {
                generate: jasmine.createSpy('routing.generate').and.returnValue('/url/')
            };

            userMock = {
                id: 10,
                name: 'name',
                surname: 'surname',
                email: 'mail@ma.il',
                role: UserRole.Admin
            };

            angular.mock.module(($provide) => {
                $provide.value('Routing', routingMock);
            });

            angular.mock.inject((Routing, $http, $httpBackend) => {
                httpBackend = $httpBackend;
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
            afterEach(() => {
                httpBackend.verifyNoOutstandingExpectation();
                httpBackend.verifyNoOutstandingRequest();
            });

            it('should set busy to true', () => {
                expect(service.busy).toBeFalsy();

                service.addUser(userMock);

                expect(service.busy).toBeTruthy();
                httpBackend.flush();
            });

            it('should call proper API', () => {
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
    });
}