module APP.Projects {
    import IToastService = angular.material.IToastService;

    export class ProjectCtrl {
        public project: ProjectModel;
        public regex: RegExp;

        public constructor(public projectsService: ProjectsService, private $mdToast: IToastService) {
            this.project = new ProjectModel({
                id: 0,
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

            this.regex = /[\s\S]*\$DATABASE_NAME\$+[\s\S]*/;
        }

        public saveProject() {
            this.projectsService.saveProject(this.project).then(() => {
                this.$mdToast.showSimple('Dodano pomyślnie');
                this.projectsService.loadProjectList();
            });
        }
    }
}

angular.module('projects')
    .controller('ProjectCtrl', ['ProjectsService', '$mdToast', function (projectsService, $mdToast) {
        return new APP.Projects.ProjectCtrl(projectsService, $mdToast);
    }]);