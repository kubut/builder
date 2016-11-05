module APP.Configuration {
    import IThemingProvider = angular.material.IThemingProvider;

    export class MdConfig {
        public static $inject = ['$mdThemingProvider'];

        public static configure($mdThemingProvider:IThemingProvider) {
            $mdThemingProvider.theme('default')
                .primaryPalette('blue', {
                    'default': '700'
                })
                .accentPalette('deep-orange')
                .backgroundPalette('grey', {
                    'default': '200'
                });
        }
    }
}