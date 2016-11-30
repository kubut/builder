/// <reference path="../common/UserModel.ts"/>
module APP.Configuration {
    import UserRole = APP.Common.UserRole;
    import UserModel = APP.Common.UserModel;

    export interface IUserConfigurationService {
        user: UserModel;
    }

    export class UserConfigurationProvider {
        private user: UserModel;
        private userRole = UserRole.User;
        private userId = 0;
        private userToken = '';

        public setUserRole(role: string) {
            switch (role) {
                case 'ROLE_USER':
                    this.userRole = UserRole.User;
                    break;
                case 'ROLE_ADMIN':
                    this.userRole = UserRole.Admin;
                    break;
            }

            return this;
        }

        public setUserId(id: number): UserConfigurationProvider {
            this.userId = id;
            return this;
        }

        public setUserToken(token: string): UserConfigurationProvider {
            this.userToken = token;
            return this;
        }

        public createUser():void {
            this.user = new UserModel(this.userId, this.userRole, this.userToken);
        }

        public $get(): IUserConfigurationService {
            return {
                user: this.user
            };
        }
    }
}

angular.module('common')
    .provider('UserConfiguration', [function () {
        return new APP.Configuration.UserConfigurationProvider();
    }]);