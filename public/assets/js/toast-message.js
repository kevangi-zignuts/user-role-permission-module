/**
 * Script to handle toast display
 */

$(document).ready(function() {
  // Function to get URL parameter by name
  function getUrlParameter(name) {
      name = name.replace(/[\[]/, '\\[').replace(/[\]]/, '\\]');
      var regex = new RegExp('[\\?&]' + name + '=([^&#]*)');
      var results = regex.exec(location.search);
      return results === null ? '' : decodeURIComponent(results[1].replace(/\+/g, ' '));
  };

  // Check if success parameter exists and is true
  var successParam = getUrlParameter('success');
  if (successParam == '1') {
      var messageParam = getUrlParameter('message');
      var toastAnimationExample = document.querySelector('.toast-ex');
      var selectedType = 'text-success';
      var selectedAnimation = 'animate__tada';
      toastAnimationExample.classList.add(selectedAnimation);
      toastAnimationExample.querySelector('.ti').classList.add(selectedType);
      var Message = document.querySelector('.toast-body');
      Message.innerText = messageParam;
      toastAnimation = new bootstrap.Toast(toastAnimationExample);
      toastAnimation.show();
  }
});
