(function($) {

    var fileInput = '';
    
    jQuery('.fooevents-tooltip').tooltip({
        tooltipClass: "fooevents-tooltip-box",
    });
    
    jQuery('.wrap').on('click', '.upload_image_button_woocommerce_events', function(e) {
            e.preventDefault();

            var button = jQuery(this);
            var uploadInput = jQuery(this).parent().prev('input.uploadfield');

            wp.media.editor.send.attachment = function(props, attachment) {
                    jQuery(uploadInput).attr("value", attachment.url);

            };
          
            wp.media.editor.open(button);
            return false;
    });

    jQuery('.upload_reset').click(function() {
        jQuery(this).parent().find('input.uploadfield').val('');
    });

    // user inserts file into post. only run custom if user started process using the above process
    // window.send_to_editor(html) is how wp would normally handle the received data

    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html){

            window.original_send_to_editor(html);

    };
    
    jQuery( "#WooCommerceEventsReport .form-submit a" ).click(function() { 
        var date = this.name;
        jQuery('#WooCommerceEventsDateFrom').val(date);
    });

    jQuery('.woocommerce-events-color-field').wpColorPicker();
    if(jQuery("#WooCommerceEventsProductIsEvent").length) {
                
        if( (typeof localObj === "object") && (localObj !== null) )
        {

            jQuery('#WooCommerceEventsDate').datepicker({

                showButtonPanel: true,
                closeText: localObj.closeText,
                currentText: localObj.currentText,
                monthNames: localObj.monthNames,
                monthNamesShort: localObj.monthNamesShort,
                dayNames: localObj.dayNames,
                dayNamesShort: localObj.dayNamesShort,
                dayNamesMin: localObj.dayNamesMin,
                dateFormat: localObj.dateFormat,
                firstDay: localObj.firstDay,
                isRTL: localObj.isRTL,

            });

        } else {

            jQuery('#WooCommerceEventsDate').datepicker();

        }

        if( (typeof localObj === "object") && (localObj !== null) )
        {

            jQuery('#WooCommerceEventsEndDate').datepicker({

                showButtonPanel: true,
                closeText: localObj.closeText,
                currentText: localObj.currentText,
                monthNames: localObj.monthNames,
                monthNamesShort: localObj.monthNamesShort,
                dayNames: localObj.dayNames,
                dayNamesShort: localObj.dayNamesShort,
                dayNamesMin: localObj.dayNamesMin,
                dateFormat: localObj.dateFormat,
                firstDay: localObj.firstDay,
                isRTL: localObj.isRTL,

            });

        } else {

            jQuery('#WooCommerceEventsEndDate').datepicker();

        }

        jQuery('.wrap').on('change', '#WooCommerceEventsExportUnpaidTicketsExport', function(e) {
            showUpdateExportMessage();
        });
        
        jQuery('.wrap').on('change', '#WooCommerceEventsExportBillingDetailsExport', function(e) {
            showUpdateExportMessage();
        });

    }
    
    function showUpdateExportMessage() {
        
        jQuery('#WooCommerceEventsExportMessage').html('Update product for export options to take affect.');
        
    }


        
    
	
})(jQuery);

(function( $ ) {
    
    jQuery('.woocommerce-events-color-field').wpColorPicker();
    
})( jQuery );

