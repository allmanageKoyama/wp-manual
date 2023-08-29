function updateView(element) {
  $('.p-manual').removeClass('view');
  var to_view = $(element).attr('data-to_view');
  $('#manual-' + to_view).addClass('view');

  // Move to the top of the page immediately after adding the class.
  window.scrollTo(0, 0);
}

$('.p-manual_post__link, .p-manual_header__adminBtn,.p-manual_btn,.p-manual_link').on('click', function () {
  updateView(this);
});