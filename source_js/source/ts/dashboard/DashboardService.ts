module APP.Dashboard {
    import Socket = APP.Common.Socket.Socket;
    import IUserConfigurationService = APP.Configuration.IUserConfigurationService;
    import IConfigurationService = APP.Configuration.IConfigurationService;
    import IRootScopeService = angular.IRootScopeService;

    export class DashboardService {
        private _instancesList: {projectId: number, instances: IInstance[]}[] = [];
        private socketConnection: Socket;
        private socketUrl: string;

        public constructor(configuration: IConfigurationService,
                           userConfiguration: IUserConfigurationService,
                           private $rootScope: IRootScopeService) {
            this.socketConnection = new Socket();
            this.socketConnection.addEventListener('message', this.handleMessage.bind(this));
            this.socketConnection.setHeaders({
                userId: userConfiguration.user.id,
                userToken: userConfiguration.user.token
            });
            this.socketUrl = configuration.instancesSocketUrl;
        }

        public connect(): void {
            this.socketConnection.connect(this.socketUrl);
        }

        public sendSynchronizationRequest(projectId: number): void {
            this.socketConnection.emit('synchronize', {projectId: projectId});
        }

        public sendCreateRequest(projectId: number, instance: IBuildConfiguration): void {
            this.socketConnection.emit('create', {projectId: projectId, instance: instance});
        }

        public getInstancesForProjectId(projectId: number): IInstance[] {
            return _.get(_.find(this._instancesList, {projectId: projectId}), 'instances', []);
        }

        public close(): void {
            this.socketConnection.close();
        }

        private handleMessage(actionType: string, actionParams: Object): void {
            switch (actionType) {
                case 'synchronize':
                    _.remove(this._instancesList, {projectId: actionParams['projectId']});
                    this._instancesList.push({
                        projectId: actionParams['projectId'],
                        instances: actionParams['instances']
                    });
                    break;
                case 'create':
                    let instancesForProject = _.find(this._instancesList, {projectId: actionParams['projectId']});
                    if (_.isUndefined(instancesForProject)) {
                        this._instancesList.push({
                            projectId: actionParams['projectId'],
                            instances: [actionParams['instance']]
                        });
                    } else {
                        instancesForProject.instances.push(actionParams['instance']);
                    }
                    break;
            }

            this.$rootScope.$emit('Instances:changes');
        }

        get instancesList(): {projectId: number; instances: APP.Dashboard.IInstance[]}[] {
            return this._instancesList;
        }
    }

    export type NewInstance = {
        name: string,
        branch: string,
        checklistId?: number,
        jiraTaskSymbol?: string
    }
}

angular.module('dashboard')
    .service('DashboardService', ['Configuration', 'UserConfiguration', '$rootScope',
        function (configuration, userConfiguration, $rootScope) {
            return new APP.Dashboard.DashboardService(configuration, userConfiguration, $rootScope);
        }]);