(function( $ ) {
    
    var captureAttendee = true;
    
    jQuery('#WooCommerceEventsEvent').on("change", function(){
        
        var productID = jQuery(this).val();
        
        var dataVariations = {
			'action': 'fetch_woocommerce_variations',
			'productID': productID
		};
                
        jQuery.post(ajaxurl, dataVariations, function(response) {
            
            if(response) {
                
                $('#woocommerce_events_variations').html(response);
            
            }
            
        });
        
        var dataAttendeeCapture = {
			'action': 'fetch_capture_attendee_details',
			'productID': productID
		};
        
        jQuery.post(ajaxurl, dataAttendeeCapture, function(response) {
            
            var details = JSON.parse(response);
            
            if(details.capture == 'off') {
                
                captureAttendee = false;
                
            }
            
        });
        
    });
    
    jQuery('#WooCommerceEventsClientID').on("change", function(){
        
        var userID = jQuery(this).val();
        
        $('#WooCommerceEventsPurchaserFirstName').val('');
        $("#WooCommerceEventsPurchaserFirstName").removeAttr("readonly"); 
        $('#WooCommerceEventsPurchaserEmail').val('');
        $("#WooCommerceEventsPurchaserEmail").removeAttr("readonly"); 
        $('#WooCommerceEventsPurchaserUserName').val('');
        $("#WooCommerceEventsPurchaserUserName").removeAttr("readonly"); 
        
        if(userID) {
            
            var data = {
                            'action': 'fetch_wordpress_user',
                            'userID': userID
                    };
            
            jQuery.post(ajaxurl, data, function(response) {
               
                var user = JSON.parse(response);

                if(user.ID) {
                    
                    $('#WooCommerceEventsPurchaserUserName').val(user.user_login);
                    $("#WooCommerceEventsPurchaserUserName").prop('readonly', true);
                    $('#WooCommerceEventsPurchaserFirstName').val(user.display_name);
                    $("#WooCommerceEventsPurchaserFirstName").prop('readonly', true);
                    $('#WooCommerceEventsPurchaserEmail').val(user.user_email);
                    $("#WooCommerceEventsPurchaserEmail").prop('readonly', true);
                    
                } 
                
            });
                    
        }

    });

    jQuery('#post').submit(function() {
        
            var error = false;
            var addTicket = jQuery('#add_ticket').val();
            
            if(addTicket) {
            
                if(!addTicket) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsEvent').val()) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsPurchaserFirstName').val()) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsPurchaserUserName').val()) {

                    error = true;

                }

                if(!jQuery('#WooCommerceEventsPurchaserEmail').val()) {

                    error = true;

                }

                if(error) {

                    alert('All fields are required');
                    return false;

                }
                
                if(!jQuery('#WooCommerceEventsClientID').val() || jQuery('#WooCommerceEventsClientID').val() == 0) {
                    
                    var data = {
                            'action': 'fooevents_validate_new_user',
                            'email': jQuery('#WooCommerceEventsPurchaserEmail').val(),
                            'display_name': jQuery('#WooCommerceEventsPurchaserFirstName').val()
                    };
            
                    jQuery.post(ajaxurl, data, function(response) {

                        if(response) {
                            
                            var response = JSON.parse(response);
                            alert(response.message);
                            
                            return false;
                        
                        }else {
                            
                            return true;
                            
                        }
                        
                    });

                }
                
            }
        
    });
    
})( jQuery );

(function($) {
    
    var postID = getParameter('post');

    
    jQuery('#WooCommerceEventsResendTicket').on("click", function(){    
        
        jQuery('#WooCommerceEventsResendTicketMessage').html("<div class='notice notice-info'>Sending...</div>");
        var WooCommerceEventsResendTicketEmail = jQuery('#WooCommerceEventsResendTicketEmail').val();
        if(!WooCommerceEventsResendTicketEmail) {
            
            jQuery('#WooCommerceEventsResendTicketMessage').html("<div class='notice notice-error'>Email address required.</div>");
            
        } else {
            
            var data = {
                            'action': 'resend_ticket',
                            'WooCommerceEventsResendTicketEmail': WooCommerceEventsResendTicketEmail,
                            'postID': postID
                    };
            
            jQuery.post(ajaxurl, data, function(response) {
               
                 var email = JSON.parse(response);
                 jQuery('#WooCommerceEventsResendTicketMessage').html("<div class='notice notice-success'>"+email.message+"</div>");
                
            });
            
        }
        
        return false;
    });
     
    function getParameter(name){
        var results = new RegExp('[\?&]' + name + '=([^&#]*)').exec(window.location.href);
        if (results==null){
           return null;
        }
        else{
           return results[1] || 0;
        }
    }
    
})( jQuery );
(function( $ ) {
    
    if ( jQuery('input[name="globalWooCommerceEventsAppEvents"]').length > 0 ) {
        jQuery('input[name="globalWooCommerceEventsAppEvents"]').change(function() {
            if ( jQuery(this).val() === 'id' ) {
                jQuery('select#globalWooCommerceEventsAppEventIDs').removeAttr('disabled');
            } else {
                jQuery('select#globalWooCommerceEventsAppEventIDs').attr('disabled', 'disabled');
            }
        });
    }
    
})( jQuery );

(function( $ ) {
    
    if ( jQuery('#WooCommerceEventsHour').length && jQuery('#WooCommerceEventsHourEnd').length ) {
        jQuery('#WooCommerceEventsHour').change(function() {
            if ( parseInt(jQuery(this).val()) > 12 || parseInt(jQuery('#WooCommerceEventsHourEnd').val()) > 12 ) {
                jQuery('#WooCommerceEventsPeriod').val('').prop('disabled', true);
                jQuery('#WooCommerceEventsEndPeriod').val('').prop('disabled', true);
            } else {
                jQuery('#WooCommerceEventsPeriod').prop('disabled', false);
                jQuery('#WooCommerceEventsEndPeriod').prop('disabled', false);
            }
        });

        jQuery('select#WooCommerceEventsHourEnd').change(function() {
            if ( parseInt(jQuery(this).val()) > 12 || parseInt(jQuery('#WooCommerceEventsHour').val()) > 12 ) {
                jQuery('#WooCommerceEventsPeriod').val('').prop('disabled', true);
                jQuery('#WooCommerceEventsEndPeriod').val('').prop('disabled', true);
            } else {
                jQuery('#WooCommerceEventsPeriod').prop('disabled', false);
                jQuery('#WooCommerceEventsEndPeriod').prop('disabled', false);
            }
        });
    }
    
})( jQuery );

