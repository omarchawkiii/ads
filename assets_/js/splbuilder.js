$('#location').change(function(){

    var box = "";
    var box_kind = "";
    var detail = "";

    var loader_content  =
    '<div class="jumping-dots-loader">'
        +'<span></span>'
        +'<span></span>'
        +'<span></span>'
        +'</div>'
    $('#dragula-left').html(loader_content)


    var  location =  $('#location').val();

    var url = "{{  url('') }}"+ '/get_cpl_with_filter_for_noc/?location=' + location+'&lms=true&playlist_builder=true' ;

    $.ajax({
        url: url,
        method: 'GET',
        success:function(response)
        {
            $.each(response.cpls, function( index, value ) {

                if (value.contentKind.localeCompare(box_kind) == 0) {
                        box_kind = value.contentKind;
                    } else {
                        box_kind = value.contentKind;
                        box += '<div class=" filtered  div_list title-kind  "   ' +
                            '        data-type="' + value.contentKind + '">' + value.contentKind + '  </div>';
                    }
                    if (value.contentKind.match(/^(SPL|Automation|Trigger|Cues|Pattern)$/)) {

                    }
                    box +=
                        '   <div class="card rounded border mb-2 left-side-item"' +
                        '       data-side="left"   ' +
                        '       data-auditorium="' + value.id_auditorium + '" ' +
                        '       data-uuid="' + value.uuid + '"' +
                        '       data-title="' + value.contentTitleText + '"' +
                        '       data-time="' + value.Duration + '"  ' +
                        '       data-time_seconds="' + value.duration_seconds + '"  ' +
                        '       data-time_Duration_frames="' + value.durationEdits + '"  ' +
                        '       data-type_component="cpl"' +
                        '       data-id="' + value.id_dcp + '"' +
                        '       data-version="left_tab"' +
                        '       data-type="' + value.contentKind + '"' +
                        '       data-editRate_denominator="' + value.editRate_denominator + '"' +
                        '       data-editRate_numerator="' + value.editRate_numerator + '"' +
                        '       data-id_server="' + value.id_server + '"' +
                        '       data-source="' + value.source + '"' +
                        '       data-need_kdm="' + ((value.pictureEncryptionAlgorithm == "None" || value.pictureEncryptionAlgorithm == 0) ? 0 : 1) + '"' +
                        '> ' +
                        '       <div class="card-body  "  >\n' +

                        '                 <div class="media-body  ">\n' +
                        '                      <h6 class="mb-1"  style="font-size: 17px;color:' +
                        (value.type === "Flat" ? "#52d4f7" :
                            (value.type === "Scope" ? "#00d25b" : "white"))
                        + '">' + value.contentTitleText +
                        ((value.pictureEncryptionAlgorithm == "None" || value.pictureEncryptionAlgorithm == 0) ? " " : "<i class=\"mdi mdi-lock-outline  cpl_need_kdm\" aria-hidden=\"true\"></i>") +
                        '</h6>\n' +
                        '                  </div>\n' +
                        '                  <div class="media-body">\n' +
                        '                       <p class="mb-0 text-muted float-left">' + formatDurationToHMS(value.duration_seconds) + ' <span class="detail-cpl">  Subtitle, VI, HI, DBox </span>   </p>\n' +
                        '                       <p class="mb-0 text-muted float-right">\n' +
                        '                          <span class="icon-prop-cpl">' +
                        (value.is_3D == 1 ? '3D' : '2D') +
                        '                          </span>\n' +
                        '                          <span class="flat">  ' +
                        (value.ScreenAspectRatio == "unknown" ? value.type
                            : value.ScreenAspectRatio + ' ' + value.cinema_DCP) +
                        '                           </span>\n' +
                        '                          <span class="flat">' + value.soundChannelCount + ' </span>\n' +
                        '                          <span class="flat"> ST  </span>\n' +
                        '                          <span data-bs-toggle="modal" data-bs-target="#infos_modal" class=" infos_modal" href="#" ' +
                        '                                data-uuid="' + value.uuid + '"' +
                        '                                 id="' + value.id + '"' +
                        '                                data-need_kdm="' + ((value.pictureEncryptionAlgorithm == "None" || value.pictureEncryptionAlgorithm == 0) ? 0 : 1) + '"' +
                        '                            > <i class="btn btn-primary  custom-search mdi mdi-magnify"> </i></span>\n' +
                        '                       </p>\n' +
                        '                    </div> ' +
                        '              </div>\n' +
                        '              <div class="card-body macro-list hide_div"></div> ' +
                        '              <div class="card-body marker-list hide_div"></div>' +

                        '   </div>';
            });
            // append pattern
            box += '<div class=" filtered  div_list   title-kind  " data-type="Pattern"> Pattern   </div>';
            box += '' +
                '<div class="list-group-item div_list card rounded border mb-2 left-side-item" ' +
                '         data-side="left" ' +
                '         data-type="Pattern" ' +
                '         data-version="left_tab"' +
                '         data-title="Black"  >' +
                '        <span></span>' +
                '        <div class="title-content"> ' +
                '          Black <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"  ></i>' +
                '        </div>' +
                '        <div class="card-body macro-list hide_div"></div> ' +
                '</div>';
            box +=
                '<div class="list-group-item div_list card rounded border mb-2 left-side-item " ' +
                '         data-side="left" ' +
                '         data-type="Pattern" ' +
                '         data-version="left_tab"' +
                '         data-title="Black 3D">' +
                '      <div class="title-content"> ' +
                '            <span class="icon_pattern">3D</span>' +
                '            Black 3D <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"  ></i>' +
                '      </div>' +
                '      <div class="card-body macro-list hide_div"></div>' +
                '</div>';
            box +=
                '<div class="list-group-item div_list card rounded border mb-2 left-side-item" ' +
                '         data-side="left" ' +
                '         data-type="Pattern" ' +
                '         data-version="left_tab"' +
                '         data-title="Black 3D 48"  >' +
                '   <div class="title-content"> ' +
                '       <span class="icon_pattern">3D</span>' +
                '       Black 3D 48 <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"  ></i>' +
                '   </div>' +
                '   <div class="card-body macro-list hide_div"></div>' +
                '</div>';
            // append macros
            box += '<div class=" filtered  div_list title_kind title-kind" data-type="Macros"> Macros  </div>';
                $.each(response.macros, function( index, value ) {
                    box +=
                    '<div  style="padding:5px" class="macro_item   div_list card rounded border mb-2 left-side-item" ' +
                    '       data-command="' +value.command + '" data-title="' +value.title + '" data-id="' +value.idmacro_config + '" data-type="Macros">' +
                    '       <div class="card-body p-3">\n' +
                    '                 <div class="media-body float-left">\n' +
                    '                    <div class="title-content col-md-12"> <i class="btn btn-inverse-primary  mdi mdi-server-network"></i> ' +value.title + ' </div>' +
                    '                       <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"></i>' +
                    '                 </div>' +
                    '        </div>' +
                    '</div>';
                });

            $('#dragula-left').html(box);
        },
        error: function(response) {

        }
    })
});


// filter files by kind
$(document).on('change', '#filter_type', function (event) {
    var criteria = $(this).val();

    if (criteria == 'all') {
        $('.left-side-item').show();
        $('.title-kind').show();
        return;
    }
    $('#dragula-left .left-side-item').each(function (i, option) {
        if ($(this).data("type") == criteria) {

            $(this).show();
        } else {
            $(this).hide();
        }
    });
    $('#dragula-left .title-kind').each(function (i, option) {
        if ($(this).data("type") == criteria) {

            $(this).show();
        } else {
            $(this).hide();
        }
    });
});

// search  content left side
var search_content = document.getElementById('search-dragula-left');

search_content.onkeyup = function () {
    var searchTerms = $(this).val();
    $('#dragula-left .left-side-item ').each(function () {

        var hasMatch = searchTerms.length == 0 ||
            $(this).text().toLowerCase().indexOf(searchTerms.toLowerCase()) > 0;
        $(this).toggle(hasMatch);

    });
}

// display cpl details

// get cpl details left/right sides
$(document).on('click', '#dragula-left .cpl-details', function () {
    let id_cpl = $(this).data("uuid");
    let need_kdm = $(this).data("need_kdm");
    getCplDetails(id_cpl, need_kdm);
});
$(document).on('click', '#dragula-right .cpl-details', function () {
    let id_cpl = $(this).data("uuid");

    getCplDetails(id_cpl, 0);
});
//  *****************************   macros
$(document).on('click', '.macro-details', function () {


    let macro_uuid = this.closest('.macro-box').getAttribute("data-uuid");
    let data_hours = this.closest('.macro-box').getAttribute("data-hours");
    let data_minutes = this.closest('.macro-box').getAttribute("data-minutes");
    let data_seconds = this.closest('.macro-box').getAttribute("data-seconds");
    let data_time = this.closest('.macro-box').getAttribute("data-time");
    let title = this.closest('.macro-box').getAttribute("data-title");
    let offset = this.closest('.macro-box').getAttribute("data-offset");
    let command = this.closest('.macro-box').getAttribute("data-command");
    let duration_seconds = this.closest('.macro-box').getAttribute("data-time_seconds");
    // get uuid parent cpl/pattern
    var leftSideItem = $(this).closest('.left-side-item');
    var macro_box = $(this).closest('.macro-box');
    var parent_uuid = leftSideItem.data('uuid');
    var parent_title = leftSideItem.data('title');
    var parent_duration = leftSideItem.data('time_seconds');
    if (offset == "End") {

        $('#edit_macro_time_hms').html(convertSecondsToHMS(parent_duration - duration_seconds));
    } else {
        $('#edit_macro_time_hms').html(convertSecondsToHMS(duration_seconds));
    }
    $("#edit_macro_parent_uuid").html(parent_uuid);
    $("#edit_parent_title").html(parent_title);
    $("#edit_parent_macro_duration").val(parent_duration);
    $("#edit_macro_uuid").val(macro_uuid);
    $("#edit_macro_title").html(title);
    $("#edit_macro_title_val").val(title);
    $("#edit_macro_command").val(command);

    $("#edit_Hours_macro").val(parseInt(data_hours));
    $("#edit_Minutes_macro").val(parseInt(data_minutes));
    $("#edit_Seconds_macro").val(parseInt(data_seconds));

    if (offset === "Start") {
        $("#edit_start_macro").prop("checked", true);
    } else if (offset === "End") {
        $("#edit_end_macro").prop("checked", true);
    }
    $("#edit-macro-modal").modal('show');
});
$(document).on('click', '#confirm_add_macro', function () {
    var macro_command = $('#macro_command').val();
    var macro_title = $('#macro_title_val').val();
    var Hours_macro = $('#Hours_macro').val();
    var Minutes_macro = $('#Minutes_macro').val();
    var Seconds_macro = $('#Seconds_macro').val();
    var offset = $('input[name=Offset_macro]:checked').val();
    var time_macro = Hours_macro + ' : ' + Minutes_macro + ' : ' + Seconds_macro;
    var time_seconds = parseInt(Hours_macro) * 60 * 60 + parseInt(Minutes_macro) * 60 + parseInt(Seconds_macro);
    var macro_box = '' +
        '<div class="media-body macro-box col-md-8" data-title="' + macro_title + '"' +
        '      data-offset="' + offset + '" ' +
        '      data-command="' + $.trim(macro_command) + '" ' +
        '      data-time="' + convertSecondsToHMS(time_seconds) + '"  data-time_seconds="' + time_seconds + '"' +
        '      data-Hours="' + Hours_macro + '" ' + 'data-Minutes="' + Minutes_macro + '" ' + 'data-Seconds="' + Seconds_macro + '" ' +
        '      data-Uuid="urn:uuid:' + uuidv4() + '">' +
        macro_title +
        '  <p class="mb-0 text-muted float-left"> ' + convertSecondsToHMS(time_seconds) + ' </p> ' +
        '   <span class="mb-0 text-muted float-left" style="margin: 0px 2px 0px 20px; ">  ---  </span>' +
        '  <p class="mb-0 text-muted float-right">\n' +
        '        <span class=" ">\n' +
        '            <i class="btn btn-primary  mdi mdi-magnify custom-search  macro-details" ' +
        '                style="font-size: 18px; "></i>\n' +
        '            <i class="btn btn-danger mdi mdi-delete-forever remove-macro custom-search" style="font-size: 18px;"></i>' +
        '        </span>' +
        '    </p>\n' +
        '</div>';



    if ($.trim($('#macro_time_hms').text()) !== "error") {
        var selectedCard = $('#dragula-right .left-side-item.selected-item');

        // Check if selectedCard is not empty
        if (selectedCard.length > 0) {
            // Get the last .media-body element inside the selected item
            var macro_body = selectedCard.find('.macro-list');
            macro_body.removeClass('hide_div');

            // Append the macro_box content after the last .media-body
            macro_body.append(macro_box);
        }
        $('#macro_time_hms').next().text("  ");
        $("#macro-modal").modal("hide");
        // reporder list items afetr append macro
        var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
        var startTime = 0;
        for (var i = 0; i < items.length; i++) {

            var duration = parseInt(items[i].getAttribute('data-time_seconds'));
            items[i].setAttribute('data-starttime', formatTime(startTime));
            var composition_start_time = items[i].getAttribute('data-starttime');
            startTime += duration;
            var composition_end_time = startTime;
            // Process the macro_list within the current item if it exists
            var macroItems = items[i].querySelectorAll('.macro-box ');

            if (macroItems.length > 0) {
                for (var j = 0; j < macroItems.length; j++) {
                    var macroTime = macroItems[j].getAttribute('data-time');
                    var macroKind = macroItems[j].getAttribute('data-offset');
                    // Calculate the macro start time based on Kind
                    var macroStartTime;
                    if (macroKind === "Start") {
                        console.log(composition_start_time);
                        console.log(macroTime);
                        macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                    } else if (macroKind === "End") {
                        console.log(composition_end_time);
                        console.log(macroTime);
                        macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                    } else {
                        // Handle other cases if needed
                        macroStartTime = 0;
                    }

                    // Update the macro_time div with the calculated start time
                    macroItems[j].querySelector('.mb-0.text-muted.float-left').innerText = convertSecondsToHMS(macroStartTime);
                }
            }

            var macroList = items[i].querySelector('.card-body.macro-list');
            reorderMacroList(macroList);
        }
    } else {
        $('#macro_time_hms').next().text(" Time parameters can't be applied !");
    }

});




