
var event_data = {
    "events": [
    {
        "occasion": " Repeated Test Event ",
        "invited_count": 120,
        "year": 2020,
        "month": 7,
        "day": 16,
        "cancelled": true
    },
    {
        "occasion": " Repeated Test Event ",
        "invited_count": 120,
        "year": 2020,
        "month": 5,
        "day": 10,
        "cancelled": true
    },
        {
        "occasion": " Repeated Test Event ",
        "invited_count": 120,
        "year": 2020,
        "month": 5,
        "day": 10,
        "cancelled": true
    },
    {
        "occasion": " Repeated Test Event ",
        "invited_count": 120,
        "year": 2020,
        "month": 5,
        "day": 10
    },
    ]
};

var unfinished_event = {
    "ongoing" : [
    ]
}

function new_event_json(name, count, date, curr_date, day, note, proof, id) {
    var event = {
        "occasion": name,
        "invited_count": count,
        "year": date.getFullYear(),
        "month": date.getMonth()+1,
        "day": parseInt(day),
        "note": note,
        "proof": proof,
        "id": id,
    };
    event_data["events"].push(event);
    date.setDate(day);
    init_calendar(curr_date);
}


function new_unfinished_event_json(name, date, day, note, proof, id){
    var event = {
        "occasion": name,
        "year": date.getFullYear(),
        "month": date.getMonth()+1,
        "day": parseInt(day),
        "note": note,
        "proof": proof,
        "id": id,
    };

    unfinished_event["ongoing"].push(event);
}

"use strict";

	// Setup the calendar with the current date
$(document).ready(function(){
    var date = new Date();
    var today = date.getDate();
    // Set click handlers for DOM elements
    $(".right-button").click({date: date}, next_year);
    $(".left-button").click({date: date}, prev_year);
    $(".month").click({date: date}, month_click);
    $("#add-button").click({date: date}, new_event);
    // Set current month as active
    $(".months-row").children().eq(date.getMonth()).addClass("active-month");
    init_calendar(date);
    var events = check_events(today, date.getMonth()+1, date.getFullYear());
    show_events(events, months[date.getMonth()], today);
});

// Initialize the calendar by appending the HTML dates
function init_calendar(date) {
    $(".tbody").empty();
    $(".events-container").empty();
    var calendar_days = $(".tbody");
    var month = date.getMonth();
    var year = date.getFullYear();
    var day_count = days_in_month(month, year);
    var row = $("<tr class='table-row'></tr>");
    var today = date.getDate();
    // Set date to 1 to find the first day of the month
    date.setDate(1);
    var first_day = date.getDay();
    // 35+firstDay is the number of date elements to be added to the dates table
    // 35 is from (7 days in a week) * (up to 5 rows of dates in a month)
    for(var i=0; i<35+first_day; i++) {
        // Since some of the elements will be blank, 
        // need to calculate actual date from index
        var day = i-first_day+1;
        // If it is a sunday, make a new row
        if(i%7===0) {
            calendar_days.append(row);
            row = $("<tr class='table-row'></tr>");
        }
        // if current index isn't a day in this month, make it blank
        if(i < first_day || day > day_count) {
            var curr_date = $("<td class='table-date nil'>"+"</td>");
            row.append(curr_date);
        }   
        else {
            var curr_date = $("<td class='table-date'>"+day+"</td>");
            var events = check_events(day, month+1, year);
            if(today===day && $(".active-date").length===0) {
                curr_date.addClass("active-date");
                show_events(events, months[month], day);
            }
            // If this date has any events, style it with .event-date
            if(events.length!==0) {
                curr_date.addClass("event-date");
            }
            // Set onClick handler for clicking a date
            curr_date.click({events: events, month: months[month], day:day}, date_click);
            row.append(curr_date);
        }
    }
    // Append the last row and set the current year
    calendar_days.append(row);
    $(".year").text(year);
}

// Get the number of days in a given month/year
function days_in_month(month, year) {
    var monthStart = new Date(year, month, 1);
    var monthEnd = new Date(year, month + 1, 1);
    return (monthEnd - monthStart) / (1000 * 60 * 60 * 24);    
}

