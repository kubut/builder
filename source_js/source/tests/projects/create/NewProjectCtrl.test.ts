module APP.Projects {
    describe('NewProjectCtrl', () => {
        let ctrl: NewProjectCtrl,
            projectsServiceMock,
            $mdToastMock,
            q,
            rootScope,
            $stateMock;

        beforeEach(() => {
            angular.mock.module('projects');

            projectsServiceMock = {
                saveProject: jasmine.createSpy('ProjectsService.saveProject'),
                loadProjectList: jasmine.createSpy('ProjectsService.loadProjectList')
            };

            $mdToastMock = {
                showSimple: jasmine.createSpy('toast.showSimple')
            };

            $stateMock = {
                go: jasmine.createSpy('state.go')
            };

            angular.mock.module(($provide) => {
                $provide.value('ProjectsService', projectsServiceMock);
                $provide.value('$mdToast', $mdToastMock);
                $provide.value('$state', $stateMock);
            });

            angular.mock.inject((ProjectsService, $mdToast, $state, $q, $rootScope) => {
                q = $q;
                rootScope = $rootScope;

                ctrl = new NewProjectCtrl(ProjectsService, $mdToast, $state);
            });
        });

        describe('saveProject', () => {
            beforeEach(() => {
                let defer = q.defer();
                defer.resolve({data: {id: 2}});

                projectsServiceMock.saveProject.and.returnValue(defer.promise);
                ctrl.saveProject();

                rootScope.$apply();
            });

            it('should call saveProject from service', () => {
                expect(projectsServiceMock.saveProject).toHaveBeenCalledWith(ctrl.project);
            });
            it('should show toast and reload projects list', () => {
                expect(projectsServiceMock.loadProjectList).toHaveBeenCalled();
                expect($mdToastMock.showSimple).toHaveBeenCalledWith('Dodano pomyślnie');
            });
            it('should go to edit page', () => {
                expect($stateMock.go).toHaveBeenCalledWith('app.admin.project.edit', {id: 2});
            });
        });
    });
}