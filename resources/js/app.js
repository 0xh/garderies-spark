require('spark-bootstrap');

Vue.filter('date', value => {
    return moment.utc(value).local().format('DD.MM.YYYY');
});
Vue.filter('datetime', value => {
    return moment.utc(value).local().format('DD.MM.YYYY HH:mm');
});

require('./spark-components/bootstrap');

Vue.component('owner-tour', require('./components/OwnerTourComponent'));
Vue.component('director-tour', require('./components/DirectorTourComponent'));
Vue.component('user-availabilities', require('./components/UserAvailabilitiesComponent'));
Vue.component('search-substitute', require('./components/SearchSubstituteComponent'));
Vue.component('availability-edit', require('./components/AvailabilityEditComponent'));
Vue.component('booking-show', require('./components/BookingShowComponent'));
Vue.component('booking-edit', require('./components/BookingEditComponent'));
Vue.component('booking-create', require('./components/BookingCreateComponent'));
Vue.component('booking-request-show', require('./components/BookingRequestShowComponent'));
Vue.component('user-show', require('./components/UserShowComponent'));
Vue.component('user-edit', require('./components/UserEditComponent'));
Vue.component('nursery-show', require('./components/NurseryShowComponent'));
Vue.component('nursery-create', require('./components/NurseryCreateComponent'));
Vue.component('nursery-edit', require('./components/NurseryEditComponent'));
Vue.component('nursery-planning', require('./components/NurseryPlanningComponent'));
Vue.component('nursery-scheduling', require('./components/NurserySchedulingComponent'));
Vue.component('network-show', require('./components/NetworkShowComponent'));
Vue.component('network-create', require('./components/NetworkCreateComponent'));
Vue.component('network-edit', require('./components/NetworkEditComponent'));
Vue.component('notifications', require('./components/NotificationsComponent'));
Vue.component('ad-create', require('./components/AdCreateComponent'));
Vue.component('vue-table', require('./components/VueTable'));

// Initialize bootstrap plugins
$(function () {
    $('[data-toggle="tooltip"]').tooltip();
});

var app = new Vue({
    mixins: [require('spark')]
});

/**
 * Mobile navigation
 */
var fadeSpeed = 200;
$('.hamburger').click(function (e) {
    $(this).toggleClass('is-active');
    $('.nav-mobile').toggleClass('open').toggleClass('closed', !$('.nav-mobile').hasClass('open'));
    $('body').toggleClass('nav-mobile-open');
});
$('.nav-mobile').click(function (e) {
    $('.hamburger').toggleClass('is-active');
    $(this).toggleClass('open').toggleClass('closed', !$('.nav-mobile').hasClass('open'));
    $('body').removeClass('nav-mobile-open');
});