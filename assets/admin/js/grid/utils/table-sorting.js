const $ = global.$;

/**
 * Makes a table sortable by columns.
 * This forces a page reload with more query parameters.
 */
class TableSorting {

    /**
     * @param {jQuery} table
     */
    constructor(table) {
        this.selector = '.ps-sortable-column';
        this.columns = $(table).find(this.selector);
    }

    /**
     * Attaches the listeners
     */
    attach() {
        this.columns.on('click', (e) => {
            const $column = $(e.delegateTarget);
            this._sortByColumn($column, this._getToggledSortDirection($column));
        });
    }

    /**
     * Sort using a column name
     * @param {string} columnName
     * @param {string} direction "asc" or "desc"
     */
    sortBy(columnName, direction) {
        const $column = this.columns.is(`[data-sort-col-name="${columnName}"]`);
        if (!$column) {
            throw new Error(`Cannot sort by "${columnName}": invalid column`);
        }

        this._sortByColumn($column, direction);
    }

    /**
     * Sort using a column element
     * @param {jQuery} column
     * @param {string} direction "asc" or "desc"
     * @private
     */
    _sortByColumn(column, direction) {
        window.location = this._getUrl(column.data('sortColName'), (direction === 'desc') ? 'desc' : 'asc');
    }

    /**
     * Returns the inverted direction to sort according to the column's current one
     * @param {jQuery} column
     * @return {string}
     * @private
     */
    _getToggledSortDirection(column) {
        return column.data('sortDirection') === 'asc' ? 'desc' : 'asc';
    }

    /**
     * Returns the url for the sorted table
     * @param {string} colName
     * @param {string} direction
     * @return {string}
     * @private
     */
    _getUrl(colName, direction) {
        const url = new URL(window.location.href);
        const params = url.searchParams;

        params.set('orderBy', colName);
        params.set('sortOrder', direction);

        return url.toString();
    }
}

export default TableSorting;