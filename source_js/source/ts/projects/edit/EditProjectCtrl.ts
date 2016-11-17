module APP.Projects {
    import IStateService = angular.ui.IStateService;
    import IToastService = angular.material.IToastService;
    import IDialogService = angular.material.IDialogService;

    export class EditProjectCtrl extends MainProjectCtrl {
        public constructor(projectsService: ProjectsService,
                           $mdToast: IToastService,
                           protected $state: IStateService,
                           project: ProjectModel,
                           private $mdDialog: IDialogService) {
            super(projectsService, $mdToast, $state);

            this.project = project;
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
                    this.$state.go('app.dashboard');
                });
            });
        }
    }
}

angular.module('projects')
    .controller('EditProjectCtrl', ['ProjectsService', '$mdToast', '$state', 'project', '$mdDialog',
        function (projectsService, $mdToast, $state, project, $mdDialog) {
            return new APP.Projects.EditProjectCtrl(projectsService, $mdToast, $state, project, $mdDialog);
        }]);