import './css/reorder.css';

jQuery(document).ready(function ($) {
  $('#sortable').sortable();

  $('#reorder-form').on('submit', function (e) {
    e.preventDefault();

    var order = $('#sortable')
      .sortable('toArray')
      .map(function (id) {
        return id.replace('post-', '');
      });

    $.post(
      mapsReorder.ajaxUrl,
      {
        action: 'maps_save_order',
        order: order,
      },
      function (response) {
        alert('Order saved!');
      },
    );
  });
});
