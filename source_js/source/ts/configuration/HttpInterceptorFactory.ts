module APP.Configuration {
    import IInjectorService = angular.auto.IInjectorService;
    import IQService = angular.IQService;
    import IToastService = angular.material.IToastService;

    export class HttpInterceptorFactory {
        public responseError:(rejection:any) => any;

        public constructor($q: IQService, private $injector: IInjectorService) {
            HttpInterceptorFactory.prototype.responseError = (rejection: any) => {
                let toastService: IToastService = <IToastService>$injector.get('$mdToast'),
                    message = _.get(rejection, 'data.error.message', 'Wystąpił błąd, spróbuj ponownie'),
                    toast = toastService.simple()
                        .textContent(message + message + message + message + message + message + message + message)
                        .action('OK')
                        .highlightAction(true)
                        .highlightClass('md-warn')
                        .hideDelay(0)
                        .position('top right');

                if (!_.get(rejection, 'config.params.ignoreExceptions', false)) {
                    toastService.show(toast);
                    return $q.reject(rejection);
                } else {
                    return $q.reject(rejection);
                }
            };
        }

        public static Factory() {
            const factory = ($q: IQService, $injector: IInjectorService) => {
                return new HttpInterceptorFactory($q, $injector);
            };

            factory['$inject'] = ['$q', '$injector'];

            return factory;
        }
    }
}

angular.module('app')
    .factory('httpInterceptor', APP.Configuration.HttpInterceptorFactory.Factory());