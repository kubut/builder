module APP.Projects {
    import IHttpService = angular.IHttpService;
    import IRoutingService = APP.Common.IRoutingService;
    import IPromise = angular.IPromise;

    export class JiraConfigurationService {
        public jiraConfig: IJiraConfig;

        public constructor(private http: IHttpService,
                           private routing: IRoutingService) {

        }

        public loadJiraConfiguration(projectId: number): IPromise<any> {
            return this.http.get(this.routing.generate('get_jira_configuration', {projectId: projectId})).then((config:any) => {
                this.jiraConfig = config;
            });
            // return this.http.get('http://builder-dev.vagrant:3000/jira').then((response: any) => {
            //     this.jiraConfig = response.data;
            // });
        }

        public saveConfiguration(projectId: number): IPromise<any> {
            return this.http.post(this.routing.generate('save_jira_configuration', {projectId: projectId}), this.jiraConfig);
        }
    }
}

angular.module('projects')
    .service('JiraConfigurationService', ['$http', 'Routing', function ($http, routing) {
        return new APP.Projects.JiraConfigurationService($http, routing);
    }]);