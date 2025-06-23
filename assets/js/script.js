$(document).ready(function () {
    $('#notifiction_icon_header').on('click', function () {
      const $modal = $('#notificationModal');
      $modal.removeClass('hidden');
  
      setTimeout(() => {
        $modal.removeClass('translate-x-full').addClass('translate-x-0');
      }, 10);
    });
  
    $('#closenotificationModalBtn').on('click', function () {
      const $modal = $('#notificationModal');
  
      $modal.removeClass('translate-x-0').addClass('translate-x-full');
  
      setTimeout(() => {
        $modal.addClass('hidden');
      }, 700);
    });
  });
  