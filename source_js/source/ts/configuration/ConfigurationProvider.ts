module APP.Configuration {
    export interface IConfigurationService {
        databaseSocketUrl: string;
        instancesSocketUrl: string;
    }

    export class ConfigurationProvider {
        private databaseSocketUrl: string;
        private instancesSocketUrl: string;

        public setDatabaseSocketUrl(url:string):void {
            this.databaseSocketUrl = url;
        }

        public setInstancesSocketUrl(url:string):void {
            this.instancesSocketUrl = url;
        }

        public $get(): IConfigurationService {
            return {
                databaseSocketUrl: this.databaseSocketUrl,
                instancesSocketUrl: this.instancesSocketUrl
            };
        }
    }
}

angular.module('common')
    .provider('Configuration', [function () {
        return new APP.Configuration.ConfigurationProvider();
    }]);