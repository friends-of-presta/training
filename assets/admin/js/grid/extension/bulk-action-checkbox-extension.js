const $ = window.$;

/**
 * Class BulkActionSelectCheckboxExtension
 */
export default class BulkActionCheckboxExtension {
  /**
   * Extend grid with bulk action checkboxes handling functionality
   *
   * @param {Grid} grid
   */
  extend(grid) {
    this._handleBulkActionCheckboxSelect(grid);
    this._handleBulkActionSelectAllCheckbox(grid);
  }

  /**
   * Handles "Select all" button in the grid
   *
   * @param {Grid} grid
   *
   * @private
   */
  _handleBulkActionSelectAllCheckbox(grid) {
    grid.getContainer().on('change', '.js-bulk-action-select-all', (e) => {
      const $checkbox = $(e.currentTarget);

      const isChecked = $checkbox.is(':checked');
      if (isChecked) {
        this._enableBulkActionsBtn(grid);
      } else {
        this._disableBulkActionsBtn(grid);
      }

      grid.getContainer().find('.js-bulk-action-checkbox').prop('checked', isChecked);
    });
  }

  /**
   * Handles each bulk action checkbox select in the grid
   *
   * @param {Grid} grid
   *
   * @private
   */
  _handleBulkActionCheckboxSelect(grid) {
    grid.getContainer().on('change', '.js-bulk-action-checkbox', () => {
      const checkedRowsCount = grid.getContainer().find('.js-bulk-action-checkbox:checked').length;

      if (checkedRowsCount > 0) {
        this._enableBulkActionsBtn(grid);
      } else {
        this._disableBulkActionsBtn(grid);
      }
    });
  }

  /**
   * Enable bulk actions button
   *
   * @param {Grid} grid
   *
   * @private
   */
  _enableBulkActionsBtn(grid) {
    grid.getContainer().find('.js-bulk-actions-btn').prop('disabled', false);
  }

  /**
   * Disable bulk actions button
   *
   * @param {Grid} grid
   *
   * @private
   */
  _disableBulkActionsBtn(grid) {
    grid.getContainer().find('.js-bulk-actions-btn').prop('disabled', true);
  }
}
