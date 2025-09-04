function getSourceIngest() {
    var action_control = "get_screens";
    $.ajax({
        url: '/noc/ingester/action_contoller',
        type: 'post',
        data:{
            'action_control':  action_control,
            //'_token': $('meta[name="csrf-token"]').attr('content'),
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            console.log(response)
            try {
                var obj = JSON.parse(response);
                var ingestIndex = obj.findIndex(function (item) {
                    return item.serverType === "Ingest";
                });

// Move the "Ingest" type to the beginning of the array
                if (ingestIndex !== -1) {
                    var ingestItem = obj.splice(ingestIndex, 1)[0];
                    obj.unshift(ingestItem);
                }

                let box = "";
                box += '  <option value ="Select_Library"> Select Library</option> ';
                for (var i = 0; i < obj.length; i++) {
                    var selected = i === 0 ? 'selected="selected"' : '';
                    box += '<option  data-serverType="' + obj[i].serverType + '" value="' + obj[i].id + '" ' + selected + '>' + obj[i].serverName + '</option>';
                }

                $('#list_source_ingest').html(box);
                $("#list_source_ingest option:first").attr('selected', 'selected');
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

getSourceIngest();

$(document).ready(function () {
    setInterval(function () {
        $('.running-icon').toggleClass('hidden-icon');
    }, 1000); // Toggle the class every 1 second (1000 milliseconds)
});

function scanScreen(screen_id, action, screen_name) {

    $('#select_all_ingest_items').prop('checked', false);
    var xhr = $.ajax({
        url: '/noc/ingester/action_contoller',
        beforeSend: function () {
            swal({
                title: screen_name + ' Now Scanning',
                allowEscapeKey: false,
                allowOutsideClick: true,
                onOpen: () => {
                    swal.showLoading();
                }
            });
        },
        type: 'post',
        data: {
            action_control: action,
            screen_id: screen_id
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            swal.close();
            try {
                //$('#wait').hide();

                let box = "";

                var obj = JSON.parse(response);

                let content = obj.dcp_content;

                let spl_content_screen = obj.spl_content;

                if (obj.session == 0) {

                    $('#result_scan').html('<li id="off_line_box">Could Not Connect To Server</li>');
                    $("#result_scan").css("overflow-y", "hidden");
                } else {
                    if (content.length == 0 && spl_content_screen == null) {
                        $('#result_scan').html('<li id="off_line_box"> Empty Server</li>');
                        $("#result_scan").css("overflow-y", "hidden");
                    } else {



                            for (var i = 0; i < content.length; i++) {

                                if (content[i].type == "CompositionPlaylist") {

                                    let item_exist = (content[i].totalProgress == content[i].totalSize && content[i].totalSize > 0) ? " exist_item" : " ";
                                    let pkl = getPkl(content, content[i].parent_id);
                                    let asset = getAssetMap(content, pkl['parent_id']);
                                    box += '<div class=" border-bottom custom-border ingest-item row ' +
                                        ((content[i].downloaded_to_tms == 1) ? "element-not-loaded" : " ") +

                                        '"' +
                                        '         data-type="CompositionPlaylist"' +
                                        '         data-cpl_id="' + content[i].id + '"' +
                                        '         data-cpl_uri="' + content[i].uri + '"' +
                                        '         data-pkl_uri="' + pkl['uri'] + '"   ' +
                                        '         data-pkl_id="' + pkl['id'] + '"   ' +
                                        '         data-asset_id="' + asset['id'] + '"' +
                                        '         data-asset_uri="' + asset['uri'] + '"' +
                                        '         data-multiple_asset="0"' +
                                        '         data-asset_description="' + asset['description'] + '"' +

                                        '          data-download_status="' + ((content[i].current_status == "pending") ? "pending"
                                            : (content[i].current_status == "running") ? "running" : "1") + '"' +
                                        '> ' +
                                        '   <div class="preview-item cpl" ' +
                                        '             data-uuid="' + content[i].id + '"' +
                                        '             data-type="' + content[i].type + '" ' +
                                        '             data-description="' + content[i].description + '"' +
                                        '             data-is3D="' + content[i].is3D + '"   ' +
                                        '             data-uri="' + content[i].uri + '"' +
                                        '   >' +
                                        '        <div class="icon icon-box-primary"> ' +
                                        '           <i class="mdi mdi-play-protected-content btn btn-success   custom-icon"></i> ' +
                                        '        </div> ' +
                                        '        <div class="preview-item-content d-sm-flex flex-grow">  ' +
                                        '            <div class="flex-grow">\n' +
                                        '                 <h6 class="preview-subject">' + content[i].description +
                                                                                ((content[i].current_status == "pending") ? "<i class=\"mdi mdi-av-timer custom-icon   btn btn-warning \" style='border:0;margin-left: 14px; font-size: 23px; background: none;   color: #ffab00;'></i>"
                                            : (content[i].current_status == "running") ? "<i class=\"btn btn-primary running-icon custom-icon mdi mdi-play-circle-outline\"   style='margin-left: 14px; font-size: 23px; background: none;      border: 0; color: #297EEE;'   \"></i>"
                                                : " "
                                        ) +
                                        '   </h6> ' +
                                        '            </div>' +
                                        '        </div>' +
                                        '   </div>' +
                                        '   <div class="preview-item col-md-12 pkl "' +
                                        '             data-uuid="' + pkl['id'] + '"' +
                                        '             data-type="' + pkl['type'] + '"' +
                                        '             data-description="' + pkl['description'] + '"' +
                                        '             data-uri="' + pkl['uri'] + '">' +

                                        '        <div class="icon icon-box-primary">\n' +
                                        '               <i class="mdi mdi-package btn  btn-primary  custom-icon"></i>\n' +
                                        '        </div>\n' +
                                        '        <div class="preview-item-content d-sm-flex flex-grow">\n' +
                                        '             <div class="flex-grow">\n' +
                                        '                   <h6 class="preview-subject">' + pkl['description'] + '</h6>\n' +
                                        '             </div>\n' +
                                        '         </div>\n' +
                                        '   </div>\n' +
                                        '</div>';

                                }
                            }

                            let spl_content = obj.spl_content;

                            if (!(spl_content == null)) {
                                for (var i = 0; i < spl_content.length; i++) {

                                    box += '<div class=" border-bottom custom-border ingest-item row ' + ((spl_content[i].file_size == spl_content[i].file_progress && spl_content[i].file_size > 0) ? " element-not-loaded" : " ") + '"' +
                                        '         data-type="ShowPlaylist"' +
                                        '             data-uuid="' + spl_content[i].id + '" ' +
                                        '             data-type="' + spl_content[i].type + '" ' +
                                        '             data-description="' + spl_content[i].description + '" ' +
                                        '             data-uri="' + spl_content[i].uri + '"> ' +

                                        '   <div class="preview-item spl" ' +
                                        '             data-uuid="' + spl_content[i].id + '"' +
                                        '             data-type="' + spl_content[i].type + '" ' +
                                        '             data-description="' + spl_content[i].description + '"' +
                                        '             data-is3D="' + spl_content[i].is3D + '"   ' +
                                        '             data-uri="' + spl_content[i].uri + '"' +
                                        '   >' +
                                        '        <div class="icon icon-box-primary"> ' +
                                        '           <i class="mdi mdi-format-list-bulleted-type btn btn-warning custom-icon"></i> ' +
                                        '        </div> ' +
                                        '        <div class="preview-item-content d-sm-flex flex-grow">  ' +
                                        '            <div class="flex-grow">\n' +
                                        '                 <h6 class="preview-subject">' + spl_content[i].description + '</h6> ' +
                                        '            </div>' +
                                        '        </div>' +
                                        '   </div>' +
                                        '</div>';
                                }
                            }



                        $('#result_scan').val("");
                        $('#result_scan').html(box);
                        $("#result_scan").css("overflow-y", "scroll");
                        // let height_parent= $('.background-content').height();
                        // $("#result_scan").css("max-height", height_parent-120);
                        // $("#result_scan").css("overflow-y", "auto");
                    }
                }
            } catch (e) {
                console.log(e);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(errorThrown);
            swal.close();
        },
        complete: function (jqXHR, textStatus) {
            swal.close();
        }
    });
}

$(document).on('click', '#refresh_scan ', function () {
    $('#filter_type').val("all");
    let screen_id = $('#list_source_ingest option:selected').val();
    if (screen_id === "Select_Library") {
        $('#select_all_ingest_items').prop('checked', false);
        $('#result_scan').html('<div class="no-source"> No Source Selected </div>');
        $("#result_scan").css("overflow-y", "hidden");
    } else {
        let screen_name = $('#list_source_ingest option:selected').text();
        scanScreen(screen_id, "refresh_server", screen_name);
    }
});

$('#filter_type').change(function () {

    var criteria = $(this).val();

    if (criteria == 'all') {
        $('.ingest-item').show();
        return;
    }
    $('.ingest-item').each(function (i, option) {
        if ($(this).data("type") == criteria) {

            $(this).show();
        } else {
            $(this).hide();
        }
    });
});
$(document).on('click', '.ingest-item', function (event) {
    $(this).toggleClass("selected");
});

function getPkl(obj, cpl_parent_id) {
    var pkl = [];
    for (var i = 0; i < obj.length; i++) {
        if (obj[i].type == "PackagingList" && obj[i].id == cpl_parent_id) {
            pkl['type'] = obj[i].type;
            pkl['id'] = obj[i].id;
            pkl['uri'] = obj[i].uri;
            pkl['description'] = obj[i].description;
            pkl['parent_id'] = obj[i].parent_id;

            return pkl;
        }
    }
}

function getAssetMap(obj, pkl_parent_id) {
    var asset = [];
    for (var i = 0; i < obj.length; i++) {
        if (obj[i].type == "Assetmap" && obj[i].id == pkl_parent_id) {
            asset['type'] = obj[i].type;
            asset['id'] = obj[i].id;
            asset['uri'] = obj[i].uri;
            asset['description'] = obj[i].description;
            return asset;
        }
    }
}

$('#list_source_ingest').change(function () {
    $('#filter_type').val("all");

    let screen_name = $('#list_source_ingest option:selected').text();
    let screen_id = $('#list_source_ingest option:selected').val();
    if (screen_id === "Select_Library") {
        $('#select_all_ingest_items').prop('checked', false);
        $('#result_scan').html('<div class="no-source"> No Source Selected </div>');
        $("#result_scan").css("overflow-y", "hidden");
    } else {

        scanScreen(screen_id, "scan_server", screen_name);

    }
});

document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select_all_ingest_items');


    const tableCpls = document.getElementById('result_scan');

    selectAllCheckbox.addEventListener('change', function () {

        const cplItems = tableCpls.querySelectorAll('.ingest-item:not([style="display: none;"])');
        if (selectAllCheckbox.checked) {
            cplItems.forEach(function (item) {
                item.classList.add('selected');
            });
        } else {
            cplItems.forEach(function (item) {
                item.classList.remove('selected');
            });
        }
    });
});
// search
var search_screens = document.getElementById('search_ingest_scan');

search_screens.onkeyup = function () {

    var searchTerms = $(this).val();
    $('#result_scan .ingest-item ').each(function () {

        var hasMatch = searchTerms.length == 0 ||
            $(this).text().toLowerCase().indexOf(searchTerms.toLowerCase()) > 0;
        $(this).toggle(hasMatch);

    });

}

$(document).on('click', '#start_ingest', function () {
    var start_ingest   = " ";
    let id_source = $('#list_source_ingest option:selected').val();
    if (id_source == "Select_Library") {
        $("#warning_content").html('No Selected Source !');
        $("#no-screen-modal").modal('show');
    } else {
        var dcp_content = [];
        var spl_content = [];

        $("#result_scan > .selected").each(function () {
            var obj = {};
            if ($(this).data("type") == "CompositionPlaylist") {
                if ($(this).data("download_status") == 1) {
                    obj['cpl_uuid'] = $(this).find(".cpl").data("uuid");
                    obj['cpl_uri'] = $(this).find(".cpl").data("uri");
                    obj['cpl_description'] = $(this).find(".cpl").data("description");
                    obj['is3D'] = $(this).find(".cpl").data("is3d");

                    obj['pkl_uuid'] = $(this).find(".pkl").data("uuid");
                    obj['pkl_uri'] = $(this).find(".pkl").data("uri");
                    obj['pkl_description'] = $(this).find(".pkl").data("description");

                    obj['asset_uuid'] = $(this).data("asset_id");
                    obj['asset_uri'] = $(this).data("asset_uri");
                    obj['asset_description'] = $(this).data("asset_description");
                    obj['multiple_asset'] = $(this).data("multiple_asset");
                    dcp_content.push(obj);
                }

            }
            if ($(this).data("type") == "ShowPlaylist") {

                obj['uuid'] = $(this).data("uuid");
                obj['uri'] = $(this).data("uri");
                obj['description'] = $(this).data("description");
                obj['type'] = $(this).data("type");
                spl_content.push(obj);
            }

        });

        if (dcp_content.length === 0 && spl_content.length === 0) {

            $("#warning_content").html('No Selected File !');
            $("#no-screen-modal").modal('show');
        } else {

            $("#scan").removeClass("show active");
            $("#monitor").addClass("show active");
            $("#scan-tab").removeClass("show active");
            $("#monitor-tab").addClass("show active");

            $.ajax({
                url: '/noc/ingester/action_contoller',
                type: 'post',
                data: {
                    action_control: "start_ingest",
                    id_source: id_source,
                    dcp_content: dcp_content,
                    spl_content: spl_content
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (response) {
                    console.log(response);
                    try {
                        $(document).ready(function () {
                            displayMonitoring();
                        });
                        $("#ingest_tab").removeClass("active");
                        $("#ingest_monitoring").addClass("active");

                        $("#Ingest_scan").removeClass("active");
                        $("#Ingest_monitor").addClass("active");
                        $("#ingest_result > li").removeClass("selected");

                        displayMonitoring();
                        timer=  setInterval(function () {
                            displayMonitoring();
                        }, 2500);
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
    }


});
//*************************** scan logs

$(document).on('click', '#scan_error-tab', function () {
    let height_parent = $('.tab-content').height();
    var height_tab = height_parent - 149;

    $.ajax({
        data: {
            action_control: "get_scan_errors",
        },
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },

        url: '/noc/ingester/action_contoller',
        method: 'POST',
       // dataType: 'json',
        success: function (data) {
            console.log(data) ;
  var obj = JSON.parse(data);
       $('#errors_scan_table tbody').empty();
    if (data === "null" || obj.length === 0 || data ==[]) {


            }else{
                 let box = "";
                       for (var i = 0; i < obj.length; i++) {
                     box += '' +
                    '<tr class="scan-error-item" data-id="'+obj[i].id+'">' +
                        '<td>' + obj[i].id + '</td>' +
                        '<td>' + obj[i].title + '</td>' +
                        '<td>' + obj[i].type + '</td>' +
                        '<td>' + obj[i].source + '</td>' +
                        '<td>' + obj[i].date_time + '</td>' +
                        '<td>' + obj[i].content + '</td>' +
                        '<td>' + obj[i].file_path + '</td>' +
                    '</tr>';
                    }
                       $('#errors_scan_table tbody').html(box);
            }
            // Clear existing table content



        },
        error: function (xhr, status, error) {
            console.error(error);
        }
    });

    // Adjust the height and display the table
    $('#errors_scan_table').css('height', height_tab + 'px');
    $('#errors_scan_table').css('display', 'block');
});
function getScanLogs(){
    $(document).ready(function () {
        let height_parent = $('.tab-content').height();
        var height_tab = height_parent - 149;

        $.ajax({
            data: {
                action_control: "get_scan_errors",
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            url: '/noc/ingester/action_contoller',
            method: 'POST',
           // dataType: 'json',
            success: function (data) {
                console.log(data) ;
      var obj = JSON.parse(data);
           $('#errors_scan_table tbody').empty();
        if (data === "null" || obj.length === 0 || data == []) {


                }else{
                     let box = "";
                           for (var i = 0; i < obj.length; i++) {
                         box += '' +
                        '<tr class="scan-error-item" data-id="'+obj[i].id+'">' +
                            '<td>' + obj[i].id + '</td>' +
                            '<td>' + obj[i].title + '</td>' +
                            '<td>' + obj[i].type + '</td>' +
                            '<td>' + obj[i].source + '</td>' +
                            '<td>' + obj[i].date_time + '</td>' +
                            '<td>' + obj[i].content + '</td>' +
                            '<td>' + obj[i].file_path + '</td>' +
                        '</tr>';
                        }
                           $('#errors_scan_table tbody').html(box);
                }
                // Clear existing table content



            },
            error: function (xhr, status, error) {
                console.error(error);
            }
        });

        // Adjust the height and display the table
        $('#errors_scan_table').css('height', height_tab + 'px');
        $('#errors_scan_table').css('display', 'block');
    });
}
$(document).on('click', '#monitor-tab', function () {

    $(document).ready(function () {
        displayMonitoring();
    });
    timer=  setInterval(function () {
        displayMonitoring();
    }, 2500);
});

function displayMonitoring() {
    $.ajax({
        url: '/noc/ingester/action_contoller',
        method: "POST",
        data: {action_control: "monitor"},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            try {
                let box_running = "";
                let box_pending = "";
                var obj = JSON.parse(response);
                var running_ingest = obj.running;
                var pending_ingest = obj.pending;

                if (running_ingest.length === 0) {
                    $('#table_ingest_running').data("current_status", "0");
                    $('#body_ingest_running').html("");
                    $('#empty_running_list').removeClass("hide_div");
                } else {
                    $('#empty_running_list').addClass("hide_div");
                    for (var i = 0; i < running_ingest.length; i++) {
                        box_running += '<tr class="item_to_select running-item" id="current_download" data-task_status="running" data-current="1" data-id_cpl="' + running_ingest[i].id_cpl + '" data-id_server="' + running_ingest[i].id_server + '" data-status="running" style="font-weight: bold">' +
                            '    <td class="status_control">' + getStatusDownload(running_ingest[i].status) + ' </td>  ' +
                            '    <td>' + running_ingest[i].type + '</td>\n' +
                            '    <td>' + running_ingest[i].cpl_description + '</td>\n' +
                            '    <td>' + running_ingest[i].source + '</td>\n' +
                            '    <td>' + running_ingest[i].date_start_ingesting + '</td>\n' +
                            '    <td>' + running_ingest[i].percentage + ' %</td>' +
                            '</tr>';

                    }
                    $('#body_ingest_running').html(box_running);
                }

                //  pending section
                if (pending_ingest.length === 0) {
                    $('#body_ingest_pending').html("");
                    $('#empty_pending_list').removeClass("hide_div");
                }else{
                    $('#empty_pending_list').addClass("hide_div");
                    for (var i = 0; i < pending_ingest.length; i++) {
                        box_pending += '' +
                            '<tr  class="item_to_select pending-item" data-task_status="pending"data-id_cpl="' + pending_ingest[i].id_cpl + '"  data-id_server="' + pending_ingest[i].id_server + '" data-status="pending">' +
                            '     <td class="py-1 text-warning">\n' +
                            '         <span class="mdi mdi-timer-sand btn btn-warning custom-icon  " style="margin-left: 1px; font-size: 23px; background: none;border: 0; "></span>\n' +
                            '                  Pending\n' +
                            '    </td>  ' +
                            // ' <td class="status_control" style="width: 100px;">'+getStatusDownload(pending_ingest[i].status)+' </td>  ' +
                            '    <td>' + pending_ingest[i].type + '</td>\n' +
                            '    <td>' + pending_ingest[i].cpl_description + '</td>\n' +
                            '    <td>' + pending_ingest[i].source + '</td>\n' +
                            '    <td>' + pending_ingest[i].date_create_ingest + '</td>\n' +

                            '</tr>';
                    }
                    $('#body_ingest_pending').html(box_pending);
                }


                let height_parent = $('.background-content').height();
                $("#tab2-1").css("max-height", height_parent - 30);
                $("#tab2-1").css("overflow-y", "auto");
                $("#tab2-1").css("max-height", height_parent - 30);
                $("#tab2-1").css("overflow-y", "auto");
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

function updateMonitoring() {
    $.ajax({
        url: '/noc/ingester/action_contoller',
        method: "POST",
        data: {action_control: "monitor"},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            try {
                let box_running = "";
                let box_pending = "";
                var obj = JSON.parse(response);
                var running_ingest = obj.running;
                var pending_ingest = obj.pending;
                if ($('#table_ingest_running tbody tr').length > 0) {
                    // Iterate through each row in the table
                    $('#table_ingest_running tbody tr').each(function() {
                        // Get the data-id_cpl attribute value of the current row
                        var idCpl = $(this).attr('data-id_cpl');

                        // Iterate through the array to find the matching object
                        for (var i = 0; i < running_ingest.length; i++) {
                            if (running_ingest[i].id_cpl === idCpl) {
                                // Update the percentage value in the "Overall Progress" column
                                $(this).find('td:last-child').text(running_ingest[i].percentage + ' %');
                                if (running_ingest[i].percentage === 100) {
                                    $(this).remove();
                                    $('#empty_running_list').removeClass("hide_div");
                                }
                                break; // Exit the loop once the match is found
                            }
                        }
                    });
                }


                // pending tab
                if ($('#table_ingest_pending tbody tr').length > 0) {
                    // Iterate through each row in the table
                    $('#table_ingest_pending tbody tr').each(function() {
                        // Get the data-id_cpl attribute value of the current row
                        var idCpl = $(this).attr('data-id_cpl');
                        var found = false; // Flag to track if the idCpl is found in the array
                        // Iterate through the array to find the matching object
                        for (var i = 0; i < pending_ingest.length; i++) {
                            if (pending_ingest[i].id_cpl === idCpl) {
                                found = true; // Set found to true if idCpl is found in the array
                                break; // Exit the loop if idCpl is found
                            }
                        }
                        // If idCpl is not found in the array, remove the row
                        if (!found) {
                            $(this).remove();
                        }
                    });
                }
// Iterate through the pending_ingest array to add missing rows to the table
                for (var i = 0; i < pending_ingest.length; i++) {
                    var idCpl = pending_ingest[i].id_cpl;
                    // Check if the row with the idCpl already exists in the table
                    if (!$('#table_ingest_pending tbody tr[data-id_cpl="' + idCpl + '"]').length) {
                        var newRow_pending =
                            '<tr  class="item_to_select pending-item" data-task_status="pending"data-id_cpl="' + pending_ingest[i].id_cpl + '"  data-id_server="' + pending_ingest[i].id_server + '" data-status="pending">' +
                            '     <td class="py-1 text-warning">\n' +
                            '         <span class="mdi mdi-timer-sand btn btn-warning custom-icon  " style="margin-left: 1px; font-size: 23px; background: none;border: 0; "></span>\n' +
                            '                  Pending\n' +
                            '    </td>  ' +
                             '    <td>' + pending_ingest[i].type + '</td>\n' +
                            '    <td>' + pending_ingest[i].cpl_description + '</td>\n' +
                            '    <td>' + pending_ingest[i].source + '</td>\n' +
                            '    <td>' + pending_ingest[i].date_create_ingest + '</td>\n' +
                            '</tr>';
                        $('#table_ingest_pending tbody').append(newRow_pending);
                    }
                }
                let height_parent = $('.background-content').height();
                $("#tab2-1").css("max-height", height_parent - 30);
                $("#tab2-1").css("overflow-y", "auto");
                $("#tab2-1").css("max-height", height_parent - 30);
                $("#tab2-1").css("overflow-y", "auto");
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

function getStatusDownload(status) {


    let icon =
        (status == "running") ? "<i class=\"btn btn-primary running-icon custom-icon mdi mdi-play-circle-outline\"   style='margin-left: 1px; font-size: 23px; background: none;      border: 0; color: #297EEE;'   \"></i> Running"
            : status == "Complete" ? "<i class='fa fa-check ' aria-hidden='true' style='color: #60eb47'></i> Complete"
                : status == "Failed" ? "<i class='fa  fa-exclamation-triangle' aria-hidden='true' style='color: #ff4545 '></i> Failed"
                    : status == "Canceled By User" ? "<i class='fa fa-close' aria-hidden='true' style='color:#b7b7b7;'></i> <span >Canceled By User</span>"
                        : "<i class='fa fa-clock-o' aria-hidden='true' style='color:#ffcb57;'></i> Pending";

    return icon;
}


$(document).on('click', '.running-item', function (event) {
    $('.pending-item').not(this).removeClass('selected');
    $('.running-item').not(this).removeClass('selected');
    $(this).toggleClass("selected");
});
$(document).on('click', '.pending-item', function (event) {
    $('.pending-item').not(this).removeClass('selected');
    $('.running-item').not(this).removeClass('selected');
    // Toggle the selected class for the clicked row
    $(this).toggleClass('selected');
});

$(document).on('click', '.logs-item', function (event) {
    $('.logs-item').not(this).removeClass('selected');
    // Toggle the selected class for the clicked row
    $(this).toggleClass('selected');
});

$(document).on('click', '.scan-error-item', function (event) {

    $(this).toggleClass('selected');
});


$(document).on('click', '#delete_scan_logs', function (event) {
    var array_logs = [];
    $("#errors_scan_table .scan-error-item.selected").each(function() {
        var id = $(this).data("id");
        array_logs.push(id);
    });



    if (array_logs.length ==  0) {
        $("#empty-logs-warning-modal").modal('show');
    }else{
        $.ajax({
            url: '/noc/ingester/action_contoller',
            type: 'POST',
            data: {
                action_control: "delete_scan_logs",
                array_logs: array_logs
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {
            },
            success: function (response) {
                try {
                    getScanLogs();
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
});
$(document).on('click', '#details_ingest', function (event) {
    var selectedRow = $('#table_ingest_pending tbody tr.selected');
    if (selectedRow.length > 0) {

    } else {
        var selectedRow = $('#table_ingest_running tbody tr.selected');
    }
    if (selectedRow.length > 0) {
        // Get data attributes from the selected row
        var task_status = selectedRow.data('task_status');
        var idCpl = selectedRow.data('id_cpl');
        var idServer = selectedRow.data('id_server');
        var status = selectedRow.data('status');

        $.ajax({
            url: '/noc/ingester/action_contoller',
            type: 'post',
            data: {
                action_control: "details_ingest",
                idCpl: idCpl,
                idServer: idServer
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            },
            success: function (response) {

                try {
                    $("#details-ingest-modal").modal('show');
                    var obj = JSON.parse(response);
                    var dcp = obj.dcp;
                    var box = "";
                    var lis_mxf = obj.mxf;
                    var box_mxf = "";
                    for (var i = 0; i < lis_mxf.length; i++) {
                        box_mxf +=
                            ' <p class=""> FileName : ' + lis_mxf[i].OriginalFileName + '  </p>' +
                            '       <p> Type : ' + lis_mxf[i].Type + ' </p>' +
                            '       <p> Status :' + (lis_mxf[i].status == null ? "Pending" : lis_mxf[i].status) + ' </p>' +
                            '<hr/>'
                    }

                    box += '' +
                        '<div class="tab-pane fade show active row" id="Properties" role="tabpanel" aria-labelledby="Properties-tab">\n' +
                        '  <div class="col-md-6"> ' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  justify-content-start">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class=" mdi mdi-star icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '                                                <h6 class="mb-1 custom-text">Title : </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 custom-text" style="text-align: left; ">' + dcp[0].cpl_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-star icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '                                                <h6 class="mb-1 custom-text">UUID : </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 custom-text" style="text-align: left; "">' + dcp[0].cpl_id + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">PKL :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].pkl_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">ASSET :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].asset_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Source :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].name_source + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                         </div>' +
                        '                         <div class="col-md-6">' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Status :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="  text-transform: uppercase;text-align: left;" >' + dcp[0].status + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Date Create Ingest Task :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; " >' + dcp[0].date_create_ingest + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2" style="border: 0px!important;">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media    justify-content-start mr-5">\n' +
                        '                                            <div class="  col-md-12 row" style="  text-align: left; ">\n' +
                        '                                                <h6 class="mb-1 custom-text col-md-8" style="font-size: 21px;">List Mxf  :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>' +
                        '<div class="card rounded border mb-2" id="list_details_mxf">\n' +
                        '    <div class="card-body p-3">\n' +
                        '       <div class="media    justify-content-start mr-5">\n' +
                        '          <div class="  col-md-12 row" style="  text-align: left; ">\n' +


                        box_mxf +
                        '           </div>\n' +
                        '       </div>\n' +
                        '    </div>\n' +
                        ' </div>' +
                        '                         </div>' +
                        '</div>';


                    $('#ingest_details_content').html(box);

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
    } else {
        $("#no-select-modal").modal('show');
    }
});
$(document).on('click', '#details_logs', function (event) {
    var selectedRow = $('#table_ingest_logs tbody tr.selected');
    if (selectedRow.length > 0) {

    } else {
        var selectedRow = $('#table_ingest_logs tbody tr.selected');
    }
    if (selectedRow.length > 0) {
        // Get data attributes from the selected row
        var task_status = selectedRow.data('task_status');
        var idCpl = selectedRow.data('id_cpl');
        var idServer = selectedRow.data('id_server');

        $.ajax({
            url: '/noc/ingester/action_contoller',
            type: 'post',
            data: {
                action_control: "details_ingest",
                idCpl: idCpl
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            beforeSend: function () {

            },
            success: function (response) {
            console.log(response)
                try {
                    $("#details-ingest-modal").modal('show');
                    var obj = JSON.parse(response);
                    var dcp = obj.dcp;
                    var box = "";
                    var lis_mxf = obj.mxf;
                    var box_mxf = "";
                    for (var i = 0; i < lis_mxf.length; i++) {
                        box_mxf +=
                            ' <p class=""> FileName : ' + lis_mxf[i].OriginalFileName + '  </p>' +
                            '       <p> Type : ' + lis_mxf[i].Type + ' </p>' +
                            '       <p> Status :' + lis_mxf[i].status + ' </p>' +
                            '<hr/>'
                    }

                    box += '' +
                        '<div class="tab-pane fade show active row" id="Properties" role="tabpanel" aria-labelledby="Properties-tab">\n' +
                        '  <div class="col-md-6"> ' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  justify-content-start">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class=" mdi mdi-star icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '                                                <h6 class="mb-1 custom-text">Title : </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 custom-text" style="text-align: left; ">' + dcp[0].cpl_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-star icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '                                                <h6 class="mb-1 custom-text">UUID : </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 custom-text" style="text-align: left; "">' + dcp[0].cpl_id + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">PKL :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].pkl_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">ASSET :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].asset_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Source :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].name_source + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-3">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">TMS PATH  :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].tms_dir + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                         </div>' +
                        '                         <div class="col-md-6">' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Status :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="  text-transform: uppercase;text-align: left;" >' + dcp[0].status + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Date Create Ingest Task :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; " >' + dcp[0].date_create_ingest + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Date Start Ingesting :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; " >' + (dcp[0].date_start_ingesting == null ? " / " : dcp[0].date_start_ingesting) + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Date End  Ingesting :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; " >' + (dcp[0].date_end_ingest == null ? " / " : dcp[0].date_end_ingest) + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2" style="border: 0px!important;">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media    justify-content-start mr-5">\n' +
                        '                                            <div class="  col-md-12 row" style="  text-align: left; ">\n' +
                        '                                                <h6 class="mb-1 custom-text col-md-8" style="font-size: 21px;">List Mxf  :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>' +
                        '                                <div class="card rounded border mb-2" id="list_details_mxf">\n' +
                        '                                   <div class="card-body p-3">\n' +
                        '                                       <div class="media    justify-content-start mr-5">\n' +
                        '                                            <div class="  col-md-12 row" style="  text-align: left; ">\n' +
                        box_mxf +
                        '                                            </div>\n' +
                        '                                      </div>\n' +
                        '                                   </div>\n' +
                        '                               </div>' +
                        '                         </div>' +
                        '</div>';


                    $('#ingest_details_content').html(box);

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
    } else {
        $("#no-select-modal").modal('show');
    }
});
$(document).on('click', '#pause_ingest', function (event) {
    var selectedRow = $('#table_ingest_pending tbody tr.selected');
    if (selectedRow.length > 0) {

    } else {
        var selectedRow = $('#table_ingest_running tbody tr.selected');
    }

    // Check if any row is selected
    if (selectedRow.length > 0) {
        // Get data attributes from the selected row
        var idCpl = selectedRow.data('id_cpl');
        var idServer = selectedRow.data('id_server');
        var status = selectedRow.data('status');

        // Perform your AJAX request here using the data from the selected row
        $.ajax({
            url: 'your_endpoint_url',
            method: 'POST',
            data: {
                idCpl: idCpl,
                idServer: idServer,
                status: status
            },
            success: function (response) {
                // Handle the success response from the server
                console.log('AJAX request successful');
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error('AJAX request error:', error);
            }
        });
    } else {
        $("#no-select-modal").modal('show');
    }
});
$(document).on('click', '#resume_ingest', function (event) {
    var selectedRow = $('#table_ingest_pending tbody tr.selected');

    // Check if any row is selected
    if (selectedRow.length > 0) {
        // Get data attributes from the selected row
        var idCpl = selectedRow.data('id_cpl');
        var idServer = selectedRow.data('id_server');
        var status = selectedRow.data('status');

        // Perform your AJAX request here using the data from the selected row
        $.ajax({
            url: 'your_endpoint_url',
            method: 'POST',
            data: {
                idCpl: idCpl,
                idServer: idServer,
                status: status
            },
            success: function (response) {
                // Handle the success response from the server
                console.log('AJAX request successful');
            },
            error: function (xhr, status, error) {
                // Handle errors
                console.error('AJAX request error:', error);
            }
        });
    } else {
        $("#no-select-modal").modal('show');
    }
});
$(document).on('click', '#cancel_ingest', function (event) {
    var selectedRow = $('#table_ingest_pending tbody tr.selected');
    if (selectedRow.length > 0) {

    } else {
        var selectedRow = $('#table_ingest_running tbody tr.selected');
    }
    // Check if any row is selected
    if (selectedRow.length > 0) {
        // Get data attributes from the selected row
        var task_status = selectedRow.data('task_status');
        var idCpl = selectedRow.data('id_cpl');
        var idServer = selectedRow.data('id_server');
        var status = selectedRow.data('status');

        // Perform your AJAX request here using the data from the selected row

        $.ajax({
            url: '/noc/ingester/action_contoller',
            type: 'post',
            data: {
                action_control: "cancel_ingest",
                idCpl: idCpl,
                idServer: idServer
            },
            data:{
                'action_control':  action_control,
                //'_token': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend: function () {
                $('#task_executing').show();
                $("#wait-modal").modal('show');
            },
            success: function (response) {

                try {
                    var obj = JSON.parse(response);
                    $("#task_executing").hide();
                    $("#status_task_executing").html("task Canceled");

                    displayMonitoring();
                    console.log("111")

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
    } else {
        $("#no-select-modal").modal('show');
    }
});

$(document).on('click', '#logs-tab', function () {
    $(document).ready(function () {
        displayIngestLogs();
    });

});

function displayIngestLogs() {
    $.ajax({
        url: '/noc/ingester/action_contoller',
        method: "POST",
        data: {action_control: "get_logs"},
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function (response) {
            try {
                let box_logs = "";
                let box_pending = "";
                var obj = JSON.parse(response);
                var dcp = obj.dcp;
                var spl = obj.spl;
                if (dcp.length === 0) {
                    $('#body_ingest_logs').html("");
                } else {
                    for (var i = 0; i < dcp.length; i++) {
                        box_logs += '<tr class="item_to_select logs-item" ' +
                            '  data-task_status="' + dcp[i].status + '" ' +
                            ' data-type="DCP"' +
                            '  data-id_cpl="' + dcp[i].cpl_id + '"   style="font-weight: bold">' +
                            '    <td class="status_control">' + getStatusDownload(dcp[i].status) + ' </td>  ' +
                            '    <td>' + dcp[i].cpl_description + '</td>\n' +
                            '    <td>' + dcp[i].serverName + ' </td>\n' +
                            '    <td>DCP</td>\n' +
                            '    <td>' + (dcp[i].date_start_ingesting == null ? dcp[i].date_create_ingest : dcp[i].date_start_ingesting) + ' </td>\n' +
                            '    <td>' + (dcp[i].date_finished_ingest == null ? dcp[i].date_create_ingest : dcp[i].date_finished_ingest) + '  </td>' +
                            '    <td>' + ((dcp[i].hasMxf == 0 || dcp[i].hasMxf == null) && dcp[i].status == "Complete" ? 100 : dcp[i].percentage) + ' % </td>' +
                            '</tr>';

                    }
                    $('#body_ingest_logs').html(box_logs);
                }
                let height_parent = $('.background-content').height();
                $("#tab2-1").css("max-height", height_parent - 30);
                $("#tab2-1").css("overflow-y", "auto");
                $("#tab2-1").css("max-height", height_parent - 30);
                $("#tab2-1").css("overflow-y", "auto");
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


var search_logs = document.getElementById('search_logs');

search_logs.onkeyup = function () {

    var searchTerms = $(this).val();
    $('#body_ingest_logs .logs-item ').each(function () {

        var hasMatch = searchTerms.length == 0 ||
            $(this).text().toLowerCase().indexOf(searchTerms.toLowerCase()) > 0;
        $(this).toggle(hasMatch);

    });

}

$('#filter_logs_type').change(function () {

    var criteria = $(this).val();

    if (criteria == 'All') {
        $('.logs-item').show();
        return;
    }
    $('.logs-item').each(function (i, option) {
        if ($(this).data("type") == criteria) {

            $(this).show();
        } else {
            $(this).hide();
        }
    });
});
$('#filter_logs_status').change(function () {

    var criteria = $(this).val();

    if (criteria == 'All') {
        $('.logs-item').show();
        return;
    }
    $('.logs-item').each(function (i, option) {
        if ($(this).data("task_status") == criteria) {

            $(this).show();
        } else {
            $(this).hide();
        }
    });
});


$(document).on('click', '#details_log', function (event) {
    var selectedRow = $('#table_ingest_pending tbody tr.selected');
    if (selectedRow.length > 0) {

    } else {
        var selectedRow = $('#table_ingest_running tbody tr.selected');
    }
    if (selectedRow.length > 0) {
        // Get data attributes from the selected row
        var task_status = selectedRow.data('task_status');
        var idCpl = selectedRow.data('id_cpl');
        var idServer = selectedRow.data('id_server');
        var status = selectedRow.data('status');

        $.ajax({
            url: '/noc/ingester/action_contoller',
            type: 'post',
            data: {
                action_control: "details_ingest",
                idCpl: idCpl,
                idServer: idServer
            },
            data:{
                'action_control':  action_control,
                //'_token': $('meta[name="csrf-token"]').attr('content'),
            },
            beforeSend: function () {

            },
            success: function (response) {

                try {
                    $("#details-ingest-modal").modal('show');
                    var obj = JSON.parse(response);
                    var dcp = obj.dcp;
                    var box = "";
                    var lis_mxf = obj.mxf;
                    var box_mxf = "";
                    for (var i = 0; i < lis_mxf.length; i++) {
                        box_mxf +=
                            ' <p class=""> FileName : ' + lis_mxf[i].OriginalFileName + '  </p>' +
                            '       <p> Type : ' + lis_mxf[i].Type + ' </p>' +
                            '       <p> Status :' + (lis_mxf[i].status == null ? "Pending" : lis_mxf[i].status) + ' </p>' +
                            '<hr/>'
                    }

                    box += '' +
                        '<div class="tab-pane fade show active row" id="Properties" role="tabpanel" aria-labelledby="Properties-tab">\n' +
                        '  <div class="col-md-6"> ' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  justify-content-start">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class=" mdi mdi-star icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '                                                <h6 class="mb-1 custom-text">Title : </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 custom-text" style="text-align: left; ">' + dcp[0].cpl_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-star icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '                                                <h6 class="mb-1 custom-text">UUID : </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 custom-text" style="text-align: left; "">' + dcp[0].cpl_id + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">PKL :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].pkl_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">ASSET :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].asset_description + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center col-md-2">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Source :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; ">' + dcp[0].name_source + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                         </div>' +
                        '                         <div class="col-md-6">' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Status :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="  text-transform: uppercase;text-align: left;" >' + dcp[0].status + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media  d-flex justify-content-start mr-5">\n' +
                        '                                            <div class="media-body d-flex align-items-center">\n' +
                        '                                                <i class="mdi mdi-format-line-weight icon-sm align-self-center me-3" style="margin-right: 3px!important;"></i>\n' +
                        '\n' +
                        '                                                <h6 class="mb-1 custom-text">Date Create Ingest Task :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0 text-muted m-1"></p>\n' +
                        '                                            </div>\n' +
                        '                                            <div class="media-body">\n' +
                        '                                                <p class="mb-0   custom-text" style="text-align: left; " >' + dcp[0].date_create_ingest + '</p>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>\n' +
                        '                                <div class="card rounded border mb-2" style="border: 0px!important;">\n' +
                        '                                    <div class="card-body p-3">\n' +
                        '                                        <div class="media    justify-content-start mr-5">\n' +
                        '                                            <div class="  col-md-12 row" style="  text-align: left; ">\n' +
                        '                                                <h6 class="mb-1 custom-text col-md-8" style="font-size: 21px;">List Mxf  :  </h6>\n' +
                        '                                            </div>\n' +
                        '                                        </div>\n' +
                        '                                    </div>\n' +
                        '                                </div>' +
                        '<div class="card rounded border mb-2" id="list_details_mxf">\n' +
                        '    <div class="card-body p-3">\n' +
                        '       <div class="media    justify-content-start mr-5">\n' +
                        '          <div class="  col-md-12 row" style="  text-align: left; ">\n' +


                        box_mxf +
                        '           </div>\n' +
                        '       </div>\n' +
                        '    </div>\n' +
                        ' </div>' +
                        '                         </div>' +
                        '</div>';


                    $('#ingest_details_content').html(box);

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
    } else {
        $("#no-select-modal").modal('show');
    }
});


var t = $(window).height()
$(".tab-content").css("height", t - 220);

let parent = $('.tab-content').height();
$("#result_scan").css("height", parent - 107);
$("#result_scan").css("max-height", parent - 107);
$("#result_scan").css("overflow-y", "auto");

$("#div_scan_error").css("height", parent - 40);
$("#div_scan_error").css("max-height", parent - 40);
$("#div_scan_error").css("overflow-y", "auto");

$("#div_ingest_monitor").css("height", parent - 45);
$("#div_ingest_monitor").css("max-height", parent - 45);
$("#div_ingest_monitor").css("overflow-y", "auto");

$("#div_ingest_logs").css("height", parent - 70);
$("#div_ingest_logs").css("max-height", parent - 70);
$("#div_ingest_logs").css("overflow-y", "auto");



