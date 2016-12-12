module APP.Dashboard {
    import IDialogService = angular.material.IDialogService;
    export class NewBuildModalCtrl {
        private _ready: boolean = false;
        private _checklistsVisible: boolean = false;
        private _jiraVisible: boolean = false;
        private _buildConfiguration: IBuildConfiguration;

        public constructor(projectId: number,
                           private $mdDialog: IDialogService,
                           private dashboardService: DashboardService,
                           private buildService: BuildService) {
            buildService.getBuildInfo(projectId).then(() => {
                this._ready = true;
            });
        }

        public close(): void {
            this.$mdDialog.cancel();
        }

        get checklistsVisible(): boolean {
            return this._checklistsVisible;
        }

        set checklistsVisible(value: boolean) {
            this._checklistsVisible = value;
        }

        get jiraVisible(): boolean {
            return this._jiraVisible;
        }

        set jiraVisible(value: boolean) {
            this._jiraVisible = value;
        }

        get ready(): boolean {
            return this._ready;
        }

        get buildConfiguration(): APP.Dashboard.IBuildConfiguration {
            return this._buildConfiguration;
        }

        set buildConfiguration(value: APP.Dashboard.IBuildConfiguration) {
            this._buildConfiguration = value;
        }
    }
}

angular.module('dashboard')
    .controller('NewBuildModalCtrl', ['projectId', '$mdDialog', 'DashboardService', 'BuildService',
        function (projectId, $mdDialog, dashboardService, buildService) {
            return new APP.Dashboard.NewBuildModalCtrl(projectId, $mdDialog, dashboardService, buildService);
        }]);