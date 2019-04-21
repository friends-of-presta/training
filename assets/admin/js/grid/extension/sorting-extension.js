import TableSorting from '../utils/table-sorting';

/**
 * Class ReloadListExtension extends grid with "List reload" action
 */
export default class SortingExtension {
  /**
   * Extend grid
   *
   * @param {Grid} grid
   */
  extend(grid) {
    const $sortableTable = grid.getContainer().find('table.table');

    new TableSorting($sortableTable).attach();
  }
}
