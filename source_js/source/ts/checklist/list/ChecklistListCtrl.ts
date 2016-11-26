module APP.Checklist {
    import IStateService = angular.ui.IStateService;
    import IDialogService = angular.material.IDialogService;
    import IToastService = angular.material.IToastService;

    export class ChecklistListCtrl {
        private page = 1;

        public constructor(public checklistService: ChecklistService,
                           private $state: IStateService,
                           private $mdDialog: IDialogService,
                           private $mdToast: IToastService,) {

        }

        public addChecklist(ev: MouseEvent): void {
            let dialog = this.$mdDialog.prompt()
                .title('Stwórz nową checkliste')
                .placeholder('Nazwa checlisty')
                .initialValue('Nowa checklista')
                .targetEvent(ev)
                .ok('Dodaj')
                .cancel('Jednak nie')
                .clickOutsideToClose(true);

            this.$mdDialog.show(dialog).then((result) => {
                if (_.isEmpty(result)) {
                    this.$mdToast.showSimple('Checklista bez nazwy? Nieładnie...');
                    return;
                }

                this.checklistService.createChecklist(result).then(() => {
                    this.page = 1;
                    this.checklistService.loadListOfChecklists(this.page);
                });
            });
        }

        public openChecklist(id: number): void {
            this.$state.go('app.checklist', {id: id});
        }

        public nextPage(): void {
            this.checklistService.loadListOfChecklists(++this.page);
        }

        public prevPage(): void {
            this.checklistService.loadListOfChecklists(--this.page);
        }

        public isPrev(): boolean {
            return this.page > 1;
        }

        public isNext(): boolean {
            return this.checklistService.total > this.page * this.checklistService.limit;
        }
    }
}

angular.module('checklist')
    .controller('ChecklistListCtrl', ['ChecklistService', '$state', '$mdDialog', '$mdToast',
        function (checklistService, $state, $mdDialog, $mdToast) {
            return new APP.Checklist.ChecklistListCtrl(checklistService, $state, $mdDialog, $mdToast);
        }]);