$(document).on('input', '#Hours_macro', function () {
    var parent_duration = parseInt($('#parent_macro_duration').val());
    var seconds = $('#Seconds_macro').val();
    var minutes = $('#Minutes_macro').val();
    var hours = $('#Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="Offset_macro"]:checked').val();
    if ((parent_duration - time_seconds) <= 0) {
        $('#macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }

    // updateSecond('display_start_macro_hours','hours')
});
$(document).on('input', '#Minutes_macro', function () {
    var parent_duration = parseInt($('#parent_macro_duration').val());
    var seconds = $('#Seconds_macro').val();
    var minutes = $('#Minutes_macro').val();
    var hours = $('#Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="Offset_macro"]:checked').val();
    if ((parent_duration - time_seconds) <= 0) {
        $('#macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }
});
$(document).on('input', '#Seconds_macro', function () {
    var parent_duration = parseInt($('#parent_macro_duration').val());
    var seconds = $('#Seconds_macro').val();
    var minutes = $('#Minutes_macro').val();
    var hours = $('#Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="Offset_macro"]:checked').val();

    if ((parent_duration - time_seconds) <= 0) {
        $('#macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }
});
$('input[name="Offset_macro"]').change(function () {

    var parent_duration = parseInt($('#parent_macro_duration').val());
    var seconds = $('#Seconds_macro').val();
    var minutes = $('#Minutes_macro').val();
    var hours = $('#Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="Offset_macro"]:checked').val();

    if ((parent_duration - time_seconds) <= 0) {
        $('#macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }

    // Add your code to handle the change event here
});

$(document).on('click', '#confirm_edit_macro', function () {
    var macro_uuid = $('#edit_macro_uuid').val();
    var parent_uuid = $('#edit_macro_parent_uuid').val();
    var macro_command = $('#edit_macro_command').val();
    var macro_title = $('#edit_macro_title_val').val();
    var Hours_macro = $('#edit_Hours_macro').val();
    var Minutes_macro = $('#edit_Minutes_macro').val();
    var Seconds_macro = $('#edit_Seconds_macro').val();
    var offset = $('input[name=edit_Offset_macro]:checked').val();
    var time_seconds = parseInt(Hours_macro) * 60 * 60 + parseInt(Minutes_macro) * 60 + parseInt(Seconds_macro);


    //  var markerBox = parentItem.find('.macro-list .macro-box[data-uuid="' + macro_uuid + '"]');

    var macro_box = '' +
        macro_title +
        '  <p class="mb-0 text-muted float-left"> ' + convertSecondsToHMS(time_seconds) + ' </p> ' +
        '   <span class="mb-0 text-muted float-left" style="margin: 0px 2px 0px 20px; ">  ---  </span>' +
        '  <p class="mb-0 text-muted float-right">\n' +
        '        <span class=" ">\n' +
        '            <i class="btn btn-primary  mdi mdi-magnify custom-search  macro-details" ' +
        '                style="font-size: 18px; "></i>\n' +
        '            <i class="btn btn-danger mdi mdi-delete-forever remove-macro custom-search" style="font-size: 18px;"></i>' +
        '        </span>' +
        '    </p>\n';

    // Check if selectedCard is not empty
    var selected_item = $('#dragula-right .left-side-item.selected-item');
    var selected_macro = $('#dragula-right .left-side-item.selected-item .macro-list .macro-box[data-uuid="' + macro_uuid + '"]');
    selected_macro.attr('data-title', macro_title);
    selected_macro.attr('data-offset', offset);
    selected_macro.attr('data-command', macro_command);
    selected_macro.attr('data-time', convertSecondsToHMS(time_seconds));
    selected_macro.attr('data-Hours', Hours_macro);
    selected_macro.attr('data-Minutes', Minutes_macro);
    selected_macro.attr('data-Seconds', Seconds_macro);

    if ($.trim($('#edit_macro_time_hms').text()) !== "error") {
        if (selected_item.length > 0) {
            selected_macro.html(macro_box);
        }
        $('#edit_macro_time_hms').next().text("  ");
        $("#edit-macro-modal").modal("hide");

        // reporder list items afetr append macro
        var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
        var startTime = 0;
        for (var i = 0; i < items.length; i++) {

            var duration = parseInt(items[i].getAttribute('data-time_seconds'));
            items[i].setAttribute('data-starttime', formatTime(startTime));
            var composition_start_time = items[i].getAttribute('data-starttime');
            startTime += duration;
            var composition_end_time = startTime;
            // Process the macro_list within the current item if it exists
            var macroItems = items[i].querySelectorAll('.macro-box ');

            if (macroItems.length > 0) {
                for (var j = 0; j < macroItems.length; j++) {
                    var macroTime = macroItems[j].getAttribute('data-time');
                    var macroKind = macroItems[j].getAttribute('data-offset');
                    // Calculate the macro start time based on Kind
                    var macroStartTime;
                    if (macroKind === "Start") {
                        console.log(composition_start_time);
                        console.log(macroTime);
                        macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                    } else if (macroKind === "End") {
                        console.log(composition_end_time);
                        console.log(macroTime);
                        macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                    } else {
                        // Handle other cases if needed
                        macroStartTime = 0;
                    }

                    // Update the macro_time div with the calculated start time
                    macroItems[j].querySelector('.mb-0.text-muted.float-left').innerText = convertSecondsToHMS(macroStartTime);
                }
            }

            var macroList = items[i].querySelector('.card-body.macro-list');
            reorderMacroList(macroList);
        }
    } else {
        $('#edit_macro_time_hms').next().text(" Time parameters can't be applied !");

    }

});

$(document).on('input', '#edit_Hours_macro', function () {
    var parent_duration = parseInt($('#edit_parent_macro_duration').val());
    var seconds = $('#edit_Seconds_macro').val();
    var minutes = $('#edit_Minutes_macro').val();
    var hours = $('#edit_Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="edit_Offset_macro"]:checked').val();
    if ((parent_duration - time_seconds) <= 0) {
        $('#edit_macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }

    // updateSecond('display_start_macro_hours','hours')
});
$(document).on('input', '#edit_Minutes_macro', function () {
    var parent_duration = parseInt($('#edit_parent_macro_duration').val());
    var seconds = $('#edit_Seconds_macro').val();
    var minutes = $('#edit_Minutes_macro').val();
    var hours = $('#edit_Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="edit_Offset_macro"]:checked').val();
    if ((parent_duration - time_seconds) <= 0) {
        $('#edit_macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }
});
$(document).on('input', '#edit_Seconds_macro', function () {
    var parent_duration = parseInt($('#edit_parent_macro_duration').val());
    var seconds = $('#edit_Seconds_macro').val();
    var minutes = $('#edit_Minutes_macro').val();
    var hours = $('#edit_Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="edit_Offset_macro"]:checked').val();
    if ((parent_duration - time_seconds) <= 0) {
        $('#edit_macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }
});
$('input[name="edit_Offset_macro"]').change(function () {

    var parent_duration = parseInt($('#edit_parent_macro_duration').val());
    var seconds = $('#edit_Seconds_macro').val();
    var minutes = $('#edit_Minutes_macro').val();
    var hours = $('#edit_Hours_macro').val();
    var time_seconds = parseInt(hours) * 60 * 60 + parseInt(minutes) * 60 + parseInt(seconds);
    var selectedOffset = $('input[name="edit_Offset_macro"]:checked').val();
    if ((parent_duration - time_seconds) <= 0) {
        $('#edit_macro_time_hms').html(' error');
    } else {
        if (selectedOffset == "End") {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(parent_duration - time_seconds));
        } else {
            $('#edit_macro_time_hms').html(convertSecondsToHMS(time_seconds));
        }

    }
});
$(document).on('click', '.remove-macro', function () {

    $(this).parent().parent().parent().remove();
    // re-order items
    var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
    var startTime = 0;

    for (var i = 0; i < items.length; i++) {
        var duration = parseInt(items[i].getAttribute('data-time_seconds'));
        items[i].setAttribute('data-starttime', formatTime(startTime));
        var composition_start_time = items[i].getAttribute('data-starttime');
        items[i].querySelector('.mb-0.text-muted.float-left').innerText = formatTime(startTime);
        startTime += duration;
        var composition_end_time = startTime;
        // Process the macro_list within the current item if it exists
        var macroItems = items[i].querySelectorAll('.macro_item');
        if (macroItems.length > 0) {
            for (var j = 0; j < macroItems.length; j++) {
                var macroTime = macroItems[j].getAttribute('data-time');
                var macroKind = macroItems[j].getAttribute('data-offset');
                // Calculate the macro start time based on Kind
                var macroStartTime;
                if (macroKind === "Start") {
                    macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                } else if (macroKind === "End") {
                    macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                } else {
                    // Handle other cases if needed
                    macroStartTime = 0;
                }
                // Update the macro_time div with the calculated start time
                macroItems[j].querySelector('#macro_time').innerText = convertSecondsToHMS(macroStartTime);
            }
        }
    }

});


//  end macro

// *****************************  marker
$(document).on('click', '#show_marker_modal', function () {

    var selectedCard = $('#dragula-right .left-side-item.selected-item');

    // Check if selectedCard is not empty
    if (selectedCard.length > 0) {
        var dataTitleValue = selectedCard.data('title');
        $("#title_selected_item").html(dataTitleValue);
        // Get the last .media-body element inside the selected item
        // var marker_body = selectedCard.find('.marker-list');
        // marker_body.removeClass('hide_div');
        $("#action_marker").val("add");
        $('#marker_label').next().text(" ");
        $('#marker_label').val(" ");
        $('#Hours_marker').val(0);
        $('#Minutes_marker').val(0);
        $('#Seconds_marker').val(0);
        $('#start_marker').prop('checked', true);
        $('#end_marker').prop('checked', false);
        $("#marker-modal").modal('show');
    } else {
        $("#no-cpl-selected").modal('show');
    }

});
$(document).on('click', '#confirm_add_marker', function () {

    var marker_label = $('#marker_label').val();
    var uuid = $.trim('urn:uuid:' + uuidv4());
    var Hours_marker = $('#Hours_marker').val();
    var Minutes_marker = $('#Minutes_marker').val();
    var Seconds_marker = $('#Seconds_marker').val();
    var offset = $('input[name=Offset_marker]:checked').val();
    var time_marker = Hours_marker + ' : ' + Minutes_marker + ' : ' + Seconds_marker;
    var time_seconds = parseInt(Hours_marker) * 60 * 60 + parseInt(Minutes_marker) * 60 + parseInt(Seconds_marker);

    var marker_box = '' +
        '<div class="media-body marker-box col-md-8" data-title="' + $.trim(marker_label) + '"' +
        '     data-offset="' + offset + '" ' +
        '     data-time="' + convertSecondsToHMS(time_seconds) + '"' +
        '     data-uuid="' + uuid + '"' +
        '     data-hours="' + Hours_marker + '"' +
        '     data-minutes="' + Minutes_marker + '"' +
        '     data-seconds="' + Seconds_marker + '">' + marker_label +
        '  <p class="mb-0 text-muted float-left"> ' + convertSecondsToHMS(time_seconds) + ' </p> ' +
        '   <span class="mb-0 text-muted float-left" style="margin: 0px 2px 0px 20px; ">  ---  </span>' +
        '  <p class="mb-0 text-muted float-right">\n' +
        '        <span class=" ">\n' +
        '            <i class="btn btn-primary  mdi mdi-magnify custom-search  marker-details" ' +
        '               data-uuid="' + uuid + '"' +
        '                style="font-size: 18px; "></i>\n' +
        '            <i class="btn btn-danger mdi mdi-delete-forever remove-marker custom-search" style="font-size: 18px;"></i>' +
        '        </span>' +
        '    </p>\n' +
        '</div>';
    var selectedCard = $('#dragula-right .left-side-item.selected-item');

    // Check if selectedCard is not empty
    if (selectedCard.length > 0) {
        if ($.trim(marker_label) !== '') {
            $('#marker_label').next().text(" ");
            // Get the last .media-body element inside the selected item
            var marker_body = selectedCard.find('.marker-list');
            marker_body.removeClass('hide_div');

            // Append the macro_box content after the last .media-body
            marker_body.append(marker_box);
            $("#marker-modal").modal("hide");
        } else {
            $('#marker_label').next().text("Marker Label can't be empty.");
        }


    }


});
$(document).on('click', '.remove-marker', function () {

    $(this).parent().parent().parent().remove();
    // re-order items
    var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
    var startTime = 0;

    for (var i = 0; i < items.length; i++) {
        var duration = parseInt(items[i].getAttribute('data-time_seconds'));
        items[i].setAttribute('data-starttime', formatTime(startTime));
        var composition_start_time = items[i].getAttribute('data-starttime');
        items[i].querySelector('.mb-0.text-muted.float-left').innerText = formatTime(startTime);
        startTime += duration;
        var composition_end_time = startTime;
        // Process the macro_list within the current item if it exists
        var macroItems = items[i].querySelectorAll('.macro_item');
        if (macroItems.length > 0) {
            for (var j = 0; j < macroItems.length; j++) {
                var macroTime = macroItems[j].getAttribute('data-time');
                var macroKind = macroItems[j].getAttribute('data-offset');
                // Calculate the macro start time based on Kind
                var macroStartTime;
                if (macroKind === "Start") {
                    macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                } else if (macroKind === "End") {
                    macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                } else {
                    // Handle other cases if needed
                    macroStartTime = 0;
                }
                // Update the macro_time div with the calculated start time
                macroItems[j].querySelector('#macro_time').innerText = convertSecondsToHMS(macroStartTime);
            }
        }
    }

});
$(document).on('click', '.marker-details', function () {
    let marker_uuid = $(this).data("uuid");

    let data_hours = this.closest('.marker-box').getAttribute("data-hours");
    let data_minutes = this.closest('.marker-box').getAttribute("data-minutes");
    let data_seconds = this.closest('.marker-box').getAttribute("data-seconds");
    let data_time = this.closest('.marker-box').getAttribute("data-time");
    let title = this.closest('.marker-box').getAttribute("data-title");
    let offset = this.closest('.marker-box').getAttribute("data-offset");

    // get uuid parent cpl/pattern
    var leftSideItem = $(this).closest('.left-side-item');
    var parent_uuid = leftSideItem.data('uuid');
    var parent_title = leftSideItem.data('title');
    $("#edit_title_selected_item").html(parent_title);
    $("#edit_marker_label").val(title);
    $("#parent_uuid").val(parent_uuid);
    $("#marker_uuid").val(marker_uuid);

    $("#edit_marker_time_hms").html(data_time);
    $("#edit_Hours_marker").val(parseInt(data_hours));
    $("#edit_Minutes_marker").val(parseInt(data_minutes));
    $("#edit_Seconds_marker").val(parseInt(data_seconds));
    $("#edit_Offset_marker").val(offset);
    $("#edit-marker-modal").modal('show');
});
$(document).on('click', '#confirm_edit_marker', function () {

    var marker_label = $('#edit_marker_label').val();
    var uuid = $('#marker_uuid').val();
    var parent_id = $('#parent_uuid').val();
    var Hours_marker = $('#edit_Hours_marker').val();
    var Minutes_marker = $('#edit_Minutes_marker').val();
    var Seconds_marker = $('#edit_Seconds_marker').val();
    var offset = $('input[name=edit_Offset_marker]:checked').val();
    var time_seconds = parseInt(Hours_marker) * 60 * 60 + parseInt(Minutes_marker) * 60 + parseInt(Seconds_marker);


    var parentItem = $('#dragula-right .left-side-item[data-uuid="' + parent_id + '"]');

    var markerBox = parentItem.find('.marker-list .marker-box[data-uuid="' + uuid + '"]');

    var marker_box = marker_label +
        '<p class="mb-0 text-muted float-left">' + convertSecondsToHMS(time_seconds) + ' </p>' +
        '<span class="mb-0 text-muted float-left" style="margin: 0px 2px 0px 20px; ">  ---  </span> ' +
        ' <p class="mb-0 text-muted float-right">' +
        '  <span class=" ">' +
        '       <i class="btn btn-primary  mdi mdi-magnify custom-search  marker-details" data-uuid="' + uuid + '" style="font-size: 18px; "></i>' +
        '       <i class="btn btn-danger mdi mdi-delete-forever remove-marker custom-search" style="font-size: 18px;"></i>  ' +
        '  </span>' +
        ' </p> ';
    if ($.trim(marker_label) !== '') {
        $('#edit_marker_label').next().text(" ");
        markerBox.html(marker_box);
        markerBox.attr('data-title', marker_label);
        markerBox.attr('data-time', convertSecondsToHMS(time_seconds));
        markerBox.attr('data-offset', offset);
        markerBox.attr('data-uuid', uuid);
        markerBox.attr('data-hours', Hours_marker);
        markerBox.attr('data-minutes', Minutes_marker);
        markerBox.attr('data-seconds', Seconds_marker);

        $("#edit-marker-modal").modal('hide');
    } else {
        $('#edit_marker_label').next().text("Marker Label can't be empty.");
    }


});

// edit marker

// **************************  segment

$(document).on('click', '#add_segment', function () {
    var uuid_segment = 'urn:uuid:' + uuidv4();
    var segment_box = '' +
        '<div class="card rounded border mb-2 left-side-item  segment-style" data-side="right" ' +
        '     data-uuid="' + uuid_segment + '" data-title="New Segment"  ' +
        '     data-type_component="segment"    data-type="segment"   >   ' +
        '     <div class="card-body  ">\n' +

        '         <div class="media-body">\n' +
        '              <p class="mb-0 text-muted float-left">' +
        '                    <i class="btn btn-inverse-warning  mdi mdi-package-variant-closed"></i>  New Segment ' +

        '              </p>\n' +
        '              <p class="mb-0 text-muted float-right">\n' +
        '                 <span class=" ">\n' +
        '                    <i class="btn btn-primary  mdi mdi-magnify custom-search  segment-details" data-uuid="' + uuid_segment + '"  ></i>\n' +
        '                    <i class="btn btn-danger mdi mdi-delete-forever remove-cpl custom-search"></i>\n' +
        '                 </span>' +
        '               </p>\n' +
        '           </div>            ' +
        '     </div>' +
        '</div>';
    $('#dragula-right').append(segment_box);
});
$(document).on('click', '.segment-details', function () {
    //let title = $(this).parent().parent().parent().parent().parent().data("title");
    let title = $(this).closest('.card[data-title]').data("title");
    let uuid = $(this).parent().parent().parent().parent().parent().data("uuid");

    $("#pack_name").val(title);
    $("#segment_uuid").val(uuid);

    $("#segment-modal").modal('show');
});
$(document).on('click', '#confirm_add_segment', function () {
    var title = $('#pack_name').val();
    var uuid = $('#segment_uuid').val();
    var type = "segment";
    var targetItem = $('#dragula-right .card[data-uuid="' + uuid + '"][data-type="' + type + '"]');
    if (targetItem.length > 0) {
        // Change the text inside mb-0 text-muted float-left

        targetItem.attr('data-title', title).promise().done(function () {
            // Retrieve the updated data-title value
            var updatedTitle = targetItem.attr('data-title');

        });
        targetItem.find('.mb-0.text-muted.float-left').html('<i class="btn btn-inverse-warning  mdi mdi-package-variant-closed"></i> ' + title);
    }

});

// end marker

// ******************  pattern
$(document).on('click', '#confirm_add_pattern', function () {
    var type_action = $('#type_action').val();
    var pattern_title = $('#pattern_title').val();
    var pattern_Seconds = $('#pattern_Seconds').val();


    var pattern_Minutes = $('#pattern_Minutes').val();

    var time_seconds = parseInt(pattern_Minutes) * 60 + parseInt(pattern_Seconds);

    if (pattern_title == "Black 3D 48") {
        var editrate_numerator = "48";
        var editrate_denominator = "1";
    } else {
        var editrate_numerator = "24";
        var editrate_denominator = "1";
    }
    var icon = pattern_title == "Black" ? " " : ' <span class="icon_pattern">3D</span> ';
    var pattern_box =
        '<div class="card rounded border mb-2 left-side-item" data-side="left" ' +
        '     data-type="Pattern"' +
        '     data-id="' + uuidv4() + '"' +
        '     data-uuid="urn:uuid:' + uuidv4() + '"' +
        '     data-source=""' +
        '     data-title="' + pattern_title + '"' +
        '     data-editrate_denominator="' + editrate_denominator + '"' +
        '     data-editrate_numerator="' + editrate_numerator + '"' +
        '     data-minutes="' + pattern_Minutes + '"' +
        '     data-seconds="' + pattern_Seconds + '"' +
        '     data-version="new_item"' +
        '     data-time_seconds="' + time_seconds + '"' +
        '     data-time="' + convertSecondsToHMS(time_seconds) + '"' +
        '  >   ' +
        '   <div class="card-body  ">\n' +
        '      <div class="media-body  ">\n' +
        '          <h6 class="mb-1" style=" font-size: 18px">' + pattern_title + '</h6>\n' +
        '      </div>\n' +
        '      <div class="media-body">\n' +
        '         <p class="mb-0 text-muted float-left">' + convertSecondsToHMS(time_seconds) + '</p>\n' +
        '         <p class="mb-0 text-muted float-right">\n' +
        '            <span class=" ">\n' +
        '              <i class="btn btn-primary  mdi mdi-magnify custom-search  pattern-details" data-uuid="urn:uuid:e83235b4-f50d-4f46-906f-2ce2cca1ba52" ></i>\n' +
        '              <i class="btn btn-danger mdi mdi-delete-forever remove-cpl custom-search"></i>\n' +
        '            </span>' +
        '         </p>\n' +
        '       </div>\n' +
        '   </div>\n' +
        '   <div class="card-body macro-list  hide_div"></div>' +
        '   <div class="card-body marker-list hide_div"></div>' +
        '</div>';
    if (type_action == "db_click") {
        $('#dragula-right').append(pattern_box);
    } else {
        var uuid_before = $('#uuid_before_item').val();
        // var before_item = $('#dragula-right .left-side-item[data-uuid="' + uuid_before + '"]');
        if (uuid_before == "default_uuid") {
            $('#dragula-right').append(pattern_box);
        } else {
            $('#dragula-right .left-side-item').each(function () {
                var currentUuid = $(this).data('uuid');

                // Check if the current card's uuid matches the one to insert before
                if (currentUuid === uuid_before) {
                    // Insert the new card before the current card
                    $(this).before(pattern_box);
                    return false; // Exit the loop after inserting the new card
                }
            });
        }

    }


    //  $('#dragula-right').append(pattern_box);
    // re-order items
    var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
    var startTime = 0;

    for (var i = 0; i < items.length; i++) {
        var duration = parseInt(items[i].getAttribute('data-time_seconds'));
        items[i].setAttribute('data-starttime', formatTime(startTime));
        var composition_start_time = items[i].getAttribute('data-starttime');
        items[i].querySelector('.mb-0.text-muted.float-left').innerText = formatTime(startTime);
        startTime += duration;
        var composition_end_time = startTime;
        // Process the macro_list within the current item if it exists
        var macroItems = items[i].querySelectorAll('.macro_item');
        if (macroItems.length > 0) {
            for (var j = 0; j < macroItems.length; j++) {
                var macroTime = macroItems[j].getAttribute('data-time');
                var macroKind = macroItems[j].getAttribute('data-offset');
                // Calculate the macro start time based on Kind
                var macroStartTime;
                if (macroKind === "Start") {
                    macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                } else if (macroKind === "End") {
                    macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                } else {
                    // Handle other cases if needed
                    macroStartTime = 0;
                }
                // Update the macro_time div with the calculated start time
                macroItems[j].querySelector('#macro_time').innerText = convertSecondsToHMS(macroStartTime);
            }
        }
    }
    reorderRightList();
});

$(document).on('click', '.pattern-details', function () {
    // let id_cpl = $(this).data("uuid");
    // let title = $(this).data("title");
    // let title = $(this).data("title");
    // let title = $(this).data("title");
    //
    var title = this.closest('.left-side-item ').getAttribute("data-title");
    var minutes = this.closest('.left-side-item ').getAttribute("data-minutes");
    var seconds = this.closest('.left-side-item ').getAttribute("data-seconds");
    $('#edit_display_pattern_title').html(title);
    $('#edit_pattern_title').val(title);
    $('#edit_pattern_Minutes').val(minutes);
    $('#edit_pattern_Seconds').val(seconds);
    $('#edit_pattern_in_seconds').html(this.closest('.left-side-item ').getAttribute("data-time_seconds"));

    $("#edit-pattern_modal").modal('show');
});

$(document).on('click', '#confirm_edit_pattern', function () {


    var pattern_Seconds = $('#edit_pattern_Seconds').val();
    var pattern_Minutes = $('#edit_pattern_Minutes').val();
    var time_seconds = parseInt(pattern_Minutes) * 60 + parseInt(pattern_Seconds);


    var selected_item = $('#dragula-right .left-side-item.selected-item');

    selected_item.attr('data-time_seconds', time_seconds);
    selected_item.attr('data-time', convertSecondsToHMS(time_seconds));
    selected_item.attr('data-minutes', pattern_Minutes);
    selected_item.attr('data-seconds', pattern_Seconds);

    reorderRightList();
});

$(document).on('dblclick', '#dragula-left .left-side-item', function (e) {
    var clonedElement = $(this).clone();
    if (clonedElement.data('type') === "Macros") {

        var selected_item = $('#dragula-right .left-side-item.selected-item');
        if (selected_item.length === 0) {
            $("#no-cpl-selected").modal('show');
        } else {
            var destination_title = selected_item.data('title');
            var destination_duration = selected_item.data('time_seconds');
            $("#parent_title").html(destination_title);
            $("#macro_title").html(clonedElement.data('title'));
            $("#parent_macro_duration").val(destination_duration);
            $("#macro_command").val(clonedElement.data('command'));
            $("#macro_title_val").val(clonedElement.data('title'));
            $("#macro_time_hms").html('00:00:00');
            $("#Hours_macro").val(0);
            $("#Minutes_macro").val(0);
            $("#Seconds_macro").val(0);
            $('#macro_time_hms').next().text("  ");
            $("#start_macro").prop("checked", true);
            $("#end_macro").prop("checked", false);
            $("#macro-modal").modal('show');
        }

        // if (check == 1) {
        //     $(this).data('type');
        //     $('#titl_macro').html($(this).parent().data('title'));
        //     $('#id_macro_item').html($(this).parent().data('id'));
        //     $('#id_macro_item').attr('data-id', $(this).parent().data('id'));
        //     resetMacroTimeInputs();
        //     $("#macro_modal").modal('show');
        // }
    }
    else if (clonedElement.data('type') === "Pattern") {
        $("#type_action").val('db_click');
        $("#pattern_Minutes").val('0');
        $("#pattern_Seconds").val('0');
        $("#pattern_title").val(clonedElement.data('title'));
        $("#display_pattern_title").html(clonedElement.data('title'));
        $("#pattern_modal").modal('show');


    } else if (clonedElement.data('type') === "SPL") {

    } else {
        modifyContentBeforeDrop(clonedElement.get(0));

        $('#dragula-right').append(clonedElement);

        // re-order items
        var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
        var startTime = 0;

        for (var i = 0; i < items.length; i++) {
            var duration = parseInt(items[i].getAttribute('data-time_seconds'));
            items[i].setAttribute('data-starttime', formatTime(startTime));
            var composition_start_time = items[i].getAttribute('data-starttime');
            items[i].querySelector('.mb-0.text-muted.float-left').innerText = formatTime(startTime);
            startTime += duration;
            var composition_end_time = startTime;
            // Process the macro_list within the current item if it exists
            var macroItems = items[i].querySelectorAll('.macro_item');
            if (macroItems.length > 0) {
                for (var j = 0; j < macroItems.length; j++) {
                    var macroTime = macroItems[j].getAttribute('data-time');
                    var macroKind = macroItems[j].getAttribute('data-offset');
                    // Calculate the macro start time based on Kind
                    var macroStartTime;
                    if (macroKind === "Start") {
                        macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                    } else if (macroKind === "End") {
                        macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                    } else {
                        // Handle other cases if needed
                        macroStartTime = 0;
                    }
                    // Update the macro_time div with the calculated start time
                    macroItems[j].querySelector('#macro_time').innerText = convertSecondsToHMS(macroStartTime);
                }
            }
        }
    }

});


// *************************  Top menu Right Side

//click btn new spl
$(document).on('click', '#reset_spl_builder', function () {
    //$('#actual_spl_title').text("No SPL Selected , Create New One");
    //  $('#id_spl_opened').text(0);
    $('#opened_spl').attr('data-opened_spl_status', 0);
    $('#opened_spl').attr('data-hfr', 0);
    $('#opened_spl').attr('data-mod', "2D");
    $('#opened_spl').attr('data-uuid', "");
    $('#opened_spl').attr('data-title', "");
    $("#dragula-right").empty();
    $('#opened_spl').html('No Playlist Selected');
    // $('#id_spl_opened').attr('data-hfr', 0 );
    // $('#id_spl_opened').attr('data-mod', "" );
    // $('#id_spl_opened').attr('data-spl_uuid', 0 );
    // prepareSortablReorder();
});
// click open (display spls list
$(document).on('click', '#open_spl_list', function () {
    $("#spl-list").modal('show');
    $("#order-listing").dataTable().fnDestroy();
    $('#order-listing').DataTable({
        // "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"],
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,
        "info": true,

        "responsive": true,
        "processing": true,
        "serverSide": true,
        // "iDisplayLength": 20,
        "scrollY": "600px", // Set the height of the scrolling area

        "ajax": 'system/controller_playlist_builder.php?action_control=get_spl_available',

        'columnDefs': [
            {
                'targets': 3,
                'searchable': false,
                'orderable': false,

                'className': 'dt-body-center',
                'render': function (data, type, row) {
                    //+row[1]+ for getting parameters by position
                    return ' <i class="btn btn-primary mdi mdi-tooltip-edit open_spl" data-title="' + row[0] + '" data-uuid="' + row[2] + '"></i>' +
                        '    <i class="btn btn-danger mdi   mdi-delete-forever delete_spl"  data-title="' + row[0] + '"    data-uuid="' + row[2] + '"></i>     ';
                }
            }]
    });
    // var available="";
    // $.ajax({
    //     url: 'system/controller_playlist_builder.php',
    //     type: 'post',
    //     cache: false,
    //     data: {
    //         action_control: "get_spl_available"
    //     },
    //     success: function (response) {
    //         try {
    //             $(jQuery.parseJSON(response)).each(function () {
    //                 available += '<div class="row header_ingest_spl item_to_select" id="' + this.uuid + '" data-id="' + this.uuid + '">' +
    //                     // '             <span class="col-md-1">' + this.file_type + '</span>' +
    //                     '             <span class="col-md-5 spl_title"> ' + this.ShowTitleText + '</span>' +
    //                     '             <span class="col-md-2 spl_date"> ' + this.IssueDate + '</span>' +
    //                     '             <span class="col-md-5">' + this.uuid + '</span>' +
    //                     '        </div>';
    //             });
    //             // $(available).insertAfter(id_header);
    //             $(id_parent).html(available);
    //
    //         } catch (e) {
    //             console.log(e);
    //         }
    //     },
    //     error: function (jqXHR, textStatus, errorThrown) {
    //         console.log(errorThrown);
    //     },
    //     complete: function (jqXHR, textStatus) {
    //     }
    // });
    //
    // getAvailableSpl("#id_open_spl_list");
    // $("#open_spl").modal('show');
    // $('#save_new_spl').prop('disabled', true);
    // $("#file_name").keyup(function () {
    //     if ($('#file_name').val().trim() === '') {
    //         $("#file_name").css("background-color", "pink");
    //     } else {
    //         $("#file_name").css("background-color", "#2386cc94");
    //     }
    // });
    // function validateNextButton() {
    //     var buttonDisabled = $('#file_name').val().trim() === '';
    //     $('#save_new_spl').prop('disabled', buttonDisabled);
    // }
    // $('#file_name').on('keyup', validateNextButton);
});

$(document).on('click', '#display_spl_properties', function () {

    var opened_spl_status = $('#opened_spl').attr("data-opened_spl_status");
    if (opened_spl_status == 1) {

        $('#spl_action').val("edit");

        var id_spl = $('#opened_spl').data("uuid");
        $('#spl_uuid_edit').val(id_spl);
        var title = $('#opened_spl').text();
        var mod = $('#opened_spl').attr("data-mod");
        var hfr = $('#opened_spl').attr("data-hfr");
        if (hfr == 1) {
            $('#spl_properties_hfr').prop('checked', true);
        } else {
            $('#spl_properties_hfr').prop('checked', false);

        }
        $('#uuid_spl_edit').val(id_spl);
        $('#spl_title').val(title);
        $('#display_mode').val(mod);
        $("#spl-properties-modal").modal('show');
    }
    else {
        $('#spl_action').val("insert");
        $('#spl_uuid_edit').val(0);
        $('#spl_title').val(" ");
        $('#spl_properties_hfr').prop('checked', false);
        $("#spl-properties-modal").modal('show');
    }

});
//click btn save new spl
$(document).on('click', '#save_new_spl', function () {
    let array_spl = [];
    let items_spl = [];
    let items_macro = [];
    let items_marker = [];
    let items_intermission = [];
    var title_spl = $('#spl_title').val();

    if (title_spl == "") {
        $('#spl_title').next().text("SPL Title can't be empty.");
    }
    else {
        $('#spl_title').next().text(" ");
        var display_mode = $('#display_mode').val();

        var hfr = 0;
        if ($('#spl_properties_hfr').is(":checked")) {
            hfr = 1;
        }
        $('#dragula-right > .left-side-item').map(function () {
            items_macro = [];
            items_marker = [];
            items_intermission = [];
            var edit_rate_denominator = $(this).data('editrate_denominator');
            var edit_rate_numerator = $(this).data('editrate_numerator');
            var marco_divs = $(this).find('.macro-box');
            var marker_divs = $(this).find('.marker-box');

            let intermission_divs = $(this).children('.intermission_list').children('.intermission_style');
            marco_divs.map(function () {
                console.log($(this).data('time'));
                console.log($(this));
                items_macro.push({
                    'id': $(this).data('uuid'),
                    'uuid': $(this).data('uuid'),
                    'title': $(this).data('title'),
                    'offset': $(this).data('offset'),
                    'time': $(this).data('time'),
                    'time_frames': convertStringToSeconds($(this).data('time')) * edit_rate_numerator / edit_rate_denominator
                });
            });
            if (items_macro.length == 0) {
                items_macro = null;
            }
            marker_divs.map(function () {
                items_marker.push({
                    'uuid': $(this).data('uuid'),
                    'title': $(this).data('title'),
                    'offset': $(this).data('offset'),
                    'time': $(this).data('time'),
                    'time_frames': convertStringToSeconds($(this).data('time')) * edit_rate_numerator / edit_rate_denominator

                });
            });
            if (items_marker.length == 0) {
                items_marker = null;
            }
            intermission_divs.map(function () {
                items_intermission.push({
                    'uuid': $(this).data('uuid'),
                    'title': $(this).data('title'),
                    'offset': $(this).data('offset'),
                    'time': $(this).data('time'),
                    'time_frames': convertStringToSeconds($(this).data('time')) * edit_rate_numerator / edit_rate_denominator
                });
            });
            if (items_intermission.length == 0) {
                items_intermission = null;
            }
            array_spl.push($(this).data('uuid'));
            items_spl.push({
                'kind': $(this).data('type'),
                'id': $(this).data('id'),
                'uuid': $(this).data('uuid'),
                'source': $(this).data('source'),
                'title': $(this).data('title'),
                'IntrinsicDuration': $(this).data('time'),
                'start_time': $(this).data('starttime'),
                'time_seconds': $(this).data('time_seconds'),
                'editrate_denominator': $(this).data('editrate_denominator'),
                'editrate_numerator': $(this).data('editrate_numerator'),
                'id_server': $(this).data('id_server'),
                'macro_list': items_macro,
                'marker_list': items_marker,
                'items_intermission': items_intermission
            });
        });
        var array_length = 0;
        array_length = array_spl.length;


        if (array_length > 0) {
            var action_control = "save_new_spl";
            var spl_title = $('#spl_title').val();
            var file_name = $('#file_name').val();
            var display_mode = $('#display_mode').val();
            $('#opened_spl').attr('data-mod', display_mode);
            var hfr = 0;
            if ($('#spl_properties_hfr').is(":checked")) {
                hfr = 1;
                $('#opened_spl').attr('data-hfr', 1);
            } else {
                $('#opened_spl').attr('data-hfr', 0);
            }
            $('#opened_spl').text(spl_title);


            $('#opened_spl').attr('data-title', spl_title);


            //console.log(items_spl);
            $.ajax({
                url: 'system/controller_playlist_builder.php',
                type: 'post',
                cache: false,
                data: {
                    array_spl: array_spl,
                    title_spl: spl_title,
                    display_mode: display_mode,

                    hfr: hfr,
                    action_control: action_control,
                    items_spl: items_spl
                },
                success: function (response) {
                    try {
                        console.log(response);
                        var obj = JSON.parse(response);
                        // $('#actual_spl_title').text(title_spl);
                        // $('#id_spl_opened').text(obj['uuid']);
                        $('#opened_spl').attr('data-uuid', obj['uuid']);
                        $(jQuery.parseJSON(response)).each(function () {
                            status = this.status;
                        });
                        if (status === "Failed") {
                            $("#success_message_update").css("background-color", "rgb(224 114 114)");
                            $('#success_message_update').fadeIn().html("Execution Failed");
                            setTimeout(function () {
                                $('#success_message_update').fadeOut("slow");
                            }, 2000);
                        }
                        if (status === "saved") {
                            $('#container2 > .div_list').map(function () {
                                if ($(this).data('version') === "new_item") {
                                    this.setAttribute('data-version', "old");
                                }
                            });

                            array_spl = [];
                            array_length = 0;
                            window.array_length = 0;

                            $('#success_message_update').css({ "background": "#CCF5CC" });
                            $('#success_message_update').fadeIn().html("SPL saved successfully");
                            setTimeout(function () {
                                $('#success_message_update').fadeOut("slow");
                            }, 2000);
                        }
                    } catch (e) {
                        console.log(e);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                },
                complete: function (jqXHR, textStatus) {
                }
            });
        }
        array_spl = [];
        window.array_length = 0;
        $("#save_spl_form").trigger('reset');


    }
});

$(document).on('input', '#spl_title', function () {
    checkAvailability();
});
$(document).on('input', '#edit_spl_title', function () {
    checkAvailabilityEditForm();
});
$(document).ready(function () {
    $(document).on('click', '#edit_spl_properties', function () {

        var opened_spl_status = $('#opened_spl').attr("data-opened_spl_status");
        if (opened_spl_status == 1) {
            var id_spl = $('#opened_spl').data("uuid");
            var title = $('#opened_spl').text();
            var mod = $('#opened_spl').attr("data-mod");
            var hfr = $('#opened_spl').attr("data-hfr");
            if (hfr == 1) {
                $('#edit_hfr_spl').prop('checked', true);
            } else {
                if (hfr == 1) {
                    $('#edit_hfr_spl').prop('checked', false);
                }
            }
            $('#uuid_spl_edit').val(id_spl);
            $('#edit_spl_title').val(title);
            $('#edit_display_mode').val(mod);
            $("#edit-spl-properties-modal").modal('show');
        } else {
            $("#no-spl-selected").modal('show');
        }

    });
});


$(document).on('click', '.open_spl', function () {
    var action_control = "open_spl";

    $('#opened_spl').attr('data-opened_spl_status', 1);
    var id_spl = $(this).data("uuid");
    var title = $(this).data("title");
    openSpl(id_spl);
    $('#opened_spl').html(title);
    $('#opened_spl').attr('data-opened_spl_status', 1);
    $("#spl-list").modal("hide");


});
// remove cpl right list
$(document).on('click', '.remove-cpl', function () {

    $(this).parent().parent().parent().parent().parent().remove();
    // re-order items
    var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
    var startTime = 0;

    for (var i = 0; i < items.length; i++) {
        var duration = parseInt(items[i].getAttribute('data-time_seconds'));
        items[i].setAttribute('data-starttime', formatTime(startTime));
        var composition_start_time = items[i].getAttribute('data-starttime');
        items[i].querySelector('.mb-0.text-muted.float-left').innerText = formatTime(startTime);
        startTime += duration;
        var composition_end_time = startTime;
        // Process the macro_list within the current item if it exists
        var macroItems = items[i].querySelectorAll('.macro_item');
        if (macroItems.length > 0) {
            for (var j = 0; j < macroItems.length; j++) {
                var macroTime = macroItems[j].getAttribute('data-time');
                var macroKind = macroItems[j].getAttribute('data-offset');
                // Calculate the macro start time based on Kind
                var macroStartTime;
                if (macroKind === "Start") {
                    macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                } else if (macroKind === "End") {
                    macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                } else {
                    // Handle other cases if needed
                    macroStartTime = 0;
                }
                // Update the macro_time div with the calculated start time
                macroItems[j].querySelector('#macro_time').innerText = convertSecondsToHMS(macroStartTime);
            }
        }
    }

});
//remove macro right list


$(document).ready(function () {
    $(document).on('click', '.delete_spl', function () {
        var spl_uuid = $(this).data('uuid');
        var title = $(this).data('title');

        $("#spl_title_to_delete").html(title);
        $("#spl_uuid_to_delete").html(spl_uuid);
        $("#id_spl_delete").val(spl_uuid);
        $("#delete-spl").modal('show');

        //  deleteSplSelected(spl_uuid);
    });
});
$(document).ready(function () {
    $(document).on('click', '#confirm_delete_spl', function () {
        var spl_uuid = $("#id_spl_delete").val();
        deleteSplSelected(spl_uuid);
        $("#delete-spl").modal("hide");
    });
});


// ************** select items right side list
$('#dragula-right').on('click', '.left-side-item', function () {
    // Remove 'selected' class from all items
    $('.left-side-item').removeClass('selected-item');
    $('.mb-0.text-muted.float-left').removeClass("color-white");

    // Add 'selected' class to the clicked item
    $(this).toggleClass('selected-item');
    $(this).find('.mb-0.text-muted.float-left').addClass("color-white");

});

// *********************** functions
function displayStorageContent(target_devices) {
    $.ajax({
        url: 'system/controller_playlist_builder.php',
        type: 'post',
        data: { action_control: target_devices },
        beforeSend: function () {
            swal({
                title: 'Refreshing',
                allowEscapeKey: false,
                allowOutsideClick: true,
                onOpen: () => {
                    swal.showLoading();
                }
            });
        },
        success: function (response) {
            swal.close();
            try {
                $("#wait").modal("hide");
                var box = "";
                var box_kind = "";

                var obj = JSON.parse(response);

                let cpls = obj.cpl_content;
                let spls = obj.spl_content;
                let macros = obj.macros;

                // prepare menu sort by ContentKind
                let option_kind = ' <option value="all" selected="selected">All Elements</option>' +
                    '<option value="Pattern" >Pattern</option>';
                var kinds = [...new Set(cpls.map(item => item.ContentKind))];
                $.each(kinds, function (indexes, values) {
                    option_kind += ' <option value="' + values + '" >' + values + '</option>';
                });
                option_kind += ' ' +
                    ' <option value="SPL"> Show Playlist</option> ' +
                    '<option value="Macros"> Macros</option>';
                $('#filter_type').html(option_kind);
                $("#filter_type option:first").attr('selected', 'selected');

                // cpls list
                for (var i = 0; i < cpls.length; i++) {

                    if (cpls[i].ContentKind.localeCompare(box_kind) == 0) {
                        box_kind = cpls[i].ContentKind;
                    } else {
                        box_kind = cpls[i].ContentKind;
                        box += '<div class=" filtered  div_list title-kind  "   ' +
                            '        data-type="' + cpls[i].ContentKind + '">' + cpls[i].ContentKind + '  </div>';
                    }
                    if (cpls[i].ContentKind.match(/^(SPL|Automation|Trigger|Cues|Pattern)$/)) {

                    }
                    box +=
                        '   <div class="card rounded border mb-2 left-side-item"' +
                        '       data-side="left"   ' +
                        '       data-auditorium="' + cpls[i].id_auditorium + '" ' +
                        '       data-uuid="' + cpls[i].cpl_uuid + '"' +
                        '       data-title="' + cpls[i].ContentTitleText + '"' +
                        '       data-time="' + cpls[i].Duration + '"  ' +
                        '       data-time_seconds="' + cpls[i].Duration_seconds + '"  ' +
                        '       data-time_Duration_frames="' + cpls[i].Duration_frames + '"  ' +
                        '       data-type_component="cpl"' +
                        '       data-id="' + cpls[i].id_dcp + '"' +
                        '       data-version="left_tab"' +
                        '       data-type="' + cpls[i].ContentKind + '"' +
                        '       data-editRate_denominator="' + cpls[i].editRate_denominator + '"' +
                        '       data-editRate_numerator="' + cpls[i].editRate_numerator + '"' +
                        '       data-id_server="' + cpls[i].id_server + '"' +
                        '       data-source="' + cpls[i].source + '"' +
                        '       data-need_kdm="' + ((cpls[i].pictureEncryptionAlgorithm == "None" || cpls[i].pictureEncryptionAlgorithm == 0) ? 0 : 1) + '"' +
                        '> ' +
                        '       <div class="card-body  "  >\n' +

                        '                 <div class="media-body  ">\n' +
                        '                      <h6 class="mb-1"  style="font-size: 17px;color:' +
                        (cpls[i].type_ScreenAspectRatio.type === "Flat" ? "#52d4f7" :
                            (cpls[i].type_ScreenAspectRatio.type === "Scope" ? "#00d25b" : "white"))
                        + '">' + cpls[i].ContentTitleText +
                        ((cpls[i].pictureEncryptionAlgorithm == "None" || cpls[i].pictureEncryptionAlgorithm == 0) ? " " : "<i class=\"mdi mdi-lock-outline  cpl_need_kdm\" aria-hidden=\"true\"></i>") +
                        '</h6>\n' +
                        '                  </div>\n' +
                        '                  <div class="media-body">\n' +
                        '                       <p class="mb-0 text-muted float-left">' + formatDurationToHMS(cpls[i].Duration_seconds) + ' <span class="detail-cpl">  Subtitle, VI, HI, DBox </span>   </p>\n' +
                        '                       <p class="mb-0 text-muted float-right">\n' +
                        '                          <span class="icon-prop-cpl">' +
                        (cpls[i].is_3D == 1 ? '3D' : '2D') +
                        '                          </span>\n' +
                        '                          <span class="flat">  ' +
                        (cpls[i].type_ScreenAspectRatio.Aspect_Ratio == "unknown" ? cpls[i].type_ScreenAspectRatio.type
                            : cpls[i].type_ScreenAspectRatio.Aspect_Ratio + ' ' + cpls[i].type_ScreenAspectRatio.Cinema_DCP) +
                        '                           </span>\n' +
                        '                          <span class="flat">' + cpls[i].soundChannelCount + ' </span>\n' +
                        '                          <span class="flat"> ST  </span>\n' +
                        '                          <span class="cpl-details"  ' +
                        '                                data-uuid="' + cpls[i].cpl_uuid + '"' +
                        '                                data-need_kdm="' + ((cpls[i].pictureEncryptionAlgorithm == "None" || cpls[i].pictureEncryptionAlgorithm == 0) ? 0 : 1) + '"' +
                        '                            > <i class="btn btn-primary  custom-search mdi mdi-magnify"> </i></span>\n' +
                        '                       </p>\n' +
                        '                    </div> ' +
                        '              </div>\n' +
                        '              <div class="card-body macro-list hide_div"></div> ' +
                        '              <div class="card-body marker-list hide_div"></div>' +

                        '   </div>';
                }
                // append pattern
                box += '<div class=" filtered  div_list   title-kind  " data-type="Pattern"> Pattern   </div>';
                box += '' +
                    '<div class="list-group-item div_list card rounded border mb-2 left-side-item" ' +
                    '         data-side="left" ' +
                    '         data-type="Pattern" ' +
                    '         data-version="left_tab"' +
                    '         data-title="Black"  >' +
                    '        <span></span>' +
                    '        <div class="title-content"> ' +
                    '          Black <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"  ></i>' +
                    '        </div>' +
                    '        <div class="card-body macro-list hide_div"></div> ' +
                    '</div>';
                box +=
                    '<div class="list-group-item div_list card rounded border mb-2 left-side-item " ' +
                    '         data-side="left" ' +
                    '         data-type="Pattern" ' +
                    '         data-version="left_tab"' +
                    '         data-title="Black 3D">' +
                    '      <div class="title-content"> ' +
                    '            <span class="icon_pattern">3D</span>' +
                    '            Black 3D <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"  ></i>' +
                    '      </div>' +
                    '      <div class="card-body macro-list hide_div"></div>' +
                    '</div>';
                box +=
                    '<div class="list-group-item div_list card rounded border mb-2 left-side-item" ' +
                    '         data-side="left" ' +
                    '         data-type="Pattern" ' +
                    '         data-version="left_tab"' +
                    '         data-title="Black 3D 48"  >' +
                    '   <div class="title-content"> ' +
                    '       <span class="icon_pattern">3D</span>' +
                    '       Black 3D 48 <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"  ></i>' +
                    '   </div>' +
                    '   <div class="card-body macro-list hide_div"></div>' +
                    '</div>';
                // append macros
                box += '<div class=" filtered  div_list title_kind title-kind" data-type="Macros"> Macros  </div>';
                for (var i = 0; i < macros.length; i++) {
                    box +=
                        '<div  style="padding:5px" class="macro_item   div_list card rounded border mb-2 left-side-item" ' +
                        '       data-command="' + macros[i].command + '" data-title="' + macros[i].title + '" data-id="' + macros[i].idmacro_config + '" data-type="Macros">' +
                        '       <div class="card-body p-3">\n' +
                        '                 <div class="media-body float-left">\n' +
                        '                    <div class="title-content col-md-12"> <i class="btn btn-inverse-primary  mdi mdi-server-network"></i> ' + macros[i].title + ' </div>' +
                        '                       <i class="fa fa-arrow-circle-right icon_drag_to_left" aria-hidden="true"></i>' +
                        '                 </div>' +
                        '        </div>' +
                        '</div>';
                }

                $('#dragula-left').html(box);

                var t = $(window).height();
                $("#dragula-left").css("height", t - 135);
                $("#dragula-left").css("max-height", t - 135);
                $("#dragula-left").css("overflow-y", 'auto');
                $("#dragula-right").css("height", t - 135);
                $("#dragula-right").css("max-height", t - 135);
                $("#dragula-right").css("overflow-y", 'auto');

                $("#content-div").css("height", t - 120);
                $("#content-div").css("max-height", t - 120);
                $("#content-div").css("overflow-y", 'auto');

            } catch (e) {
                console.log(e);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            swal.close();
            console.log(errorThrown);
        },
        complete: function (jqXHR, textStatus) {
            swal.close();
        }
    });
}

function formatDurationToHMS(duration) {
    //  console.log(new Date(duration  * 1000).toISOString().slice(11, 19));
    return new Date(duration * 1000).toISOString().slice(11, 19);

}

function convertHMSToSeconds(time) {
    var parts = time.split(":");
    var hours = parseInt(parts[0]);
    var minutes = parseInt(parts[1]);
    var seconds = parseInt(parts[2]);
    return hours * 3600 + minutes * 60 + seconds;
}

function convertSecondsToHMS(seconds) {
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds % 3600) / 60);
    var remainingSeconds = seconds % 60;

    // Add leading zeros if necessary
    hours = String(hours).padStart(2, '0');
    minutes = String(minutes).padStart(2, '0');
    remainingSeconds = String(remainingSeconds).padStart(2, '0');

    return hours + ':' + minutes + ':' + remainingSeconds;
}

function convertStringToSeconds(timeString) {

    var timeParts = timeString.split(':');
    var hours = parseInt(timeParts[0], 10);
    var minutes = parseInt(timeParts[1], 10);
    var seconds = parseInt(timeParts[2], 10);
    return (hours * 3600) + (minutes * 60) + seconds;

}

function formatSize(sizeInBytes) {
    if (sizeInBytes >= 1024 * 1024 * 1024) {
        return (sizeInBytes / (1024 * 1024 * 1024)).toFixed(2) + ' GB';
    } else if (sizeInBytes >= 1024 * 1024) {
        return (sizeInBytes / (1024 * 1024)).toFixed(2) + ' MB';
    } else {
        return sizeInBytes + ' Bytes';
    }
}

function formatTime(seconds) {
    var hours = Math.floor(seconds / 3600);
    var minutes = Math.floor((seconds - (hours * 3600)) / 60);
    var secs = Math.floor(seconds - (hours * 3600) - (minutes * 60));
    return (hours < 10 ? "0" + hours : hours) + ":" + (minutes < 10 ? "0" + minutes : minutes) + ":" + (secs < 10 ? "0" + secs : secs);
}

function uuidv4() {
    return ([1e7] + -1e3 + -4e3 + -8e3 + -1e11).replace(/[018]/g, c =>
        (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
    );
}

function getCplDetails(id_cpl, need_kdm) {
    $.ajax({
        url: 'system/controller_playlist_builder.php',
        type: 'post',

        data: {
            action_control: "get_cpl_details_left_sid",
            id_cpl: id_cpl,
            need_kdm: need_kdm
        },

        success: function (response) {

            var obj = JSON.parse(response);
            var detail_cpl = obj.details_cpl;
            if (detail_cpl == false) {

            }

            var details_spl_contains_cpl = obj.details_spl_contains_cpl;
            $('#date_create_ingest_details').html("Creation Date :" + detail_cpl.date_create_ingest);
            $('#details_title').html(detail_cpl.contentTitleText);
            $('#details_uuid').html(detail_cpl.uuid);
            $('#details_kind').html(detail_cpl.contentKind);
            let duration_Seconds = detail_cpl.durationEdits * detail_cpl.editRate_denominator / detail_cpl.editRate_numerator;

            duration_Seconds = convertSecondsToHMS(Math.round(duration_Seconds));
            $('#details_duration').html(duration_Seconds);
            $('#details_edit_rate').html(detail_cpl.editRate_numerator + " " + detail_cpl.editRate_denominator);
            $('#details_dcp_size').html(formatSize(detail_cpl.packageSizeInBytes));
            $('#details_pictureHeight').html(detail_cpl.pictureHeight);
            $('#details_pictureWidth').html(detail_cpl.pictureWidth);
            $('#details_pictureEncodingAlgorithm').html(detail_cpl.pictureEncodingAlgorithm);
            $('#details_pictureEncryptionAlgorithm').html(detail_cpl.pictureEncryptionAlgorithm);
            $('#details_soundChannelCount').html(detail_cpl.soundChannelCount);
            $('#details_soundEncodingAlgorithm').html(detail_cpl.soundEncodingAlgorithm);
            $('#details_soundEncryptionAlgorithm').html(detail_cpl.soundEncryptionAlgorithm);
            $("#cpl_detail_model_left").modal('show');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}

// open selected spl

function reorderMacroList(macroList) {


    // Get all the media-body elements within the container
    var macroBoxes = Array.from(macroList.querySelectorAll('.media-body.macro-box'));

    // Sort the media-body elements based on the text content of the target element
    macroBoxes.sort(function (a, b) {
        var timeA = a.querySelector('.mb-0.text-muted.float-left').innerText;
        var timeB = b.querySelector('.mb-0.text-muted.float-left').innerText;
        // Assuming the time format is HH:mm:ss, you may need to modify the comparison logic accordingly
        return timeA.localeCompare(timeB);
    });

    // Clear the existing content in the container
    macroList.innerHTML = '';

    // Append the sorted media-body elements back to the container
    macroBoxes.forEach(function (box) {
        macroList.appendChild(box);
    });
}

function extractEditRateValues(editRate) {
    var editRateArray = editRate.split(" ");
    var editRate_numerator = editRateArray[0];
    var editRate_denominator = editRateArray[1];

    return [editRate_numerator, editRate_denominator];
}

function reorderRightList() {
    var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
    var startTime = 0;
    for (var i = 0; i < items.length; i++) {

        var duration = parseInt(items[i].getAttribute('data-time_seconds'));
        items[i].setAttribute('data-starttime', formatTime(startTime));
        var composition_start_time = items[i].getAttribute('data-starttime');
        startTime += duration;
        var composition_end_time = startTime;
        // Process the macro_list within the current item if it exists
        var macroItems = items[i].querySelectorAll('.macro-box ');

        if (macroItems.length > 0) {
            for (var j = 0; j < macroItems.length; j++) {
                var macroTime = macroItems[j].getAttribute('data-time');
                var macroKind = macroItems[j].getAttribute('data-offset');
                // Calculate the macro start time based on Kind
                var macroStartTime;
                if (macroKind === "Start") {
                    console.log(composition_start_time);
                    console.log(macroTime);
                    macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                } else if (macroKind === "End") {
                    console.log(composition_end_time);
                    console.log(macroTime);
                    macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                } else {
                    // Handle other cases if needed
                    macroStartTime = 0;
                }

                // Update the macro_time div with the calculated start time
                macroItems[j].querySelector('.mb-0.text-muted.float-left').innerText = convertSecondsToHMS(macroStartTime);
            }
        }

        var macroList = items[i].querySelector('.card-body.macro-list');
        reorderMacroList(macroList);
    }
}

function setSplOpenedData(capabilities) {
    const obj = { "capabilities": capabilities };
    $('#opened_spl').attr('data-hfr', 0);

    $('#opened_spl').attr('data-mod', "2D");
    if (typeof obj.capabilities === 'object' && obj.capabilities.hasOwnProperty('capability1') && obj.capabilities.capability1 === null) {
        $('#opened_spl').attr('data-mod', "2D");
    } else if (Object.keys(obj.capabilities).length === 0) {

        $('#opened_spl').attr('data-mod', "2D");
    } else {
        for (const key in obj.capabilities) {
            if (obj.capabilities.hasOwnProperty(key)) {
                const capability = obj.capabilities[key];

                if (capability[0] === "HFR_CONTENT") {
                    $('#opened_spl').attr('data-hfr', 1);
                }
                if (capability[0] === "4K_CONTENT") {

                    $('#opened_spl').attr('data-mod', "4k");
                }
                if (capability[0] === "STEREOSCOPIC_CONTENT") {
                    $('#opened_spl').attr('data-mod', "3D");

                }
            }
        }
    }
}

function openSpl(id_spl) {
    $.ajax({
        url: 'system/controller_playlist_builder.php',
        type: 'post',
        data: {
            action_control: "open_spl",
            id_spl: id_spl
        },
        success: function (response) {
            var response = JSON.parse(response);
            var obj = response.spl_file;
            var capabilities = response.capabilities;
            let box = "";

            try {


                $('#opened_spl').attr('data-uuid', obj.Id);


                setSplOpenedData(capabilities);


                if (obj.hasOwnProperty('EventList') && !obj.hasOwnProperty('PackList')) {
                    var pack = [obj.EventList];
                    var obj = {
                        "EventList": obj.EventList
                    };
                    var packList = [obj];

                } else {
                    var packList = obj.PackList;

                    if (Array.isArray(packList.Pack)) {
                        packList = obj.PackList.Pack;

                    } else if (typeof packList.Pack === 'object') {

                        var packList = [packList.Pack];

                    }
                }

                for (var packId in packList) {
                    var pack = packList[packId];

                    if (Object.keys(pack.EventList).length === 0) {
                        box +=
                            '<div class="card rounded border mb-2  segment-style"' +
                            '    data-uuid="' + pack.Id + '"' +
                            '    data-title="' + pack.PackName + '" ' +
                            '    data-type="segment" data-type_component="segment"' +
                            '    data-action="add"> ' +
                            '    <div class="card-body"  > ' +
                            '      <div class="media-body">' +
                            '            <p class="mb-0 text-muted float-left">  ' +
                            '                <i class="btn btn-inverse-warning  mdi mdi-package-variant-closed"></i> ' +
                            pack.PackName +
                            '            </p>' +
                            '            <p class="mb-0 text-muted float-right">\n' +
                            '                 <span class=" ">\n' +
                            '                    <i class="btn btn-primary  mdi mdi-magnify custom-search  segment-details" data-uuid="' + pack.Id + '"></i>\n' +
                            '                    <i class="btn btn-danger mdi mdi-delete-forever remove-cpl custom-search"></i>\n' +
                            '                 </span>   ' +
                            '            </p>' +
                            '      </div>' +
                            '    </div>' +
                            '</div>';
                    } else {

                        var events = pack.EventList.Event;
                        // if (Array.isArray(pack.EventList.Event)) {
                        //     var events = pack.EventList.Event;
                        //
                        // }
                        //
                        // if (typeof pack.EventList.Event === 'object'){
                        //     var  events =[  pack.EventList.Event]  ;
                        //
                        // }
                        // if (typeof events === 'object'){
                        //       events =[events]  ;
                        //
                        // }else{
                        //      events = pack.EventList.Event;
                        // }

                        for (var i = 0; i < events.length; i++) {

                            var macro_box = '';
                            var marker_box = '';
                            var event = events[i];

                            // Access event properties
                            var eventId = event.Id;
                            var ElementList = event.ElementList;
                            var mainElement = event.ElementList.MainElement;
                            var list_macro_style = " hide_div ";
                            var list_marker_style = " hide_div "
                            // check macro
                            if (ElementList.hasOwnProperty('AutomationCue')) {
                                if (Array.isArray(ElementList.AutomationCue)) {
                                    var macro_list = ElementList.AutomationCue;
                                } else {
                                    var macro_list = [ElementList.AutomationCue];
                                }

                                if (macro_list.length > 0) {
                                    for (let i = 0; i < macro_list.length; i++) {
                                        var macro_time = convertSecondsToHMS(macro_list[i].Offset * 1 / 24);
                                        var components_time = extractTimeComponents(macro_time);
                                        macro_box +=
                                            '   <div class="media-body macro-box col-md-8" data-title="' + macro_list[i].Action + '" ' +
                                            '        data-offset="' + macro_list[i].Kind + '"' +
                                            '        data-time="' + convertSecondsToHMS(macro_list[i].Offset * 1 / 24) + '"' +
                                            '        data-time_seconds="' + macro_list[i].Offset * 1 / 24 + '"' +
                                            '        data-hours="' + components_time.hours + '"' +
                                            '        data-minutes="' + components_time.minutes + '"' +
                                            '        data-seconds="' + components_time.seconds + '"' +
                                            '        data-uuid="' + macro_list[i].Id + '"' +
                                            '        data-idmacro="">' + macro_list[i].Action +
                                            '      <p class="mb-0 text-muted float-left">' + convertSecondsToHMS(macro_list[i].Offset * 1 / 24) + '</p> ' +
                                            '      <span class="mb-0 text-muted float-left" style="margin: 0px 2px 0px 20px; ">  ---  </span>' +
                                            '      <p class="mb-0 text-muted float-right">\n' +
                                            '        <span class=" ">\n' +
                                            '            <i class="btn btn-primary  mdi mdi-magnify custom-search  macro-details" style="font-size: 18px; "></i>\n' +
                                            '            <i class="btn btn-danger mdi mdi-delete-forever remove-macro custom-search" style="font-size: 18px;"></i>    ' +
                                            '         </span>   ' +
                                            '       </p>' +
                                            '    </div>';
                                    }
                                    list_macro_style = " "
                                } else {

                                }
                            }
                            // check markers
                            if (ElementList.hasOwnProperty('Marker')) {
                                if (Array.isArray(ElementList.Marker)) {
                                    var Marker_list = ElementList.Marker;
                                } else {
                                    var Marker_list = [ElementList.Marker];
                                }

                                if (Marker_list.length > 0) {
                                    for (let i = 0; i < Marker_list.length; i++) {
                                        var t = convertSecondsToHMS(Marker_list[i].Offset * 1 / 24);
                                        var components_time = extractTimeComponents(t);
                                        marker_box +=
                                            '<div class="media-body marker-box col-md-8"' +
                                            '      data-title="' + Marker_list[i].Label + '" data-offset="' + Marker_list[i].Kind + '"' +
                                            '      data-time="00:01:00" ' +
                                            '      data-uuid="' + Marker_list[i].Id + '" ' +
                                            '      data-hours="' + components_time.hours + '" ' +
                                            '      data-minutes="' + components_time.minutes + '" ' +
                                            '      data-seconds="' + components_time.seconds + '"> ' + Marker_list[i].Label +
                                            '    <p class="mb-0 text-muted float-left"> ' + convertSecondsToHMS(Marker_list[i].Offset * 1 / 24) + '</p> ' +
                                            '    <span class="mb-0 text-muted float-left" style="margin: 0px 2px 0px 20px; ">  ---  </span> ' +
                                            '    <p class="mb-0 text-muted float-right">\n' +
                                            '        <span class=" ">\n' +
                                            '            <i class="btn btn-primary  mdi mdi-magnify custom-search  marker-details" ' +
                                            '               data-uuid="' + Marker_list[i].Id + '" style="font-size: 18px; "></i>\n' +
                                            '            <i class="btn btn-danger mdi mdi-delete-forever remove-marker custom-search" style="font-size: 18px;"></i>    ' +
                                            '         </span>  ' +
                                            '  </p>\n' +
                                            '</div>';
                                        //
                                        // '<div class="col-md-12 marker_item marker_style" style="margin-left: 0px!important;"' +
                                        // ' data-title="' + Marker_list[i].Label + '"' +
                                        // ' data-offset="' + Marker_list[i].Kind + '" ' +
                                        // ' data-time="' + convertSecondsToHMS(Marker_list[i].Offset * 1 / 24) + '"' +
                                        // ' data-uuid="16b06cf1-2e7b-40ef-9807-d22485bb7c36"> ' +
                                        // '   <div class="col-md-2" style="padding-left:12px;">|------</div> ' +
                                        // '   <div class="col-md-2" id="marker_time">' + convertSecondsToHMS(Marker_list[i].Offset * 1 / 24) + '</div>' +
                                        // '   <div class="col-md-6" id="macrker_title">' + Marker_list[i].Label + '</div>  ' +
                                        // '   <i class="fa fa-search col-sd-1 search-marker_item"></i>' +
                                        // '   <i class="fa fa-close col-sd-1  delete-marker_item"></i>' +
                                        // '</div>';
                                    }
                                    list_marker_style = " "
                                } else {

                                }
                                console.log(marker_box);
                            }
                            if (mainElement.hasOwnProperty('Composition')) {

                                var Composition = mainElement.Composition;

                                var IntrinsicDuration = Composition.IntrinsicDuration;
                                var EditRate = extractEditRateValues(Composition.EditRate);
                                var editRate_numerator = EditRate[0];
                                var editRate_denominator = EditRate[1];
                                var duration_seconds = Composition.IntrinsicDuration * editRate_denominator / editRate_numerator;
                                var hms_time = convertSecondsToHMS(duration_seconds);
                                console.log("composition");
                                box +=
                                    '<div class="card rounded border mb-2 left-side-item"' +
                                    '      data-type="Composition"' +
                                    '      data-id="' + eventId + '"' +
                                    '      data-uuid="' + Composition.CompositionPlaylistId + '" ' +
                                    '      data-source="undefined"' +
                                    '      data-title="' + Composition.AnnotationText + '"' +
                                    '      data-editrate_denominator="' + editRate_denominator + '" ' +
                                    '      data-editrate_numerator="' + editRate_numerator + '"' +
                                    '      data-id_server="undefined"' +
                                    '      data-version="new_item" data-time_seconds="' + duration_seconds + '"' +
                                    '      data-time="' + hms_time + '" data-starttime="00:00:00" ' +
                                    '      draggable="false" style=""> ' +
                                    '    <div class="card-body  ">\n' +
                                    '       <div class="media-body  ">\n' +
                                    '            <h6 class="mb-1" style="font-size: 17px;color:#52d4f7">' + Composition.AnnotationText + ' </h6>\n' +
                                    '       </div>\n' +
                                    '      <div class="media-body">\n' +
                                    '            <p class="mb-0 text-muted float-left">00:00:00</p>\n' +
                                    '            <p class="mb-0 text-muted float-right">\n' +
                                    '              <span class=" ">\n' +
                                    '                <i class="btn btn-primary  mdi mdi-magnify custom-search  cpl-details" data-uuid="' + Composition.CompositionPlaylistId + '"  ></i>\n' +
                                    '                <i class="btn btn-danger mdi mdi-delete-forever remove-cpl custom-search"></i>\n' +
                                    '              </span>' +
                                    '             </p>\n' +
                                    '       </div>' +
                                    '    </div>' +
                                    '    <div class="card-body macro-list ' + list_macro_style + '">' + macro_box + '</div> ' +
                                    '    <div class="card-body marker-list  ' + list_marker_style + '">' + marker_box + '</div> ' +
                                    '    <div class="col-md-10 intermission_list"></div>' +
                                    '</div>';
                            } else if (mainElement.hasOwnProperty('Pattern')) {
                                var Pattern = mainElement.Pattern;
                                var IntrinsicDuration = Pattern.Duration;
                                var EditRate = extractEditRateValues(Pattern.EditRate);
                                var editRate_numerator = EditRate[0];
                                var editRate_denominator = EditRate[1];
                                var duration_seconds = Pattern.Duration * editRate_denominator / editRate_numerator;
                                var hms_time = convertSecondsToHMS(duration_seconds);
                                var components_time = secondsToMinutesAndSeconds(duration_seconds);

                                box +=
                                    '<div class="card rounded border mb-2 left-side-item "' +
                                    '      data-side="left" data-type="Pattern" ' +
                                    '      data-id="' + eventId + '"' +
                                    '      data-uuid="' + Pattern.Id + '" ' +
                                    '      data-title="' + Pattern.AnnotationText + '"' +
                                    '      data-editrate_denominator="' + editRate_denominator + '" data-editrate_numerator="' + editRate_numerator + '"' +
                                    '        data-minutes="' + components_time.minutes + '"' +
                                    '        data-seconds="' + components_time.seconds + '"' +
                                    '      data-version="new_item" ' +
                                    '      data-time_seconds="' + duration_seconds + '"' +
                                    '      data-time="' + hms_time + '"' +
                                    '      data-starttime="00:00:00">     ' +
                                    '    <div class="card-body  ">\n' +
                                    '       <div class="media-body  ">\n' +
                                    '          <h6 class="mb-1" style=" font-size: 18px">' + Pattern.AnnotationText + '</h6>\n' +
                                    '       </div>\n' +
                                    '       <div class="media-body">\n' +
                                    '         <p class="mb-0 text-muted float-left color-white">00:00:00</p>\n' +
                                    '         <p class="mb-0 text-muted float-right">\n' +
                                    '            <span class=" ">\n' +
                                    '              <i class="btn btn-primary  mdi mdi-magnify custom-search  pattern-details" data-uuid="urn:uuid:e83235b4-f50d-4f46-906f-2ce2cca1ba52"></i>\n' +
                                    '              <i class="btn btn-danger mdi mdi-delete-forever remove-cpl custom-search"></i>\n' +
                                    '            </span>       ' +
                                    '           </p>\n' +
                                    '       </div>\n' +
                                    '   </div>\n' +
                                    '    <div class="card-body macro-list  ' + list_macro_style + ' ">' + macro_box + '</div> ' +
                                    '    <div class="card-body marker-list   ' + list_marker_style + '">' + marker_box + '</div>' +
                                    '</div>';

                                // '' +
                                // '<div class="card rounded border mb-2 left-side-item "' +
                                // '      data-type="Pattern" ' +
                                // '      data-id="' + eventId + '"' +
                                // '      data-uuid="' + Pattern.Id + '" ' +
                                // '      data-source="undefined"' +
                                // '      data-title="' + Pattern.AnnotationText + '"' +
                                // '      data-editrate_denominator="' + editRate_denominator + '" ' +
                                // '      data-editrate_numerator="' + editRate_numerator + '" data-id_server="undefined"' +
                                // '      data-version="new_item" data-time_seconds="' + duration_seconds + '"' +
                                // '      data-time="' + hms_time + '" data-starttime="00:00:00" ' +
                                // '      draggable="false" style=""> ' +
                                // '   <div class="card-body  ">' +
                                //
                                // (Pattern.AnnotationText == "Black 3D" || Pattern.AnnotationText == "Black 3D 48" ? '<span class="icon_pattern">3D</span> '
                                //     : "") +
                                // Pattern.AnnotationText +
                                // '   </div>  ' +
                                // '   <div class="details-content col-md-3" style="">      ' +
                                // '      <div class=" ">          ' +
                                // '          <i class="fa fa-clock-o" aria-hidden="true"></i>         ' +
                                // '         <span class="start_time">00:00:00</span>       ' +
                                // '      </div> ' +
                                // '  </div>\n' +
                                // '  <i class="fa fa-search col-sd-1 search-spl_item"></i>  ' +
                                // '  <i class="fa fa-close col-sd-1  delete-spl_item"></i>  ' +
                                // '    <div class="col-md-12 macro_list" style="padding :0px"> ' +
                                // macro_box +
                                // '    </div>   ' +
                                // '    <div class="col-md-10 marker_list">' +
                                // marker_box +
                                // '    </div>   ' +
                                // '    <div class="col-md-10 intermission_list"></div>' +
                                // '</div>';

                            }
                            macro_box = "";
                            marker_box = "";
                        }
                    }

                }

                $('#dragula-right').html(box);
                // var items = $('#dragula-right').find('.left-side-item');
                //
                // var startTime = 0;
                // for (var i = 0; i < items.length; i++) {
                //     console.log(items[i]);
                //     var duration = parseInt(items[i].getAttribute('data-time_seconds'));
                //
                //     items[i].setAttribute('data-starttime', formatTime(startTime));
                //     var composition_start_time = items[i].getAttribute('data-starttime');
                //     items[i].querySelector('.mb-0.text-muted.float-left').innerText = formatTime(startTime);
                //     // $(items[i]).find('div:nth-child(2) span').html(formatTime(startTime));
                //     startTime += duration;
                //     var composition_end_time = startTime;
                //     // Process the macro_list within the current item if it exists
                //     var macroItems = items[i].querySelectorAll('.macro_item');
                //     if (macroItems.length > 0) {
                //         for (var j = 0; j < macroItems.length; j++) {
                //             var macroTime = macroItems[j].getAttribute('data-time');
                //             var macroKind = macroItems[j].getAttribute('data-offset');
                //
                //             // Calculate the macro start time based on Kind
                //             var macroStartTime;
                //             if (macroKind === "Start") {
                //                 // console.log(composition_start_time);
                //                 // console.log(macroTime);
                //                 macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                //             } else if (macroKind === "End") {
                //                 // console.log(composition_end_time);
                //                 // console.log(macroTime);
                //                 macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                //             } else {
                //                 // Handle other cases if needed
                //                 macroStartTime = 0;
                //             }
                //
                //             // Update the macro_time div with the calculated start time
                //             macroItems[j].querySelector('#macro_time').innerText = convertSecondsToHMS(macroStartTime);
                //         }
                //     }
                //
                //
                //     var markerItems = items[i].querySelectorAll('.marker_item');
                //
                //     if (markerItems.length > 0) {
                //         for (var j = 0; j < markerItems.length; j++) {
                //             var markerTime = markerItems[j].getAttribute('data-time');
                //             var markerKind = markerItems[j].getAttribute('data-offset');
                //
                //             // Calculate the macro start time based on Kind
                //             var markerStartTime;
                //             if (markerKind === "Start") {
                //
                //                 markerStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(markerTime);
                //
                //             } else if (markerKind === "End") {
                //                 console.log(composition_end_time);
                //                 console.log(markerTime);
                //                 markerStartTime = composition_end_time - convertHMSToSeconds(markerTime);
                //             } else {
                //                 // Handle other cases if needed
                //                 markerTime = 0;
                //                 d
                //             }
                //
                //             // Update the macro_time div with the calculated start time
                //             markerItems[j].querySelector('#marker_time').innerText = convertSecondsToHMS(markerStartTime);
                //         }
                //     }
                // }

                var items = $('#dragula-right').find('.left-side-item:not([data-type="segment"])');
                var startTime = 0;

                for (var i = 0; i < items.length; i++) {
                    var duration = parseInt(items[i].getAttribute('data-time_seconds'));
                    items[i].setAttribute('data-starttime', formatTime(startTime));
                    var composition_start_time = items[i].getAttribute('data-starttime');
                    items[i].querySelector('.mb-0.text-muted.float-left').innerText = formatTime(startTime);
                    startTime += duration;
                    var composition_end_time = startTime;
                    // Process the macro_list within the current item if it exists
                    var macroItems = items[i].querySelectorAll('.macro_item');
                    if (macroItems.length > 0) {
                        for (var j = 0; j < macroItems.length; j++) {
                            var macroTime = macroItems[j].getAttribute('data-time');
                            var macroKind = macroItems[j].getAttribute('data-offset');
                            // Calculate the macro start time based on Kind
                            var macroStartTime;
                            if (macroKind === "Start") {
                                macroStartTime = convertHMSToSeconds(composition_start_time) + convertHMSToSeconds(macroTime);
                            } else if (macroKind === "End") {
                                macroStartTime = composition_end_time - convertHMSToSeconds(macroTime);
                            } else {
                                // Handle other cases if needed
                                macroStartTime = 0;
                            }
                            // Update the macro_time div with the calculated start time
                            macroItems[j].querySelector('#macro_time').innerText = convertSecondsToHMS(macroStartTime);
                        }
                    }
                }
                reorderRightList();
            } catch (e) {
                console.log(e);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        },
        complete: function (jqXHR, textStatus) {
        }
    })
}

function deleteSplSelected(spl_uuid) {

    $.ajax({
        url: 'system/controller_playlist_builder.php',
        type: 'post',
        cache: false,
        data: {
            spl_uuid: spl_uuid,
            action_control: "delete_spl_selected"
        },
        success: function (response) {
            try {
                console.log(response);
                var obj = JSON.parse(response);
                if (obj['status'] === "Failed") {
                    sweetAlert("Oops...", "Something went wrong!", "error");

                }
                if (obj['status'] === "success") {
                    swal("Done!", "Playlist deleted successfully!", "success");
                    $('#order-listing').DataTable().ajax.reload();

                }
            } catch (e) {
                console.log(e);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
        },
        complete: function (jqXHR, textStatus) {
        }
    });
}


function extractTimeComponents(timeString) {
    // Split the time string into hours, minutes, and seconds
    const [hours, minutes, seconds] = timeString.split(':').map(Number);

    return {
        hours,
        minutes,
        seconds
    };
}

function secondsToMinutesAndSeconds(totalSeconds) {
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;

    return {
        minutes,
        seconds
    };
}

function checkAvailability() {
    var spl_title_state = false;
    var spl_title = $('#spl_title').val().trim();
    if (spl_title == '') {
        $('#spl_title').next().removeClass("form_success");
        $('#spl_title').next().addClass("form_error");
        $('#spl_title').next().text('Title cant be empty');
        $('#save_new_spl').prop('disabled', true);
    } else {
        $.ajax({
            url: 'system/controller_playlist_builder.php',
            type: 'post',
            data: {
                'action_control': 'check_spl_file_name',
                'spl_title': spl_title
            },
            success: function (response) {
                console.log(response);
                if (response == 'taken') {
                    spl_title_state = false;
                    $('#spl_title').next().removeClass("form_success");
                    $('#spl_title').next().addClass("form_error");
                    $('#spl_title').next().html('Title Already Taken')
                    $('#save_new_spl').prop('disabled', true);
                } else if (response == 'not_taken') {
                    spl_title_state = true;
                    $('#spl_title').next().removeClass("form_error");
                    $('#spl_title').next().addClass("form_success");
                    $('#spl_title').next().html("Title  Available");

                    $('#save_new_spl').prop('disabled', false);

                }
            }
        });
    }
}
function checkAvailabilityEditForm() {
    var spl_title_state = false;
    var spl_title = $('#edit_spl_title').val().trim();
    if (spl_title == '') {
        $('#edit_spl_title').next().removeClass("form_success");
        $('#edit_spl_title').next().addClass("form_error");
        $('#edit_spl_title').next().text('Title cant be empty');

        $('#confirm_edit_spl_properties').prop('disabled', true);
    } else {
        $.ajax({
            url: 'system/controller_playlist_builder.php',
            type: 'post',
            data: {
                'action_control': 'check_spl_file_name',
                'spl_title': spl_title
            },
            success: function (response) {
                console.log(response);
                if (response == 'taken') {
                    spl_title_state = false;
                    $('#edit_spl_title').next().removeClass("form_success");
                    $('#edit_spl_title').next().addClass("form_error");
                    $('#edit_spl_title').next().html('File Name Already Taken')
                    $('#confirm_edit_spl_properties').prop('disabled', true);
                } else if (response == 'not_taken') {
                    spl_title_state = true;
                    $('#edit_spl_title').next().removeClass("form_error");
                    $('#edit_spl_title').next().addClass("form_success");
                    $('#edit_spl_title').next().html("Title  available");

                    $('#confirm_edit_spl_properties').prop('disabled', false);

                }
            }
        });
    }
}
