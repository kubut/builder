module APP.Projects {
    describe('ProjectCtrl', () => {
        let ctrl: ProjectCtrl,
            projectsServiceMock,
            $mdToastMock,
            q,
            rootScope;

        beforeEach(() => {
            angular.mock.module('projects');

            projectsServiceMock = {
                saveProject: jasmine.createSpy('ProjectsService.saveProject'),
                loadProjectList: jasmine.createSpy('ProjectsService.loadProjectList')
            };

            $mdToastMock = {
                showSimple: jasmine.createSpy('toast.showSimple')
            };

            angular.mock.module(($provide) => {
                $provide.value('ProjectsService', projectsServiceMock);
                $provide.value('$mdToast', $mdToastMock);
            });

            angular.mock.inject((ProjectsService, $mdToast, $q, $rootScope) => {
                q = $q;
                rootScope = $rootScope;

                ctrl = new ProjectCtrl(ProjectsService, $mdToast);
            });
        });

        describe('saveProject', () => {
            it('should call saveProject from service', () => {
                let defer = q.defer();
                defer.resolve();

                projectsServiceMock.saveProject.and.returnValue(defer.promise);
                ctrl.saveProject();

                rootScope.$apply();

                expect(projectsServiceMock.saveProject).toHaveBeenCalledWith(ctrl.project);
            });
            it('should show toast and reload projects list', () => {
                let defer = q.defer();
                defer.resolve();

                projectsServiceMock.saveProject.and.returnValue(defer.promise);
                ctrl.saveProject();

                rootScope.$apply();

                expect(projectsServiceMock.loadProjectList).toHaveBeenCalled();
                expect($mdToastMock.showSimple).toHaveBeenCalledWith('Dodano pomyślnie');
            });
        });
    });
}