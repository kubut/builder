module APP.Checklist {
    export class ChecklistModel {
        private _id: number;
        private _name: string;
        private _items: {id?: number, name: string, solved?: boolean}[];

        constructor(id: number, name: string, items: {id: number, name: string; solved?: boolean}[]) {
            this._id = id;
            this._name = name;
            this._items = items;
        }

        public addItem(name: string): void {
            this.items.push({name: name, solved: false});
        }

        public removeItem(item: {id?:number, name: string, solved?: boolean}) {
            _.remove(this.items, item);
        }

        get id(): number {
            return this._id;
        }

        get name(): string {
            return this._name;
        }

        get items(): {name: string; solved?: boolean}[] {
            return this._items;
        }
    }
}