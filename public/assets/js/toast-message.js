/**
 * Script to handle toast display
 */

$(document).ready(function () {
  // Function to get URL parameter by name

  function getUrlParameter(name) {
    var regex = new RegExp('[?&]' + name + '=([^&#]*)');
    var results = regex.exec(location.search);
    return results ? decodeURIComponent(results[1].replace(/\+/g, ' ')) : '';
  }

  if (getUrlParameter('success') == '1') {
    $('.toast-body').text(getUrlParameter('message'));
    new bootstrap.Toast($('.toast-ex')[0]).show();
  }

  if (getUrlParameter('error') == '1') {
    $('.toast-body-error').text(getUrlParameter('message'));
    new bootstrap.Toast($('.error-message')[0]).show();
  }
});
