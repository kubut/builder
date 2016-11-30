module APP.Projects {
    import IStateService = angular.ui.IStateService;
    import IToastService = angular.material.IToastService;
    import IDialogService = angular.material.IDialogService;
    import IRootScopeService = angular.IRootScopeService;
    import ITimeoutService = angular.ITimeoutService;
    import IScope = angular.IScope;

    export class EditProjectCtrl extends MainProjectCtrl {
        private _databaseList: IDatabase[] = [];

        public constructor($scope: IScope,
                           projectsService: ProjectsService,
                           $mdToast: IToastService,
                           public project: ProjectModel,
                           protected $state: IStateService,
                           private $mdDialog: IDialogService,
                           private databasesService: DatabasesService,
                           private $rootScope: IRootScopeService,
                           private $timeout: ITimeoutService) {
            super(projectsService, $mdToast, $state);

            this.databasesService.connect();
            this.databasesService.sendSynchronizationRequest(this.project.id);

            $rootScope.$on('Databases:changes', () => {
                $timeout(this.loadDatabases.bind(this));
            });

            $scope.$on('$destroy', () => {
                this.databasesService.close();
            });
        }

        public showToast(): void {
            this.$mdToast.showSimple('Zapisano pomyślnie');
        }

        public deleteProject(ev: MouseEvent, id: number): void {
            let dialog = this.$mdDialog.confirm()
                .title('Może jeszcze to przemyśl?')
                .textContent('Jesteś pewien że chcesz usunąć projekt? Zostaną usunięte ' +
                    'wszystkie bazy danych oraz instancje należące do tego projektu')
                .targetEvent(ev)
                .clickOutsideToClose(true)
                .ok('Jestem pewien')
                .cancel('Jeszcze to przemyślę');

            this.$mdDialog.show(dialog).then(() => {
                this.projectsService.deleteProject(id).then(() => {
                    this.projectsService.loadProjectList();
                    this.$state.go('app.project.dashboard');
                });
            });
        }

        public addDatabase(ev: MouseEvent): void {
            let dialog = this.$mdDialog.prompt()
                .title('Nowa baza danych')
                .textContent('Nowa instancja bazy danych zostanie utworzona z domyżlnego pliku SQL dla projektu. ' +
                    'Jej nazwa zostanie wygenerowana dynamicznie.')
                .placeholder('Komentarz do bazy...')
                .targetEvent(ev)
                .ok('Stwórz')
                .cancel('Rozmyśliłem się')
                .clickOutsideToClose(true);

            this.$mdDialog.show(dialog).then((result: string) => {
                this.databasesService.sendCreateRequest(this.project.id, result);
            });
        }

        public deleteDatabase(ev: MouseEvent, databaseId: number): void {
            let dialog = this.$mdDialog.confirm()
                .title('Może jeszcze to przemyśl?')
                .textContent('Jesteś pewien że chcesz usunąć instancję bazy? ' +
                    'Wszystkie dane z wybranej instancji zostaną bezpowrotnie utracone')
                .targetEvent(ev)
                .clickOutsideToClose(true)
                .ok('Jestem pewien')
                .cancel('Jeszcze to przemyślę');

            this.$mdDialog.show(dialog).then(() => {
                this.databasesService.sendDeleteRequest(this.project.id, databaseId);
            });
        }

        private loadDatabases(): void {
            this._databaseList = this.databasesService.getDatabasesForProjectId(this.project.id);
        }

        get databaseList(): APP.Projects.IDatabase[] {
            return this._databaseList;
        }
    }
}

angular.module('projects')
    .controller('EditProjectCtrl',
        ['$scope', 'ProjectsService', '$mdToast', 'project', '$state', '$mdDialog', 'DatabasesService', '$rootScope', '$timeout',
            function ($scope, projectsService, $mdToast, project, $state, $mdDialog, databasesService, $rootScope, $timeout) {
                return new APP.Projects.EditProjectCtrl(
                    $scope, projectsService, $mdToast, project, $state, $mdDialog, databasesService, $rootScope, $timeout
                );
            }]);