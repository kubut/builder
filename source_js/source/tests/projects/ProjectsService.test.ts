module APP.Projects {
    describe('ProjectsService', () => {
        let service: ProjectsService,
            httpBackend,
            projectsMock,
            sqlsMock,
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

            sqlsMock = [
                'orchid.sql',
                'stravenue.sql'
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

        describe('loadSqlFiles', () => {
            beforeEach(() => {
                httpBackend.expectGET('/url/5').respond(sqlsMock);
            });

            it('should load 2 files', () => {
                service.loadSqlFiles();
                httpBackend.flush();

                expect(service.sqlFiles.length).toBe(2);
            });

            it('should call proper API', () => {
                service.loadSqlFiles();
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('get_databases_files');
            });
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

                expect(routingMock.generate).toHaveBeenCalledWith('get_projects');
            });
        });

        describe('loadProjectSettings', () => {
            it('should call proper API', () => {
                httpBackend.expectGET('/url/5').respond({});

                service.loadProjectSettings(42);
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('project_details', {id: 42});
            });
        });

        describe('saveProject', () => {
            it('should call proper API for create new project', () => {
                let projectMock = {
                    id: -1,
                    name: 'name',
                    installScript: 'installScript',
                    sqlFile: 'sqlFile',
                    sqlUser: 'sqlUser',
                    configScript: 'configScript',
                    domain: '',
                    gitPath: 'gitPath',
                    gitLogin: 'gitLogin',
                    gitPass: 'gitPass'
                };

                httpBackend.expectPUT('/url/5', {
                    name: projectMock.name,
                    installScript: projectMock.installScript,
                    sqlFile: projectMock.sqlFile,
                    sqlUser: projectMock.sqlUser,
                    configScript: projectMock.configScript,
                    domain: projectMock.domain,
                    gitPath: projectMock.gitPath,
                    gitLogin: projectMock.gitLogin,
                    gitPass: projectMock.gitPass
                }).respond({});

                service.saveProject(new ProjectModel(projectMock));
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('add_project');
            });

            it('should call proper API for edit project', () => {
                let projectMock = {
                    id: 42,
                    name: 'name',
                    installScript: 'installScript',
                    sqlFile: 'sqlFile',
                    sqlUser: 'sqlUser',
                    configScript: 'configScript',
                    domain: '',
                    gitPath: 'gitPath',
                    gitLogin: 'gitLogin',
                    gitPass: 'gitPass'
                };

                httpBackend.expectPUT('/url/5', {
                    name: projectMock.name,
                    installScript: projectMock.installScript,
                    sqlFile: projectMock.sqlFile,
                    sqlUser: projectMock.sqlUser,
                    configScript: projectMock.configScript,
                    domain: projectMock.domain,
                    gitPath: projectMock.gitPath,
                    gitLogin: projectMock.gitLogin,
                    gitPass: projectMock.gitPass
                }).respond({});

                service.saveProject(new ProjectModel(projectMock));
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('edit_project', {id: 42});
            });
        });

        describe('deleteProject', () => {
            it('should call proper API for project deletion', () => {
                httpBackend.expectDELETE('/url/5').respond({});

                service.deleteProject(42);
                httpBackend.flush();

                expect(routingMock.generate).toHaveBeenCalledWith('delete_project', {id: 42});
            });
        });
    });
}