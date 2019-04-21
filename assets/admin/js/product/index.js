import Grid from '../grid/grid';
import FiltersResetExtension from "../grid/extension/filters-reset-extension";
import SortingExtension from "../grid/extension/sorting-extension";
import ReloadListExtension from "../grid/extension/reload-list-extension";
import ExportToSqlManagerExtension from "../grid/extension/export-to-sql-manager-extension";

const $ = window.$;

$(document).ready(() => {
  const productGrid = new Grid('training_products');

  productGrid.addExtension(new FiltersResetExtension());
  productGrid.addExtension(new SortingExtension());
  productGrid.addExtension(new ReloadListExtension());
  productGrid.addExtension(new ExportToSqlManagerExtension());
});