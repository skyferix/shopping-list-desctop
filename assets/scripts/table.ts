class Table {
    private table: HTMLTableElement;
    private rows: NodeListOf<HTMLTableRowElement>;

    public constructor(table: HTMLTableElement) {
        this.table = table;
        this.rows = table.querySelectorAll('tr[data-id]');
        this.setRowListeners(this.rows);
    }

    private setRowListeners(rows: NodeListOf<HTMLTableRowElement>) {
        rows.forEach((row) => row.addEventListener('click', this.setRowListener))
    }

    private setRowListener(ev: Event & { target: HTMLTableRowElement }) {
        const target = ev.target;
        const tr = target.closest('tr');
        const id = tr.dataset.id;

        window.location.href = `/user/${id}`;
    }
}

const table = document.querySelector('table');
if (table) {
    new Table(table);
}