// Event handler for when a date is clicked
function date_click(event) {
    $(".events-container").show(250);
    $("#dialog").hide(250);
    $(".active-date").removeClass("active-date");
    $(this).addClass("active-date");
    show_events(event.data.events, event.data.month, event.data.day);
};

// Event handler for when a month is clicked
function month_click(event) {
    $(".events-container").show(250);
    $("#dialog").hide(250);
    var date = event.data.date;
    $(".active-month").removeClass("active-month");
    $(this).addClass("active-month");
    var new_month = $(".month").index(this);
    date.setMonth(new_month);
    init_calendar(date);
}

// Event handler for when the year right-button is clicked
function next_year(event) {
    $("#dialog").hide(250);
    var date = event.data.date;
    var new_year = date.getFullYear()+1;
    $("year").html(new_year);
    date.setFullYear(new_year);
    init_calendar(date);
}

// Event handler for when the year left-button is clicked
function prev_year(event) {
    $("#dialog").hide(250);
    var date = event.data.date;
    var new_year = date.getFullYear()-1;
    $("year").html(new_year);
    date.setFullYear(new_year);
    init_calendar(date);
}

// Event handler for clicking the new event button
function new_event(event) {
    // if a date isn't selected then do nothing
    if($(".active-date").length===0)
        return;
    // remove red error input on click
    $("input").click(function(){
        $(this).removeClass("error-input");
    })
    // empty inputs and hide events
    $("#dialog input[type=text]").val('');
    $("#dialog input[type=number]").val('');
    $(".events-container").hide(250);
    $("#dialog").show(250);
    // Event handler for cancel button
    $("#cancel-button").click(function() {
        $("#name").removeClass("error-input");
        $("#count").removeClass("error-input");
        $("#dialog").hide(250);
        $(".events-container").show(250);
    });
    // Event handler for ok button
    $("#ok-button").unbind().click({date: event.data.date}, function() {
        var date = event.data.date;
        var name = $("#name").val().trim();
        var count = parseInt($("#count").val().trim());
        var day = parseInt($(".active-date").html());
        // Basic form validation
        if(name.length === 0) {
            $("#name").addClass("error-input");
        }
        else if(isNaN(count)) {
            $("#count").addClass("error-input");
        }
        else {
            $("#dialog").hide(250);
            console.log("new event");
            new_event_json(name, count, date, day);
            date.setDate(day);
            init_calendar(date);
        }
    });
}

// Adds a json event to event_data
//  ini di pakai di ajax




