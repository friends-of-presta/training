import resetSearch from '../utils/reset_search';

const $ = window.$;

/**
 * Class FiltersResetExtension extends grid with filters resetting
 */
export default class FiltersResetExtension {

  /**
   * Extend grid
   *
   * @param {Grid} grid
   */
  extend(grid) {
    grid.getContainer().on('click', '.js-reset-search', (event) => {
      resetSearch($(event.currentTarget).data('url'), $(event.currentTarget).data('redirect'));
    });
  }
}
