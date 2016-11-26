module APP.Checklist {
    import IDialogService = angular.material.IDialogService;
    import IStateService = angular.ui.IStateService;
    import IStateParamsService = angular.ui.IStateParamsService;

    export class ChecklistCtrl {
        public constructor(private checklistService: ChecklistService,
                           private checklist: ChecklistModel,
                           private $mdDialog: IDialogService,
                           private $state: IStateService,
                           private $stateParams: IStateParamsService) {
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
                this.checklistService.deleteChecklist(this.checklist.id).then(() => {
                    this.$state.go('app.project.dashboard', {id: this.$stateParams['id']});
                });
            });
        }
    }
}

angular.module('checklist')
    .controller('ChecklistCtrl', ['ChecklistService', 'checklist', '$mdDialog', '$state', '$stateParams',
        function (ChecklistService, checklist, $mdDialog, $state, $stateParams) {
            return new APP.Checklist.ChecklistCtrl(ChecklistService, checklist, $mdDialog, $state, $stateParams);
        }]);