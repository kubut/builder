module APP.Dashboard {
    import IDialogService = angular.material.IDialogService;
    export class NewBuildModalCtrl {
        private _ready: boolean = false;
        private _checklistsVisible: boolean = false;
        private _jiraVisible: boolean = false;
        private _buildConfiguration: IBuildConfiguration;

        public constructor(projectId: number,
                           instanceId: number,
                           private $mdDialog: IDialogService,
                           private buildService: BuildService) {
            this._buildConfiguration = {
                name: '',
                branch: '',
                instanceId: instanceId,
                databaseId: -1
            };

            buildService.getBuildInfo(projectId).then(() => {
                this._ready = true;
            });
        }

        public close(): void {
            this.$mdDialog.cancel();
        }

        public save(): void {
            if (this.buildConfiguration.instanceId < 0) {
                delete this.buildConfiguration.instanceId;
            }

            this.$mdDialog.hide(this.buildConfiguration);
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
    .controller('NewBuildModalCtrl', ['projectId', 'instanceId', '$mdDialog', 'BuildService',
        function (projectId, instanceId, $mdDialog, buildService) {
            return new APP.Dashboard.NewBuildModalCtrl(projectId, instanceId, $mdDialog, buildService);
        }]);