const $ = window.$;

/**
 * Class ExportToSqlManagerExtension extends grid with exporting query to SQL Manager
 */
export default class ExportToSqlManagerExtension {
  /**
   * Extend grid
   *
   * @param {Grid} grid
   */
  extend(grid) {
    grid.getContainer().on('click', '.js-common_show_query-grid-action', () => this._onShowSqlQueryClick(grid));
    grid.getContainer().on('click', '.js-common_export_sql_manager-grid-action', () => this._onExportSqlManagerClick(grid));
  }

  /**
   * Invoked when clicking on the "show sql query" toolbar button
   *
   * @param {Grid} grid
   *
   * @private
   */
  _onShowSqlQueryClick(grid) {
    const $sqlManagerForm = $('#' + grid.getId() + '_common_show_query_modal_form');
    this._fillExportForm($sqlManagerForm, grid);

    const $modal = $('#' + grid.getId() + '_grid_common_show_query_modal');
    $modal.modal('show');

    $modal.on('click', '.btn-sql-submit', () => $sqlManagerForm.submit());
  }

  /**
   * Invoked when clicking on the "export to the sql query" toolbar button
   *
   * @param {Grid} grid
   *
   * @private
   */
  _onExportSqlManagerClick(grid) {
    const $sqlManagerForm = $('#' + grid.getId() + '_common_show_query_modal_form');

    this._fillExportForm($sqlManagerForm, grid);

    $sqlManagerForm.submit();
  }

  /**
   * Fill export form with SQL and it's name
   *
   * @param {jQuery} $sqlManagerForm
   * @param {Grid} grid
   *
   * @private
   */
  _fillExportForm($sqlManagerForm, grid) {
    const query = grid.getContainer().find('.js-grid-table').data('query');

    $sqlManagerForm.find('textarea[name="sql"]').val(query);
    $sqlManagerForm.find('input[name="name"]').val(this._getNameFromBreadcrumb());
  }

  /**
   * Get export name from page's breadcrumb
   *
   * @return {String}
   *
   * @private
   */
  _getNameFromBreadcrumb() {
    const $breadcrumbs = $('.header-toolbar').find('.breadcrumb-item');
    let name = '';

    $breadcrumbs.each((i, item) => {
      const $breadcrumb = $(item);

      const breadcrumbTitle = 0 < $breadcrumb.find('a').length ?
        $breadcrumb.find('a').text() :
        $breadcrumb.text();

      if (0 < name.length) {
        name = name.concat(' > ');
      }

      name = name.concat(breadcrumbTitle);
    });

    return name;
  }
}
