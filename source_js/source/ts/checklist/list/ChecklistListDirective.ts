module APP.Checklist {
    export class ChecklistListDirective {
        public restrict: string = 'E';
        public controller: string = 'ChecklistListCtrl as ctrl';
        public templateUrl: string = '/templates/checklistList.html';

        public static Factory() {
            const directive = () => {
                return new ChecklistListDirective();
            };

            directive['$inject'] = [];

            return directive;
        }
    }
}

angular.module('checklist')
    .directive('checklistList', APP.Checklist.ChecklistListDirective.Factory());