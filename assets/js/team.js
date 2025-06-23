$(document).ready(function () {

    $('#closeTeamProfileBtn').on('click', function () {
        $('#teamProfileModal').addClass('hidden');
        $('#teamAnalyticsModal').removeClass('hidden');
    })

    //display the team profile.
    $(document).on('click', '.teamCard', function () {
        $('#teamAnalyticsModal').addClass('hidden');
        $('#teamProfileModal').removeClass('hidden');
    });
    

})