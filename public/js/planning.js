/* Description d'une séance pour son popover */
function setEventDescription(event){
    return event.extendedProps.type_seance + '<br>'
        + event.extendedProps.enseignant + '<br>'
        + event.extendedProps.batiment + ' ' + event.extendedProps.salle + '<br><hr>'
        + event.extendedProps.capacite + ' places' + '<br>'
        + event.extendedProps.nb_postes + ' postes' + '<br>'
        + event.extendedProps.projecteur + ' projecteur';
}

/* Affichage du calendirer sans donnée */
function initCalendar(){
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        plugins: [ 'dayGrid', 'timeGrid', 'list', 'bootstrap' ],
        locale: 'fr',
        height: "parent",
        buttonText: {
            today: 'Aujourd\'hui',
            month: 'Mois',
            week: 'Semaine',
            day: 'Jour',
            list: 'Liste'
        },
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'timeGridWeek,listWeek'
        },
        defaultView : 'timeGridWeek',
        nowIndicator: true,
        allDaySlot: false,
        minTime: "08:00:00",
        maxTime: "21:00:00",
        firstDay: 1,
        hiddenDays: [0],
        timeGridEventMinHeight: 15,
        textEscape: false,
        eventRender: function(info) {
          $(info.el).popover({
              title: info.event.extendedProps.groupe,
              content: setEventDescription(info.event),
              html: true,
              trigger: 'hover',
              container: 'body',
          });
        },
    });

    calendar.render();

    return calendar;
}

/* récupère les séances à a partir de la bdd */
function getEventsFromDB(e, form){
    e.preventDefault();
    $.ajax({
        type: form.attr('method'),
        url: form.attr('action'),
        data: form.serialize(),
        success: function(data)
        {
           var jsonData = JSON.parse(data)
           calendarEvents.remove();
           calendarEvents = calendar.addEventSource(jsonData);
        }
    });
}

document.addEventListener('DOMContentLoaded', function() {

    calendar = initCalendar();
    calendarEvents = calendar.addEventSource([]);

    $("#groupeForm, #enseignantForm").change(function(e) {
        if($(this).is($("#groupeForm"))){
            $("#enseignantPicker").val('default').selectpicker("refresh");
        }
        else {
            $("#groupePicker").val('default').selectpicker("refresh");
        }
        getEventsFromDB(e, $(this))
    });

});
