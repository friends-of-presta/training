const $ = window.$;

/**
 * Class is responsible for handling Grid events
 */
export default class Grid {
  /**
   * Grid id
   *
   * @param {string} id
   */
  constructor(id) {
    this.id = id;
    this.$container = $('#' + this.id + '_grid');
  }

  /**
   * Get grid id
   *
   * @returns {string}
   */
  getId() {
    return this.id;
  }

  /**
   * Get grid container
   *
   * @returns {jQuery}
   */
  getContainer() {
    return this.$container;
  }

  /**
   * Extend grid with external extensions
   *
   * @param {object} extension
   */
  addExtension(extension) {
    extension.extend(this);
  }
}
