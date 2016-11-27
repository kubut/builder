module APP.Checklist {
    export interface IChecklistItem {
        id?: number;
        name: string;
        solved?: boolean;
    }

    export class ChecklistModel {
        private _id: number;
        private _name: string;
        private _token: string;
        private _items: IChecklistItem[];

        constructor(id: number, token:string, name: string, items: IChecklistItem[]) {
            this._id = id;
            this._name = name;
            this._token = token;
            this._items = items;
        }

        public addItem(name: string): void {
            this.items.push({name: name, solved: false});
        }

        public removeItem(item: {id?: number, name: string, solved?: boolean}) {
            _.remove(this.items, item);
        }

        public getSortedItems(): IChecklistItem[] {
            return _.sortBy(this._items, ['solved']);
        }

        get id(): number {
            return this._id;
        }

        get name(): string {
            return this._name;
        }

        get items(): IChecklistItem[] {
            return this._items;
        }

        get token(): string {
            return this._token;
        }
    }
}