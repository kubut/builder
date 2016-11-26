module APP.Checklist {
    import IStateService = angular.ui.IStateService;

    export class ChecklistListCtrl {
        private page = 1;

        public constructor(public checklistService: ChecklistService, private $state: IStateService) {

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
    .controller('ChecklistListCtrl', ['ChecklistService', '$state', function (checklistService, $state) {
        return new APP.Checklist.ChecklistListCtrl(checklistService, $state);
    }]);