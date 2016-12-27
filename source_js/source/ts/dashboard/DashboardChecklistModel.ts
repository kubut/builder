module APP.Dashboard {
    export class DashboardChecklistModel implements IDashboardChecklist {
        public constructor(public id: number, public name: string, public items: DashboardChecklistItem[]) {

        }

        public getSolvedCount(): number {
            let solved = _.filter(this.items, {solved: true});
            return solved ? solved.length : 0;
        }

        public reorderItems(): void {
            this.items = _.sortBy(this.items, ['solved']);
        }
    }

    export interface IDashboardChecklist {
        id: number;
        name: string;
        items: DashboardChecklistItem[];
    }

    export type DashboardChecklistItem = {
        id: number,
        name: string,
        solved: boolean
    }
}