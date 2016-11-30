module APP.Configuration {
    export interface IConfigurationService {
        databaseSocketUrl: string;
    }

    export class ConfigurationProvider {
        private databaseSocketUrl: string;

        public setDatabaseSocket(url:string):void {
            this.databaseSocketUrl = url;
        }

        public $get(): IConfigurationService {
            return {
                databaseSocketUrl: this.databaseSocketUrl
            };
        }
    }
}

angular.module('common')
    .provider('Configuration', [function () {
        return new APP.Configuration.ConfigurationProvider();
    }]);