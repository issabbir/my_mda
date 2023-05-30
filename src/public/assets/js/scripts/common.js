/*Preloader start*/
$(window).on('load', function () {
    $("#loading_page_loader").fadeOut('slow');
});
/*Preloader end*/

function datePicker(selector) {
    var elem = $(selector);
    elem.datetimepicker({
        format: 'YYYY-MM-DD',
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    let preDefinedDate = elem.attr('data-predefined-date');

    if (preDefinedDate) {
        let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD").format("YYYY-MM-DD");
        elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
}

function districts(elem, container, url, decendentElem)
{
    $(elem).on('change', function() {
        let divisionId = $(this).val();
        if( ((divisionId !== undefined) || (divisionId != null)) && divisionId) {
            $.ajax({
                type: "GET",
                url: url+divisionId,
                success: function (data) {
                    $(container).html(data.html);
                    $(decendentElem).html('');
                },
                error: function (data) {
                    alert('error');
                }
            });
        } else {
            $(container).html('');
            $(decendentElem).html('');
        }
    });
}

function thanas(elem, url, container)
{
    $(elem).on('change', function() {
        let districtId = $(this).val();

        if( ((districtId !== undefined) || (districtId != null)) && districtId) {
            $.ajax({
                type: "GET",
                url: url+districtId,
                success: function (data) {
                    $(container).html(data.html);
                },
                error: function (data) {
                    alert('error');
                }
            });
        } else {
            $(container).html('');
        }
    });
}

function selectCpaEmployees(selector, allEmployeesFilterUrl, selectedEmployeeUrl)
{
    $(selector).select2({
        placeholder: "Select",
        allowClear: false,
        ajax: {
            url: allEmployeesFilterUrl, // '/ajax/employees'
            data: function (params) {
                if(params.term) {
                    if (params.term.trim().length  < 1) {
                        return false;
                    }
                } else {
                    return false;
                }

                return params;
            },
            dataType: 'json',
            processResults: function(data) {
                var formattedResults = $.map(data, function(obj, idx) {
                    obj.id = obj.emp_id;
                    obj.text = obj.emp_code+' ('+obj.emp_name+')';
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            },
        }
    });

    if(
        ($(selector).attr('data-emp-id') !== undefined) && ($(selector).attr('data-emp-id') !== null) && ($(selector).attr('data-emp-id') !== '')
    ) {
        selectDefaultCpaEmployee($(selector), selectedEmployeeUrl, $(selector).attr('data-emp-id'));
    }

    $(selector).on('select2:select', function (e) {
        var selectedEmployee = e.params.data;
        var that = this;

        if(selectedEmployee.emp_code) {
            $.ajax({
                type: "GET",
                url: selectedEmployeeUrl+selectedEmployee.emp_id, // '/ajax/employee/'
                success: function (data) {},
                error: function (data) {
                    alert('error');
                }
            });
        }
    });
}

function selectDefaultCpaEmployee(selector, selectedEmployeeUrl, empId)
{
    $.ajax({
        type: 'GET',
        url: selectedEmployeeUrl+empId, //  '/ajax/employee/'
    }).then(function (data) {
        // create the option and append to Select2
        var option = new Option(data.emp_code+' ('+data.emp_name+')', data.emp_id, true, true);
        selector.append(option).trigger('change');

        // manually trigger the `select2:select` event
        selector.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}

function datePickerUsingDiv(divSelector) { // divSelector is the targeted parent div of date input field
    var elem = $(divSelector);
    elem.datetimepicker({
        format: 'YYYY-MM-DD',
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
}

function branches(elem, url, container)
{
    $(elem).on('change', function() {
        let branchId = $(this).val();

        if( ((branchId !== undefined) || (branchId != null)) && branchId) {
            $.ajax({
                type: "GET",
                url: url+branchId,
                success: function (data) {
                    $(container).html(data.html);
                },
                error: function (data) {
                    alert('error');
                }
            });
        } else {
            $(container).html('');
        }
    });
}


function dateTimePicker(selector) {
    var elem = $(selector);
    elem.datetimepicker({
        format: 'YYYY-MM-DD LT',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });

    let preDefinedDate = elem.attr('data-predefined-date');

    if (preDefinedDate) {
        let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD HH:mm").format("YYYY-MM-DD HH:mm A");
        elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
}

function timeRangePicker(Elem1, Elem2){
    let minElem = $(Elem1);
    let maxElem = $(Elem2);

    minElem.datetimepicker({
        format: 'LT',
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });

    maxElem.datetimepicker({
        useCurrent: false,
        format: 'LT',
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });
    minElem.on("change.datetimepicker", function (e) {
        maxElem.datetimepicker('minDate', e.date);
    });
    maxElem.on("change.datetimepicker", function (e) {
        minElem.datetimepicker('maxDate', e.date);
    });

    let preDefinedDateMin = minElem.attr('data-predefined-date');
    let preDefinedDateMax = maxElem.attr('data-predefined-date');

    if (preDefinedDateMin) {
        let preDefinedDateMomentFormat = moment(preDefinedDateMin, "YYYY-MM-DD HH:mm").format("HH:mm A");
        minElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }

    if (preDefinedDateMax) {
        let preDefinedDateMomentFormat = moment(preDefinedDateMax, "YYYY-MM-DD HH:mm").format("HH:mm A");
        maxElem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
}

function selectBookings(selector, allBookingsFilterUrl, selectedBookingUrl, callback, excludesCallback)
{
    $(selector).select2({
        placeholder: "Select",
        allowClear: false,
        ajax: {
            url: allBookingsFilterUrl,
            data: function (params) {
                var query = {
                    term: params.term,
                    exclude: excludesCallback
                }

                return query;
            },
            dataType: 'json',
            processResults: function(data) {
                var formattedResults = $.map(data, function(obj, idx) {
                    obj.id = obj.booking_mst_id;
                    obj.text = obj.booking_no;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            },
        }
    });

    if(
        ($(selector).attr('data-booking-id') !== undefined) && ($(selector).attr('data-booking-id') !== null) && ($(selector).attr('data-booking-id') !== '')
    ) {
        selectDefaultBooking($(selector), selectedBookingUrl, $(selector).attr('data-booking-id'));
    }

    $(selector).on('select2:select', function (e) {
        var selectedBooking = e.params.data;
        var that = this;

        if(selectedBooking.booking_no) {
            $.ajax({
                type: "GET",
                url: selectedBookingUrl+selectedBooking.booking_mst_id,
                success: function (data) {
                    callback(that, data);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }
    });
}

function selectDefaultBooking(selector, selectedBookingUrl, bookingId)
{
    $.ajax({
        type: 'GET',
        url: selectedBookingUrl+bookingId,
    }).then(function (data) {
        var info = data.booking;
        // create the option and append to Select2
        var option = new Option(info.booking_no, info.booking_mst_id, true, true);
        selector.append(option).trigger('change');

        // manually trigger the `select2:select` event
        selector.trigger({
            type: 'select2:select',
            params: {
                data: info
            }
        });
    });
}

function formSubmission(formElem, clickedElem, callback, message)
{
    $(clickedElem).click(function(e) {
        e.preventDefault();
        callback(formElem);
        var isValid = $(formElem).valid();

        if(isValid) {
            var confirmation = confirm(message);
            if(confirmation == true) {
                $(formElem).submit();
            }
        }
    });
}



function selectAgency(selector, allAgencyFilterUrl, selectedAgencyUrl, callback)
{
    $(selector).select2({
        placeholder: "Select",
        allowClear: false,
        ajax: {
            url: allAgencyFilterUrl, // '/ajax/employees'
            data: function (params) {
                if(params.term) {
                    if (params.term.trim().length  < 1) {
                        return false;
                    }
                } else {
                    return false;
                }

                return params;
            },
            dataType: 'json',
            processResults: function(data) {
                var formattedResults = $.map(data, function(obj, idx) {
                    obj.id = obj.agency_id;
                    obj.text = obj.agency_name;
                    return obj;
                });
                return {
                    results: formattedResults,
                };
            },
        }
    });

    if(
        ($(selector).attr('data-agency-id') !== undefined) && ($(selector).attr('data-agency-id') !== null) && ($(selector).attr('data-agency-id') !== '')
    ) {
        selectDefaultAgency($(selector), selectedAgencyUrl, $(selector).attr('data-agency-id'));
    }

    $(selector).on('select2:select', function (e) {
        var selectedAgency = e.params.data;
        var that = this;

        if(selectedAgency.agency_name) {
            $.ajax({
                type: "GET",
                url: selectedAgencyUrl+selectedAgency.agency_id, // '/ajax/employee/'
                success: function (data) {
                    callback(that, data);
                },
                error: function (data) {
                    alert('error');
                }
            });
        }
    });
}

function selectDefaultAgency(selector, selectedAgencyUrl, agencyId)
{
    $.ajax({
        type: 'GET',
        url: selectedAgencyUrl+agencyId, //  '/ajax/employee/'
    }).then(function (data) {
        // create the option and append to Select2
        var option = new Option(data.agency_name, data.agency_id, true, true);
        selector.append(option).trigger('change');

        // manually trigger the `select2:select` event
        selector.trigger({
            type: 'select2:select',
            params: {
                data: data
            }
        });
    });
}


$('.mobile-validation').on('keypress', function(e) {
    // e is event.
    var keyCode = e.which;
    /*
      8 - (backspace)
      32 - (space)
      48-57 - (0-9)Numbers
    */

    if ( (keyCode != 8 || keyCode ==32 ) && (keyCode < 48 || keyCode > 57)) {
        return false;
    }
});

function timePicker(selector) {
    var elem = $(selector);
    elem.datetimepicker({
        format: 'LT',
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });

    let preDefinedDate = elem.attr('data-predefined-date');

    if (preDefinedDate) {
        let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD HH:mm").format("YYYY-MM-DD HH:mm A");
        elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
}

function timePicker24(selector) {
    var elem = $(selector);
    elem.datetimepicker({
        format: 'H:mm',
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });

    let preDefinedDate = elem.attr('data-predefined-date');

    if (preDefinedDate) {
        let preDefinedDateMomentFormat = moment(preDefinedDate, "YYYY-MM-DD HH:mm").format("YYYY-MM-DD HH:mm A");
        elem.datetimepicker('defaultDate', preDefinedDateMomentFormat);
    }
}

function msToTime(duration) {
    var milliseconds = parseInt((duration % 1000) / 100),
        seconds = Math.floor((duration / 1000) % 60),
        minutes = Math.floor((duration / (1000 * 60)) % 60),
        hours = Math.floor((duration / (1000 * 60 * 60)) % 24);

    hours = (hours < 10) ? "0" + hours : hours;
    minutes = (minutes < 10) ? "0" + minutes : minutes;
    seconds = (seconds < 10) ? "0" + seconds : seconds;

    return hours + ":" + minutes;
}

function convertDate(inputFormat) {
    function pad(s) {
        return (s < 10) ? '0' + s : s;
    }

    var d = new Date(inputFormat)
    return [pad(d.getDate()),pad(d.getMonth() + 1), d.getFullYear() ].join('-')
}


//==========================================

$(document).ready(function () {

    $(document).on("click", '.confirm-delete', function (e) {
        e.preventDefault();
        let that = $(this);
        let link = that.attr("href");
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        swal({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            type: "warning",
            showCancelButton: !0,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Yes, delete it!",
            confirmButtonClass: "btn btn-primary",
            cancelButtonClass: "btn btn-danger ml-1",
            buttonsStyling: !1
        }).then(function (e) {
            if (e.value === true) {
                $.ajax({
                    type: 'DELETE',
                    url: link,
                    data: {_token: CSRF_TOKEN},
                    dataType: 'JSON',
                    success: function (results) {
                        if (results.success === true) {
                            let table = that.closest('div').find('.datatable').DataTable();
                            table.ajax.reload(null, false); // user paging is not reset on reload
                            swal("Done!", results.message, "success");
                        } else {
                            swal("Error!", results.message, "error");
                        }
                    }
                });

            } else {
                e.dismiss;
            }

        }, function (dismiss) {
            return false;
        })
    });


    //  Add more table Start
    var count = 0;
    $(document).on("click", ".phn_btn_more", function (e) {
        e.preventDefault();
        if ($('.officer_name').data('select2')) {
            $(".cloned-row:last").find('.officer_name').select2('destroy');
        }
        var $clone = $('.cloned-row:last').clone();
        // var $clone = $('.cloned-row:eq(0)').clone();
        //alert("Clone number" + clone);
        $clone.find('[id]').each(function () {
            this.id += 'someotherpart'
        });
        // $clone.find('.phn_btn_more').after("<a  href='#' class='text-danger btn_less1'  id='buttonless'><i  class='bx bxs-minus-circle cursor-pointer font-medium-3'></i></a>")
        $clone.attr('id', "added" + (++count));
        var officerID = "officer_name"+count;
        var cloneID = $clone.find('.select2-hidden-accessible').attr('id', officerID);
        $clone.find('.select2-hidden-accessible').attr('data-select2-id', officerID);
        $clone.find('.select2-selection__rendered').attr('id', officerID);
        // console.log(cloneID);

        // $clone.find('.counter').html(count + 1);
        $(this).parents('.em_pho').after($clone);


        $clone.find('input').val('');
        // $clone.find("select").val("").change()
        $clone.find(".external").show();
        $clone.find(".officer").hide();
        $clone.find(".stake_holder").hide();

        let url = $(this).attr('data-url');
        console.log(url);
        $('.select-officer').select2({
            minimumInputLength: 1,
            dropdownPosition: 'below',
            ajax: {
                url: url,
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        emp_name: params.term
                    };
                },
                processResults: function (data) {
                    return {
                        results: $.map(data.results, function (obj) {
                            return {
                                id: obj.id,
                                text: obj.emp_name + '-' + obj.emp_code,
                                department: obj.department
                            };
                        })
                    };
                },
                cache: false
            },

        });
        $clone.find(".select-officer").last().next().next().remove();
        // $clone.find('.'+officerID).last().next().next().remove();
        try {
            $clone.find(".btn_less1").attr('data-participant_id', '');
        }catch (e) {

        }
    });

    $(document).on('click', ".btn_less1", function (e) {
        e.preventDefault();
        var len = $('.cloned-row').length;
        if (len > 1) {
            $(this).parents('.em_pho').remove();

            //deleted id
            try{
                var deleted_participant_id = $(this).data('participant_id');
                var deleted_participants = $('#deleted_participants').val();
                if(deleted_participants.length == 0){
                    if(deleted_participant_id !=''){
                        $('#deleted_participants').val(deleted_participant_id);
                    }
                }else{
                    if(deleted_participant_id !='') {
                        $('#deleted_participants').val(deleted_participants + ',' + deleted_participant_id);
                    }
                }

            }catch (e) {}
        }
    });
    //  Add more table End


    <!---DateTime Picker Start-->
    $('.datetimepicker').datetimepicker({
        //format: 'DD-MM-YYYY',
        format: 'DD-MM-YYYY LT',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            date: 'bx bxs-calendar',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right',
            today: 'bx bxs-calendar-check',
            clear: 'bx bx-trash',
            close: 'bx bx-window-close'
        }
    });


    $('.timePiker').datetimepicker({
        format: 'LT',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            time: 'bx bx-time',
            up: 'bx bx-up-arrow-alt',
            down: 'bx bx-down-arrow-alt'
        }
    });
    $('.datePiker').datetimepicker({
        format: 'DD-MM-YYYY',
        widgetPositioning: {
            horizontal: 'left',
            vertical: 'bottom'
        },
        icons: {
            date: 'bx bxs-calendar',
            previous: 'bx bx-chevron-left',
            next: 'bx bx-chevron-right'
        }
    });
    <!---DateTime Picker End-->

    //  Add discussion table Start
    $(document).on("click", ".add_discussion", function (e) {
        e.preventDefault();
        var $tr  = $(this).closest('.discussion-cloned-row');
        var $clone = $tr.clone();
        $clone.find('textarea').val('');
        $clone.find('input').val('');
        $tr.after($clone);
    });

    $(document).on('click', ".discussion_btn_less1", function (e) {
        e.preventDefault();
        var len_discussion_cloned_row = $('.discussion-cloned-row').length;
        if (len_discussion_cloned_row > 1) {
            $(this).closest('.discussion-cloned-row').remove();

            //deleted id
            try{
                var discussion_decision_id = $(this).data('deleted_discussion_decision_id');
                var deleted_discussion_decisions = $('#deleted_discussion_decisions').val();
                if(deleted_discussion_decisions.length == 0){
                    if(discussion_decision_id !=''){
                        $('#deleted_discussion_decisions').val(discussion_decision_id);
                    }
                }else{
                    if(discussion_decision_id !='') {
                        $('#deleted_discussion_decisions').val(deleted_discussion_decisions + ',' + discussion_decision_id);
                    }
                }

            }catch (e) {}
        }
    });
    //  Add discussion table End

    //Input type only  number allowed and input limit with maxlength value
    $(document).on('input keypress','.only-number[maxlength]:not([maxlength=""])', function(ev) {
        var $this = $(this);
        var maxlength = $this.attr('maxlength');
        if (ev.which != 8 && ev.which != 0 && (ev.which < 48 || ev.which > 57)) {
            return false;
        }
        // var value = $this.val();
        // if (value && value.length >= maxlength) {
        //   $this.val(value.substr(0, maxlength));
        // }
    });





    //  Add attach table Start
    $(document).on("click", ".attach_btn_more", function (e) {
        e.preventDefault();
        var $tr  = $(this).closest('.attach-cloned-row');
        var $clone = $tr.clone();
        $clone.find('textarea').val('');
        $clone.find('input').val('');
        $tr.after($clone);
    });

    $(document).on('click', ".attach_btn_less1", function (e) {
        e.preventDefault();
        var len_attach_cloned_row = $('.attach-cloned-row').length;
        if (len_attach_cloned_row > 1) {
            $(this).closest('.attach-cloned-row').remove();

            //deleted id
            try{
                var deleted_attachment_id = $(this).data('deleted_attachment_id');
                // console.log(deleted_attachment_id);
                var deleted_attachment = $('#deleted_attachment').val();

                if(deleted_attachment.length == 0){

                    if(deleted_attachment_id !=''){
                        $('#deleted_attachment').val(deleted_attachment_id);
                    }
                }else{
                    if(deleted_attachment_id !='') {
                        $('#deleted_attachment').val(deleted_attachment + ',' + deleted_attachment_id);
                    }
                }

            }catch (e) {}
        }
    });
    //  Add attach table End


});
