module APP.Projects {
    import IStateService = angular.ui.IStateService;
    import IToastService = angular.material.IToastService;

    export class EditProjectCtrl extends MainProjectCtrl {
        public constructor(projectsService: ProjectsService,
                           $mdToast: IToastService,
                           $state: IStateService,
                           project: ProjectModel) {
            super(projectsService, $mdToast, $state);

            this.project = project;
        }

        public showToast(): void {
            this.$mdToast.showSimple('Zapisano pomyślnie');
        }
    }
}

angular.module('projects')
    .controller('EditProjectCtrl', ['ProjectsService', '$mdToast', '$state', 'project',
        function (projectsService, $mdToast, $state, project) {
            return new APP.Projects.EditProjectCtrl(projectsService, $mdToast, $state, project);
        }]);