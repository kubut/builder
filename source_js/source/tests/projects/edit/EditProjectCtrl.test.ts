module APP.Projects {
    describe('EditProjectCtrl', () => {
        let ctrl: EditProjectCtrl,
            projectMock: ProjectModel,
            ProjectsServiceMock,
            $mdToastMock,
            $stateMock,
            $mdDialogMock,
            dialogMock,
            databasesServiceMock,
            rootScopeMock,
            scopeMock,
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

            databasesServiceMock = {
                connect: jasmine. createSpy('databaseService.connect'),
                sendSynchronizationRequest: jasmine. createSpy('databaseService.sendSynchronizationRequest')
            };

            rootScopeMock = {
                $on: jasmine.createSpy('rootScope.on')
            };

            scopeMock = {
                $on: jasmine.createSpy('scopeMock.on')
            };

            angular.mock.module(($provide) => {
                $provide.value('ProjectsService', ProjectsServiceMock);
                $provide.value('$mdToast', $mdToastMock);
                $provide.value('$state', $stateMock);
                $provide.value('$mdDialog', $mdDialogMock);
                $provide.value('$scope', scopeMock);
                $provide.value('databasesService', databasesServiceMock);
                $provide.value('rootScope', rootScopeMock);
                $provide.value('timeout', {});
                $provide.value('jiraConfigService', {});
            });

            angular.mock.inject(
                (ProjectsService, $mdToast, $state, $mdDialog, $q, $scope, databasesService, rootScope, timeout, jiraConfigService) => {
                    q = $q;

                    ctrl = new EditProjectCtrl(
                        $scope,
                        ProjectsService,
                        $mdToast,
                        projectMock,
                        $state,
                        $mdDialog,
                        databasesService,
                        rootScope,
                        timeout,
                        jiraConfigService
                    );
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