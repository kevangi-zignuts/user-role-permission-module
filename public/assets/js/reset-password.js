document.addEventListener('DOMContentLoaded', function () {
  var links = document.querySelectorAll('a[data-bs-toggle="modal"]');
  links.forEach(function (link) {
    link.addEventListener('click', function (event) {
      event.preventDefault(); // Prevent the default action of the link
      var email = this.getAttribute('data-email');
      var form = document.getElementById('resetPasswordForm');
      if (form) {
        var emailInput = document.getElementById('email');
        if (emailInput) {
          emailInput.value = email;
          var route = this.getAttribute('data-route');
          if (route) {
            form.setAttribute('action', route); // Set form action to the route URL
          } else {
            console.error('Data-route attribute not found on link.');
          }
        } else {
          console.error('Email input not found.');
        }
      } else {
        console.error('Form not found.');
      }
    });
  });
});
