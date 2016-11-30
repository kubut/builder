module APP.Projects {
    import Socket = APP.Common.Socket.Socket;
    import IConfigurationService = APP.Configuration.IConfigurationService;
    import IRootScopeService = angular.IRootScopeService;
    import IUserConfigurationService = APP.Configuration.IUserConfigurationService;

    export class DatabasesService {
        private socketConnection: Socket;
        private socketUrl: string;
        private _databases: {projectId: number, databases: IDatabase[]}[] = [];

        public constructor(configuration: IConfigurationService,
                           userConfiguration: IUserConfigurationService,
                           private $rootScope: IRootScopeService) {
            this.socketConnection = new Socket();
            this.socketConnection.addEventListener('message', this.handleMessage.bind(this));
            this.socketConnection.setHeaders({
                userId: userConfiguration.user.id,
                userToken: userConfiguration.user.token
            });
            this.socketUrl = configuration.databaseSocketUrl;
        }

        public connect(): void {
            this.socketConnection.connect(this.socketUrl);
        }

        public close(): void {
            this.socketConnection.close();
        }

        public getDatabasesForProjectId(projectId: number): IDatabase[] {
            let projectDatabasesData = _.find(this._databases, {projectId: projectId});
            return projectDatabasesData ? projectDatabasesData.databases : [];
        }

        public sendSynchronizationRequest(projectId: number): void {
            this.socketConnection.emit('synchronize', {projectId: projectId});
        }

        private handleMessage(actionType: string, actionParams: Object): void {
            switch (actionType) {
                case 'synchronize':
                    _.remove(this._databases, {projectId: actionParams['projectId']});
                    this._databases.push({projectId: actionParams['projectId'], databases: actionParams['databases']});
                    break;
                case 'update':
                    _.forEach(this._databases, (databaseData: {projectId: number, databases: IDatabase[]}): void => {
                        if (databaseData.projectId === actionParams['projectId']) {
                            _.forEach(databaseData.databases, (database) => {
                                if (database.id === actionParams['databaseId']) {
                                    database.status = actionParams['status'];
                                }
                            });
                        }
                    });
                    break;
            }

            this.$rootScope.$emit('Databases:changes');
        }
    }
}

angular.module('projects')
    .service('DatabasesService', ['Configuration', 'UserConfiguration', '$rootScope',
        function (configuration, userConfiguration, $rootScope) {
            return new APP.Projects.DatabasesService(configuration, userConfiguration, $rootScope);
        }]);