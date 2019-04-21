const $ = window.$;

/**
 * Class LinkRowActionExtension handles link row actions
 */
export default class LinkRowActionExtension {
  /**
   * Extend grid
   *
   * @param {Grid} grid
   */
  extend(grid) {
    grid.getContainer().on('click', '.js-link-row-action', (event) => {
      const confirmMessage = $(event.currentTarget).data('confirm-message');

      if (confirmMessage.length && !confirm(confirmMessage)) {
        event.preventDefault();
      }
    });
  }
}
