$(document).ready(function () {
  $(document).on('click', '.delete-action', function (e) {
      if (confirm('Are you sure you want to delete?')) {
          $(this).siblings('form').submit();
      }
      return false;
  });

  $(document).on('click', '[data-toggle="popover"]', function() { $(this).popover('show'); });
});

function select_all(click_btn, select_check) {
  $(document).on('click', click_btn, function() {
      $('.fa', this).toggleClass("fa-check-square fa-square");
      if ( $('.fa', this).hasClass('fa-check-square') ) {
        $(select_check).prop('checked', true)
      } else {
        $(select_check).prop('checked', false)
      }
  });
}
function select_all_checkbox(chceck, p_div) {
  $(p_div + ' input:checkbox').prop('checked', chceck.checked);
}



function copyToClipboard(element) {
  var $temp = $("<textarea>");
  $("body").append($temp);
  $temp.val( $(element).text().trim() ).select();
  document.execCommand("copy");
  $temp.remove();
}