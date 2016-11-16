module APP.Projects {
    export class ProjectModel implements IProjectArgs {
        private _id: number;
        private _name: string;
        private _installScript: string;
        private _sqlFile: string;
        private _sqlUser: string;
        private _configScript: string;
        private _domain: string;
        private _gitPath: string;
        private _gitLogin: string;
        private _gitPass: string;

        public constructor(args: IProjectArgs) {
            _.assign(this, args);
        }

        get id(): number {
            return this._id;
        }

        set id(value: number) {
            this._id = value;
        }

        get name(): string {
            return this._name;
        }

        set name(value: string) {
            this._name = value;
        }

        get installScript(): string {
            return this._installScript;
        }

        set installScript(value: string) {
            this._installScript = value;
        }

        get sqlFile(): string {
            return this._sqlFile;
        }

        set sqlFile(value: string) {
            this._sqlFile = value;
        }

        get configScript(): string {
            return this._configScript;
        }

        set configScript(value: string) {
            this._configScript = value;
        }

        get domain(): string {
            return this._domain;
        }

        set domain(value: string) {
            this._domain = value;
        }

        get gitPath(): string {
            return this._gitPath;
        }

        set gitPath(value: string) {
            this._gitPath = value;
        }

        get gitLogin(): string {
            return this._gitLogin;
        }

        set gitLogin(value: string) {
            this._gitLogin = value;
        }

        get gitPass(): string {
            return this._gitPass;
        }

        set gitPass(value: string) {
            this._gitPass = value;
        }

        get sqlUser(): string {
            return this._sqlUser;
        }

        set sqlUser(value: string) {
            this._sqlUser = value;
        }
    }
}