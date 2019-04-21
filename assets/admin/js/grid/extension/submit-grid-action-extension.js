const $ = window.$;

/**
 * Class SubmitGridActionExtension handles grid action submits
 */
export default class SubmitGridActionExtension {
  constructor() {
    return {
      extend: (grid) => this.extend(grid)
    };
  }

  extend(grid) {
    grid.getContainer().on('click', '.js-grid-action-submit-btn', (event) => {
      this.handleSubmit(event, grid);
    });
  }

  /**
   * Handle grid action submit.
   * It uses grid form to submit actions.
   *
   * @param {Event} event
   * @param {Grid} grid
   *
   * @private
   */
  handleSubmit(event, grid) {
    const $submitBtn = $(event.currentTarget);
    const confirmMessage = $submitBtn.data('confirm-message');

    if (typeof confirmMessage !== "undefined" && 0 < confirmMessage.length && !confirm(confirmMessage)) {
        return;
    }

    const $form = $('#' + grid.getId() + '_filter_form');

    $form.attr('action', $submitBtn.data('url'));
    $form.attr('method', $submitBtn.data('method'));
    $form.find('input[name="' + grid.getId() + '[_token]"]').val($submitBtn.data('csrf'));
    $form.submit();
  }
}
