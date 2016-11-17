module APP.Projects {
    describe('EditProjectCtrl', () => {
        let ctrl: EditProjectCtrl,
            projectMock: ProjectModel,
            ProjectsServiceMock,
            $mdToastMock,
            $stateMock,
            $mdDialogMock,
            dialogMock,
            q;

        beforeEach(() => {
            angular.mock.module('projects');

            projectMock = new ProjectModel({
                id: -1,
                name: '',
                installScript: '',
                sqlFile: '',
                sqlUser: '',
                configScript: '',
                domain: '',
                gitPath: '',
                gitLogin: '',
                gitPass: ''
            });

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
                clickOutsideToClose: () => {
                    return dialogMock;
                },
                ok: () => {
                    return dialogMock;
                },
                cancel: () => {
                    return dialogMock;
                }
            };

            ProjectsServiceMock = {
                deleteProject: jasmine.createSpy('projectsService.deleteProject')
            };

            $mdToastMock = {
                showSimple: jasmine.createSpy('$mdToast.showSimple')
            };

            $stateMock = {
                go: jasmine.createSpy('$state.go')
            };

            $mdDialogMock = {
                confirm: jasmine.createSpy('mdDialog.confirm').and.returnValue(dialogMock),
                show: jasmine.createSpy('mdDialog.show')
            };


            angular.mock.module(($provide) => {
                $provide.value('ProjectsService', ProjectsServiceMock);
                $provide.value('$mdToast', $mdToastMock);
                $provide.value('$state', $stateMock);
                $provide.value('$mdDialog', $mdDialogMock);
            });

            angular.mock.inject((ProjectsService, $mdToast, $state, $mdDialog, $q) => {
                q = $q;

                ctrl = new EditProjectCtrl(ProjectsService, $mdToast, $state, projectMock, $mdDialog);
            });
        });

        describe('showToast', () => {
            it('should show toast', () => {
                ctrl.showToast();

                expect($mdToastMock.showSimple).toHaveBeenCalledWith('Zapisano pomyślnie');
            });
        });

        describe('deleteProject', () => {
            it('should show dialog', () => {
                let defer = q.defer();
                $mdDialogMock.show.and.returnValue(defer.promise);
                defer.resolve();

                ctrl.deleteProject(document.createEvent('MouseEvents'), 42);

                expect($mdDialogMock.confirm).toHaveBeenCalled();
                expect($mdDialogMock.show).toHaveBeenCalledWith(dialogMock);
            });
        });
    });
}