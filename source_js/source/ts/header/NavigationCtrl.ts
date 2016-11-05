module APP.Header {
    import IConfigurationService = APP.Configuration.IConfigurationService;

    export class NavigationCtrl {
        public constructor(public configuration:IConfigurationService) {
        }
    }
}

angular.module('users')
    .controller('NavigationCtrl', ['Configuration', function (Configuration) {
        return new APP.Header.NavigationCtrl(Configuration);
    }]);