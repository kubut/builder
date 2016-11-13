module APP.Projects {
    describe('ProjectsService', () => {
        let service: ProjectsService,
            httpBackend,
            projectsMock,
            routingMock;

        beforeEach(() => {
            angular.mock.module('projects');

            routingMock = {
                generate: jasmine.createSpy('routing.generate').and.returnValue('/url/5')
            };

            angular.mock.module(($provide) => {
                $provide.value('Routing', routingMock);
            });

            projectsMock = [
                {id: 1, name: 'one'},
                {id: 2, name: 'two'},
                {id: 3, name: 'three'}
            ];

            angular.mock.inject((Routing, $httpBackend, $http) => {
                httpBackend = $httpBackend;

                service = new ProjectsService($http, Routing);
            });
        });

        afterEach(() => {
            httpBackend.verifyNoOutstandingExpectation();
            httpBackend.verifyNoOutstandingRequest();
        });

        describe('loadProjectList', () => {
            beforeEach(() => {
                httpBackend.expectGET('/url/5').respond(projectsMock);
            });

            it('should load 3 projects', () => {
                service.loadProjectList();
                httpBackend.flush();

                expect(service.projects.length).toBe(3);
            });

            it('should call proper API', () => {
                service.loadProjectList();
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('get_projects_list');
            });
        });
    });
}