(function( $ ) {
    
    if ( jQuery('#WooCommerceEventsStatusMeta').length && jQuery('#WooCommerceEventsMultidayStatusMeta').length ) {
        jQuery('#WooCommerceEventsStatusMeta').change(function() {
            var status = jQuery(this).val();

            jQuery('#WooCommerceEventsMultidayStatusMeta select').val(status);
        });

        jQuery('#WooCommerceEventsMultidayStatusMeta select').change(function() {
            var thisStatus = jQuery(this).val();
            var changeMain = true;

            jQuery('#WooCommerceEventsMultidayStatusMeta select').each(function() {
                if ( jQuery(this).val() != thisStatus ) {
                    changeMain = false;
                }
            });

            if ( changeMain ) {
                jQuery('#WooCommerceEventsStatusMeta').val(thisStatus);
            } else {
                jQuery('#WooCommerceEventsStatusMeta').val('Not Checked In');
            }
        });
    }
    
})( jQuery );

(function( $ ) {

    function initAddToCalendarReminderRemove() {
        jQuery('.fooevents_add_to_calendar_reminders_remove').off('click');

        jQuery('.fooevents_add_to_calendar_reminders_remove').click(function(e) {
            e.preventDefault();

            jQuery(this).parent().remove();
        });
    }
    
    if ( jQuery('#WooCommerceEventsTicketAddCalendarMeta').length ) {
        initAddToCalendarReminderRemove();

        jQuery('#fooevents_add_to_calendar_reminders_new_field').click(function(e) {
            e.preventDefault();

            var reminderRow = jQuery('<span class="fooevents-add-to-calendar-reminder-row"></span>');

            reminderRow.append('<input type="number" min="0" step="1" name="WooCommerceEventsTicketAddCalendarReminderAmounts[]" value="10">');
            
            var reminderUnitsSelect = jQuery('<select name="WooCommerceEventsTicketAddCalendarReminderUnits[]"></select>');

            var minutesValue = "minutes";
            var hoursValue = "hours";
            var daysValue = "days";
            var weeksValue = "weeks";

            if( (typeof localRemindersObj === "object") && (localRemindersObj !== null) ) {
                minutesValue = localRemindersObj.minutesValue;
                hoursValue = localRemindersObj.hoursValue;
                daysValue = localRemindersObj.daysValue;
                weeksValue = localRemindersObj.weeksValue;
            }

            reminderUnitsSelect.append('<option value="minutes" SELECTED>' + minutesValue + '</option>');
            reminderUnitsSelect.append('<option value="hours">' + hoursValue + '</option>');
            reminderUnitsSelect.append('<option value="days">' + daysValue + '</option>');
            reminderUnitsSelect.append('<option value="weeks">' + weeksValue + '</option>');

            reminderRow.append(reminderUnitsSelect);

            reminderRow.append('<a href="#" class="fooevents_add_to_calendar_reminders_remove">[X]</a>');

            jQuery('#fooevents_add_to_calendar_reminders_container').append(reminderRow);

            initAddToCalendarReminderRemove();
        });
    }
    
})( jQuery );

(function( $ ) {
    
    function setMultiDayDefaultColour() {
        // Background
        if ( jQuery('input#WooCommerceEventsBackgroundColor').val() === '' ) {
            if ( jQuery('select#WooCommerceEventsNumDays').val() > 1 ) {
                jQuery('input#WooCommerceEventsBackgroundColor').wpColorPicker('color', '#16A75D');
            } else {
                jQuery('input#WooCommerceEventsBackgroundColor').val('').change();
            }

            var isBookableEvent = false;

            if ( isBookableEvent ) {
                jQuery('input#WooCommerceEventsBackgroundColor').wpColorPicker('color', '#557DBD');
            }
        }

        // Text
        if ( jQuery('input#WooCommerceEventsTextColor').val() === '' ) {
            jQuery('input#WooCommerceEventsTextColor').wpColorPicker('color', '#FFFFFF');
        }
    }

    if (
            jQuery('input#WooCommerceEventsBackgroundColor').length &&
            jQuery('input#WooCommerceEventsTextColor').length &&
            jQuery('select#WooCommerceEventsNumDays').length
        ) {
        jQuery('select#WooCommerceEventsNumDays').change(function() {
            setMultiDayDefaultColour();
        });
    }

    setMultiDayDefaultColour();
    
})( jQuery );