// Display all events of the selected date in card views
function show_events(events, month, day) {
    // Clear the dates container
    $(".events-container").empty();
    $(".events-container").show(250);


        // Go through and add each event as a card to the events container
    var event_card = $(`<div class="event-card"></div>`);
    var event_navtab = $(`
    <ul class='nav nav-tabs nav-tabs-solid nav-justified border-0'>
        <li class='nav-item'>
            <a href='#today' class='nav-link active' data-toggle='tab'>`+month+` `+day+` Trip</a>
        </li>
        <li class='nav-item'>
            <a href='#unfinished' class='nav-link' data-toggle='tab'>Unfinished</a>
        </li>
    </ul>`);
    var event_tab_content = $(`  
    <div class='tab-content'>
    </div>`);
    var event_navtab_content_today = $(`
    <div class='tab-pane fade show active' id='today'>  
    </div>`);

    var event_navtab_content_ongoing = $(`
    <div class='tab-pane fade' id='unfinished'>
    </div>`);

    var event_accordion_today ='';
    var event_accordion_ongoing =''
    // console.log(event_data["events"]);
    // If there are no events for this date, notify the user
    if(events.length===0) {
        event_accordion_today += `
        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingTwo">
                    <h5 class="mb-0">
                        <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNoData" aria-expanded="false" aria-controls="collapseNoData">
                            `+month+" "+day+` No Data
                        </button>
                    </h5>
                </div>
                <div id="collapseNoData" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                    <div class="card-body">
                        `+month+" "+day+` No Data
                    </div>
                </div>
            </div>
        </div>`;

        if(unfinished_event["ongoing"].length > 0){
            for (var i = 0; i < unfinished_event["ongoing"].length; i++) {
               event_accordion_ongoing += `
               <div id="accordion">
                   <div class="card">
                       <div class="card-header" id="headingTwo">
                           <h5 class="mb-0">
                               <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse`+unfinished_event["ongoing"][i]["id"]+`" aria-expanded="false" aria-controls="collapse`+unfinished_event["ongoing"][i]["id"]+`">
                               `+unfinished_event["ongoing"][i]["occasion"]+`
                               </button>
                           </h5>
                       </div>
                       <div id="collapse`+unfinished_event["ongoing"][i]["id"]+`" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                           <div class="card-body">
                                <div class="row">
                                    <p class="col-10">`+unfinished_event["ongoing"][i]["note"]+`</p>
                                    <a href="`+unfinished_event["ongoing"][i]["proof"]+`" class="btn bg-info col-auto" target="_blank"><i class="icon-search4"></i></a>
                                </div>
                           </div>
                       </div>
                   </div>
               </div>`;
               
           }
        }else{
           event_accordion_ongoing += `
               <div id="accordion">
                   <div class="card">
                       <div class="card-header" id="headingTwo">
                           <h5 class="mb-0">
                               <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNoData" aria-expanded="false" aria-controls="collapseNoData">
                                   No Data
                               </button>
                           </h5>
                       </div>
                       <div id="collapseNoData" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                           <div class="card-body">
                                   No Data
                           </div>
                       </div>
                   </div>
               </div>`;
        }
    }else {
        for(var i=0; i<events.length; i++) {
            event_accordion_today += `
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse`+events[i]["id"]+`" aria-expanded="false" aria-controls="collapse`+events[i]["id"]+`">
                              `+events[i]["occasion"]+`
                                </button>

                               
                            </h5>
                    </div>
                    <div id="collapse`+events[i]["id"]+`" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                           <div class="row">
                            <p class="col-10">`+events[i]["note"]+`</p>
                            <a href="`+events[i]["proof"]+`" class="btn bg-info col-auto" target="_blank"><i class="icon-search4"></i></a>
                           </div>
                        </div>
                    </div>
                </div>
            </div>`;
        }

        if(unfinished_event["ongoing"].length > 0){
         for (var i = 0; i < unfinished_event["ongoing"].length; i++) {
            event_accordion_ongoing += `
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapse`+unfinished_event["ongoing"][i]["id"]+`" aria-expanded="false" aria-controls="collapse`+unfinished_event["ongoing"][i]["id"]+`">
                            `+unfinished_event["ongoing"][i]["occasion"]+`
                            </button>
                        </h5>
                    </div>
                    <div id="collapse`+unfinished_event["ongoing"][i]["id"]+`" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                            <div class="row">
                                <p class="col-10">`+unfinished_event["ongoing"][i]["note"]+`</p>
                                <a href="`+unfinished_event["ongoing"][i]["proof"]+`" class="btn bg-info col-auto" target="_blank"><i class="icon-search4"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            
        }
        }else{
        event_accordion_ongoing += `
            <div id="accordion">
                <div class="card">
                    <div class="card-header" id="headingTwo">
                        <h5 class="mb-0">
                            <button class="btn btn-link collapsed" data-toggle="collapse" data-target="#collapseNoData" aria-expanded="false" aria-controls="collapseNoData">
                                No Data
                            </button>
                        </h5>
                    </div>
                    <div id="collapseNoData" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                        <div class="card-body">
                                No Data
                        </div>
                    </div>
                </div>
            </div>`;
        }

    }

    var content_today = $(event_navtab_content_today).append(event_accordion_today);
    var content_ongoing = $(event_navtab_content_ongoing).append(event_accordion_ongoing);
    
    content_today.appendTo(event_tab_content);
    content_ongoing.appendTo(event_tab_content);


    $(event_card).append(event_navtab).append(event_tab_content);
    $(".events-container").append(event_card);
}

// Checks if a specific date has any events
function check_events(day, month, year) {
    var events = [];
    for(var i=0; i<event_data["events"].length; i++) {
        var event = event_data["events"][i];
        if(event["day"]===day &&
            event["month"]===month &&
            event["year"]===year) {
                events.push(event);
            }
    }
    return events;
}

// Given data for events in JSON format





const months = [ 
    "January", 
    "February", 
    "March", 
    "April", 
    "May", 
    "June", 
    "July", 
    "August", 
    "September", 
    "October", 
    "November", 
    "December" 
];




