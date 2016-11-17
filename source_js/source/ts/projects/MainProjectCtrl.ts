module APP.Projects {
    import IToastService = angular.material.IToastService;
    import IStateService = angular.ui.IStateService;

    export abstract class MainProjectCtrl {
        public project: ProjectModel;
        public regex: RegExp;

        public constructor(public projectsService: ProjectsService,
                           protected $mdToast: IToastService,
                           protected $state: IStateService) {
            this.regex = /[\s\S]*\$DATABASE_NAME\$+[\s\S]*/;
        }

        public saveProject() {
            this.projectsService.saveProject(this.project).then((respond) => {
                this.showToast();
                this.projectsService.loadProjectList();
                this.$state.go('app.admin.project.edit', {id: respond.data.id});
            });
        }

        public abstract showToast():void;
    }
}