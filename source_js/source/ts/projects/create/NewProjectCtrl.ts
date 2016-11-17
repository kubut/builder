module APP.Projects {
    import IToastService = angular.material.IToastService;
    import IStateService = angular.ui.IStateService;

    export class NewProjectCtrl extends MainProjectCtrl {
        public constructor(projectsService: ProjectsService,
                           $mdToast: IToastService,
                           $state: IStateService) {
            super(projectsService, $mdToast, $state);

            this.project = new ProjectModel({
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
        }

        public showToast() {
            this.$mdToast.showSimple('Dodano pomyślnie');
        }
    }
}

angular.module('projects')
    .controller('NewProjectCtrl', ['ProjectsService', '$mdToast', '$state', function (projectsService, $mdToast, $state) {
        return new APP.Projects.NewProjectCtrl(projectsService, $mdToast, $state);
    }]);