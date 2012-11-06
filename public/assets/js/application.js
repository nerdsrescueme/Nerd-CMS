(function($) {

  /**
   * If tablecloth has been included, let's try to initialize any table that has
   * been designated to be sortable.
   */
  if ($.fn.tablecloth) {
    $('table.table-sortable').tablecloth({
      sortable: true,
      clean: true,
      striped: true
    })
  }

})(jQuery)