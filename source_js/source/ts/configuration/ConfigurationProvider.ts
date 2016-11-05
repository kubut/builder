module APP.Configuration {
    import UserRole = APP.Common.UserRole;
    import UserModel = APP.Common.UserModel;

    export interface IConfigurationService {
        user: UserModel;
    }

    export class ConfigurationProvider {
        private user: UserModel;


        public setUserRole(role:string) {
            switch (role) {
                case 'ROLE_USER':
                    this.user = new UserModel(UserRole.User);
                    break;
                case 'ROLE_ADMIN':
                    this.user = new UserModel(UserRole.Admin);
                    break;
            }

            return this;
        }

        public $get(): IConfigurationService {
            return {
                user: this.user
            };
        }
    }
}

angular.module('common')
    .provider('Configuration', [function () {
        return new APP.Configuration.ConfigurationProvider();
    }]);