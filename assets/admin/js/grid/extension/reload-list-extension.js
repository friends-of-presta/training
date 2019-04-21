/**
 * Class ReloadListExtension extends grid with "List reload" action
 */
export default class ReloadListExtension {
  /**
   * Extend grid
   *
   * @param {Grid} grid
   */
  extend(grid) {
    grid.getContainer().on('click', '.js-common_refresh_list-grid-action', () => {
      location.reload();
    });
  }
}
