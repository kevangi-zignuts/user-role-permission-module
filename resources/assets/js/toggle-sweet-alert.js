/**
 * DataTables Basic
 */

document.addEventListener("DOMContentLoaded", function () {
  toggleSwitches = document.querySelectorAll('.toggle-class');
  toggleSwitches.forEach(function (toggleSwitch) {
    toggleSwitch.addEventListener('click', function () {
      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, change it!',
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.isConfirmed) {
          var status = $(toggleSwitch).prop('checked') == true ? 1 : 0;
          var route = $(toggleSwitch).data('route');
          $.ajax({
            type: "GET",
            dataType: "json",
            url: route,
            success: function (data) {
              if (data.success) {
                Swal.fire({
                  icon: 'success',
                  title: 'Status Updated!!',
                  text: data.message,
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              }
            }
          });
        } else {
          var currentState = $(toggleSwitch).prop('checked');
          $(toggleSwitch).prop('checked', !currentState);
        }
      });
    });
  });


  deleteButtons = document.querySelectorAll('.delete-class');
  deleteButtons.forEach(function (deleteButton) {
    deleteButton.addEventListener('click', function () {
      var row = this.closest('tr');

      Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, delete it!',
        customClass: {
          confirmButton: 'btn btn-primary me-3',
          cancelButton: 'btn btn-label-secondary'
        },
        buttonsStyling: false
      }).then(function (result) {
        if (result.isConfirmed) {
          var route = $(deleteButton).data('route');
          $.ajax({
            type: "GET",
            dataType: "json",
            url: route,
            success: function (data) {
              if (data.success) {
                row.remove();
                Swal.fire({
                  icon: 'success',
                  title: 'Data Deleted!!',
                  text: data.message,
                  customClass: {
                    confirmButton: 'btn btn-success'
                  }
                });
              }
            }
          });
        }
      });
    })
  });
});

