module APP.Checklist {
    import IDialogService = angular.material.IDialogService;
    import IStateService = angular.ui.IStateService;
    import IStateParamsService = angular.ui.IStateParamsService;
    import ILocationService = angular.ILocationService;

    export class ChecklistCtrl {
        private defaultItemValue = 'Nowe zadanie';
        private _newItem: string;

        public constructor(private checklistService: ChecklistService,
                           private checklist: ChecklistModel,
                           private $mdDialog: IDialogService,
                           private $state: IStateService,
                           private $stateParams: IStateParamsService,
                           private $location: ILocationService) {
            this.newItem = this.defaultItemValue;
        }

        public getPublicLink(): string {
            return this.$location.protocol() +
                '://' + this.$location.host() +
                ':' + this.$location.port() +
                '/checklist/' + this.checklist.token;
        }

        public deleteChecklist(ev: MouseEvent): void {
            let dialog = this.$mdDialog.confirm()
                .title('Lepiej to przemyśl...')
                .textContent('Jesteś pewien że chcesz usunąć tę checklistę? Po tej operacji nie będzie już powrotu!')
                .targetEvent(ev)
                .ok('Usuń')
                .cancel('Jeszcze to przemyślę...')
                .clickOutsideToClose(true);

            this.$mdDialog.show(dialog).then(() => {
                this.checklistService.deleteChecklist(this.checklist.id, +this.$stateParams['id']).then(() => {
                    this.$state.go('app.project.dashboard', {id: this.$stateParams['id']});
                });
            });
        }

        public saveChecklist(): void {
            this.checklistService.saveChecklist(this.checklist).then(() => {
                this.$state.go('app.project.dashboard', {id: this.$stateParams['id']});
            });
        }

        public addItem(): void {
            this.checklist.addItem(this.newItem);
            this.newItem = this.defaultItemValue;
        }

        get newItem(): string {
            return this._newItem;
        }

        set newItem(value: string) {
            this._newItem = value;
        }
    }
}

angular.module('checklist')
    .controller('ChecklistCtrl', ['ChecklistService', 'checklist', '$mdDialog', '$state', '$stateParams', '$location',
        function (ChecklistService, checklist, $mdDialog, $state, $stateParams, $location) {
            return new APP.Checklist.ChecklistCtrl(ChecklistService, checklist, $mdDialog, $state, $stateParams, $location);
        }]);