$(document).ready(function () {
  $(document).on('click', '.delete-action', function (e) {
      if (confirm('Are you sure?')) {
          $(this).siblings('form').submit();
      }
      return false;
  });
});