$(function ()
{
    $('#calendar-holder').fullCalendar(
        {
            locale: 'fr',
            header:
                {
                    left: 'prev, next, today',
                    center: 'title',
                    right: 'month, agendaWeek, agendaDay'
                },
            businessHours:
                {
                    start: '09:00',
                    end: '18:00',
                    dow: [1, 2, 3, 4, 5]
                },
            defaultView: 'month',
            lazyFetching: true,
            navLinks: true,
            selectable: true,
            editable: true,
            eventDurationEditable: true,
            // events: '/box-of-heroes/public/booking/getBookings',
            eventSources:
                [
                    {
                        url: $('#calendar-holder').data('eventsUrl'),
                        color: '#ef9857',
                        textColor: 'black',
                        // type: 'GET',
                        // data: {
                        //     filters: {}
                        // },
                        // error: function () {
                        //     // alert('There was an error while fetching FullCalendar!');
                        // }
                    }
                ]
        });
});