(function ($) {
    // Settings
    function initUserOptionChange() {
        if (
            jQuery(
                'input[name="globalWooCommerceEventsZoomSelectedUserOption"]'
            ).length > 0
        ) {
            jQuery(
                'input[name="globalWooCommerceEventsZoomSelectedUserOption"]'
            ).change(function () {
                enableDisableUsersSelect(jQuery(this).val());
            });
        }
    }

    function enableDisableUsersSelect(val) {
        if (val === "select") {
            jQuery(
                "select#globalWooCommerceEventsZoomSelectedUsers"
            ).removeAttr("disabled");
        } else {
            jQuery("select#globalWooCommerceEventsZoomSelectedUsers").attr(
                "disabled",
                "disabled"
            );
        }
    }

    // Test Access
    if (jQuery("#fooevents_zoom_test_access").length) {
        jQuery(
            "#globalWooCommerceEventsZoomAPIKey, #globalWooCommerceEventsZoomAPISecret"
        ).change(function () {
            jQuery("#globalWooCommerceEventsZoomUsers").hide();

            jQuery("#globalWooCommerceEventsZoomUsersContainer").html(
                '<input type="hidden" name="globalWooCommerceEventsZoomSelectedUsers[]" value="me" />'
            );
        });

        jQuery("#fooevents_zoom_test_access").click(function () {
            jQuery("#globalWooCommerceEventsZoomUsers").hide();

            jQuery("#globalWooCommerceEventsZoomUsersContainer").html(
                '<input type="hidden" name="globalWooCommerceEventsZoomSelectedUsers[]" value="me" />'
            );

            var testButton = jQuery(this);

            jQuery("mark.fooevents-zoom-test-access-result").remove();

            testButton
                .attr("value", zoomObj.testingAccess)
                .prop("disabled", true)
                .after(
                    '<img src="' +
                        zoomObj.adminURL +
                        'images/loading.gif" class="fooevents-ajax-spinner" />'
                );

            var key = jQuery.trim(
                jQuery("#globalWooCommerceEventsZoomAPIKey").val()
            );
            var secret = jQuery.trim(
                jQuery("#globalWooCommerceEventsZoomAPISecret").val()
            );

            var data = {
                action: "fooevents_zoom_test_access",
                key: key,
                secret: secret,
            };

            jQuery.post(ajaxurl, data, function (response) {
                testButton
                    .attr("value", zoomObj.testAccess)
                    .prop("disabled", false);

                jQuery(".fooevents-ajax-spinner").remove();

                var response = JSON.parse(response);

                if (response.status == "error") {
                    testButton.after(
                        '<mark class="error fooevents-zoom-test-access-result"><span class="dashicons dashicons-warning"></span>' +
                            response.message +
                            "</mark>"
                    );
                } else {
                    testButton.after(
                        '<mark class="yes fooevents-zoom-test-access-result"><span class="dashicons dashicons-yes"></span> ' +
                            zoomObj.successFullyConnectedZoomAccount +
                            "</mark>"
                    );

                    jQuery("#globalWooCommerceEventsZoomUsers").show();
                }
            });
        });
    }

    // Users
    initUserOptionChange();

    // Fetch Users
    if (jQuery("#fooevents_zoom_fetch_users").length) {
        jQuery("#fooevents_zoom_fetch_users").click(function () {
            var fetchButton = jQuery(this);
            var selectedUsers = [];
            var userOption = "select";

            if (jQuery("#globalWooCommerceEventsZoomSelectedUsers").length) {
                selectedUsers = jQuery(
                    "#globalWooCommerceEventsZoomSelectedUsers"
                ).val();
            }

            if (
                jQuery(
                    'input[name="globalWooCommerceEventsZoomSelectedUserOption"]:checked'
                ).length
            ) {
                userOption = jQuery(
                    'input[name="globalWooCommerceEventsZoomSelectedUserOption"]:checked'
                ).val();
            }

            fetchButton
                .attr("value", zoomObj.fetchingUsers)
                .prop("disabled", true);

            jQuery("#globalWooCommerceEventsZoomUsersContainer").html(
                '<img src="' +
                    zoomObj.adminURL +
                    'images/loading.gif" class="fooevents-ajax-spinner" />'
            );

            var key = jQuery.trim(
                jQuery("#globalWooCommerceEventsZoomAPIKey").val()
            );
            var secret = jQuery.trim(
                jQuery("#globalWooCommerceEventsZoomAPISecret").val()
            );

            var data = {
                action: "fooevents_zoom_fetch_users",
                key: key,
                secret: secret,
            };

            jQuery.post(ajaxurl, data, function (response) {
                fetchButton
                    .attr("value", zoomObj.fetchUsers)
                    .prop("disabled", false);

                var response = JSON.parse(response);

                if (response.status == "error") {
                    jQuery("#globalWooCommerceEventsZoomUsersContainer").html(
                        '<mark class="error fooevents-zoom-test-access-result"><span class="dashicons dashicons-warning"></span>' +
                            response.message +
                            "</mark>"
                    );
                } else {
                    var userSelect = jQuery(
                        '<select name="globalWooCommerceEventsZoomSelectedUsers[]" id="globalWooCommerceEventsZoomSelectedUsers" multiple class="fooevents-multiselect"></select>'
                    );

                    var userKeys = Object.keys(response.data.users);

                    for (var i = 0; i < userKeys.length; i++) {
                        var userKey = userKeys[i];
                        var user = response.data.users[userKey];

                        userSelect.append(
                            '<option value="' +
                                user.id +
                                '">' +
                                user.first_name +
                                " " +
                                user.last_name +
                                " - " +
                                user.email +
                                "</option>"
                        );
                    }

                    userSelect.val(selectedUsers);

                    var usersHiddenInput = jQuery(
                        '<input type="hidden" name="globalWooCommerceEventsZoomUsers" />'
                    );

                    usersHiddenInput.val(JSON.stringify(response.data.users));

                    jQuery(
                        "#globalWooCommerceEventsZoomUsersContainer"
                    ).empty();

                    jQuery("#globalWooCommerceEventsZoomUsersContainer").append(
                        '<label><input type="radio" name="globalWooCommerceEventsZoomSelectedUserOption" id="globalWooCommerceEventsZoomSelectedUserOptionMe" value="me"> ' +
                            zoomObj.userOptionMe +
                            "</label>" +
                            "<br/><br/>" +
                            '<label><input type="radio" name="globalWooCommerceEventsZoomSelectedUserOption" id="globalWooCommerceEventsZoomSelectedUserOptionSelect" value="select"> ' +
                            zoomObj.userOptionSelect +
                            "</label>" +
                            "<br/><br/>"
                    );

                    initUserOptionChange();

                    jQuery(
                        'input[name="globalWooCommerceEventsZoomSelectedUserOption"][value="' +
                            userOption +
                            '"]'
                    ).attr("checked", "checked");

                    jQuery("#globalWooCommerceEventsZoomUsersContainer").append(
                        userSelect
                    );

                    jQuery("#globalWooCommerceEventsZoomUsersContainer").append(
                        usersHiddenInput
                    );

                    jQuery("#globalWooCommerceEventsZoomUsersContainer").append(
                        "<p>" + zoomObj.userLoadTimes + "</p>"
                    );

                    enableDisableUsersSelect(userOption);
                }
            });
        });
    }
})(jQuery);

function enableCaptureAttendeeDetails() {
    jQuery('input[name="WooCommerceEventsCaptureAttendeeDetails"]').prop(
        "checked",
        true
    );

    jQuery(
        "#fooevents_enable_attendee_details_note #fooevents_capture_attendee_details_disabled"
    ).hide();
    jQuery(
        "#fooevents_enable_attendee_details_note #fooevents_capture_attendee_details_enabled"
    ).show();
}

var fooeventsZoomMeetingRequests = {};

function selectZoomMeeting(selectID) {
    var zoomMeetingID = jQuery("#" + selectID).val();
    var meetingDetailsContainer = jQuery("#" + selectID + "Details");

    meetingDetailsContainer.html(zoomObj.notSet);

    if (
        fooeventsZoomMeetingRequests[selectID] != undefined &&
        fooeventsZoomMeetingRequests[selectID] != null
    ) {
        fooeventsZoomMeetingRequests[selectID].abort();

        fooeventsZoomMeetingRequests[selectID] = null;
    }

    if (zoomMeetingID != "") {
        meetingDetailsContainer.html(
            '<img src="' +
                zoomObj.adminURL +
                'images/loading.gif" class="fooevents-ajax-spinner" />'
        );

        var data = {
            action: "fooevents_fetch_zoom_meeting",
            zoomMeetingID: zoomMeetingID,
        };

        fooeventsZoomMeetingRequests[selectID] = jQuery.post(
            ajaxurl,
            data,
            function (response) {
                fooeventsZoomMeetingRequests[selectID] = null;

                var result = JSON.parse(response);

                if (result.status === "success") {
                    var fooeventsZoomMeeting = result.data;

                    if (
                        fooeventsZoomMeeting.type == 3 ||
                        fooeventsZoomMeeting.type == 6
                    ) {
                        meetingDetailsContainer.html(
                            '<mark class="error fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-warning"></span> ' +
                                (fooeventsZoomMeeting.type == 3
                                    ? zoomObj.noFixedTimeMeeting
                                    : zoomObj.noFixedTimeWebinar) +
                                "</mark><br/><a href='https://zoom.us/" +
                                (fooeventsZoomMeeting.type == 3
                                    ? "meeting"
                                    : "webinar") +
                                "/" +
                                fooeventsZoomMeeting.id +
                                "/edit' target='_blank'>" +
                                (fooeventsZoomMeeting.type == 3
                                    ? zoomObj.editMeeting
                                    : zoomObj.editWebinar) +
                                "</a>"
                        );
                    } else {
                        var zoomMeetingInfoTable = jQuery(
                            '<table class="fooevents-zoom-meeting-info-table"></table>'
                        );

                        if (fooeventsZoomMeeting.agenda !== "") {
                            zoomMeetingInfoTable.append(
                                '<tr><th align="left" valign="top">' +
                                    zoomObj.description +
                                    ":</th><td>" +
                                    fooeventsZoomMeeting.agenda +
                                    "</td></tr>"
                            );
                        }

                        if (
                            fooeventsZoomMeeting.start_date_display !==
                            undefined
                        ) {
                            zoomMeetingInfoTable.append(
                                '<tr><th align="left" width="25%">' +
                                    (fooeventsZoomMeeting.type == 5 ||
                                    fooeventsZoomMeeting.type == 2
                                        ? zoomObj.date
                                        : zoomObj.startDate) +
                                    ":</th><td>" +
                                    fooeventsZoomMeeting.start_date_display +
                                    "</td></tr>"
                            );
                        }

                        if (
                            fooeventsZoomMeeting.start_time_display !==
                            undefined
                        ) {
                            zoomMeetingInfoTable.append(
                                '<tr><th align="left" width="25%">' +
                                    zoomObj.startTime +
                                    ":</th><td>" +
                                    fooeventsZoomMeeting.start_time_display +
                                    "</td></tr>"
                            );
                        }

                        if (
                            fooeventsZoomMeeting.end_time_display !== undefined
                        ) {
                            zoomMeetingInfoTable.append(
                                '<tr><th align="left" width="25%">' +
                                    zoomObj.endTime +
                                    ":</th><td>" +
                                    fooeventsZoomMeeting.end_time_display +
                                    "</td></tr>"
                            );
                        }

                        if (
                            fooeventsZoomMeeting.duration_display !== undefined
                        ) {
                            zoomMeetingInfoTable.append(
                                '<tr><th align="left">' +
                                    zoomObj.duration +
                                    ":</th><td>" +
                                    fooeventsZoomMeeting.duration_display +
                                    "</td></tr>"
                            );
                        }

                        zoomMeetingInfoTable.append(
                            '<tr><th align="left">' +
                                zoomObj.registrations +
                                ":</th><td>" +
                                fooeventsZoomMeeting.registrants.total_records +
                                " / " +
                                fooeventsZoomMeeting.meeting_capacity +
                                "</td></tr>"
                        );

                        if (
                            fooeventsZoomMeeting.type != 5 &&
                            fooeventsZoomMeeting.type != 2
                        ) {
                            zoomMeetingInfoTable.append(
                                '<tr><th align="left">' +
                                    zoomObj.recurrence +
                                    ":</th><td>" +
                                    fooeventsZoomMeeting.recurrence
                                        .type_display +
                                    "</td></tr>"
                            );

                            var occurrences = jQuery("<td></td>");
                            var occurrencesContainer = jQuery(
                                '<div class="fooevents-zoom-occurrences-container"></div>'
                            );

                            for (
                                var i = 0;
                                i < fooeventsZoomMeeting.occurrences.length;
                                i++
                            ) {
                                var occurrence =
                                    fooeventsZoomMeeting.occurrences[i];

                                occurrencesContainer.append(
                                    '<span class="' +
                                        (occurrence.status == "deleted"
                                            ? " fooevents-zoom-occurrence-deleted "
                                            : "") +
                                        '">' +
                                        occurrence.start_date_display +
                                        " " +
                                        occurrence.start_time_display +
                                        " - " +
                                        occurrence.end_time_display +
                                        " (" +
                                        occurrence.duration_display +
                                        ")</span>"
                                );

                                occurrencesContainer.append("<br/>");
                            }

                            occurrences.append(occurrencesContainer);

                            zoomMeetingInfoTable.append(
                                '<tr><th align="left" valign="top">' +
                                    zoomObj.upcomingOccurrences +
                                    ":</th><td>" +
                                    occurrences.html() +
                                    "</td></tr>"
                            );
                        }

                        meetingDetailsContainer.empty();

                        if (
                            fooeventsZoomMeeting.type === 5 ||
                            fooeventsZoomMeeting.type === 2
                        ) {
                            // Once-off meeting
                            if (
                                jQuery(
                                    'input[name="WooCommerceEventsZoomMultiOption"]'
                                ).length &&
                                jQuery(
                                    'input[name="WooCommerceEventsZoomMultiOption"]:checked'
                                ).val() === "single" &&
                                meetingDetailsContainer.parents(
                                    "#fooevents_zoom_meeting_multi"
                                ).length === 0 &&
                                jQuery("#WooCommerceEventsNumDays").val() > 1
                            ) {
                                meetingDetailsContainer.append(
                                    '<mark class="error fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-warning"></span> ' +
                                        (fooeventsZoomMeeting.type === 2
                                            ? zoomObj.notRecurringMeeting
                                            : zoomObj.notRecurringWebinar) +
                                        "</mark><br/>"
                                );
                            }
                        }

                        meetingDetailsContainer.append(zoomMeetingInfoTable);

                        var registrationResult = jQuery(
                            '<span class="fooevents-zoom-registration-result fooevents-zoom-registration-result' +
                                zoomMeetingID +
                                '" data-meeting-id="' +
                                zoomMeetingID +
                                '"></span>'
                        );

                        if (
                            fooeventsZoomMeeting.type === 5 ||
                            fooeventsZoomMeeting.type === 2
                        ) {
                            // Once-off meeting
                            meetingDetailsContainer.append(
                                zoomObj.registrationRequired + "<br/>"
                            );

                            if (
                                fooeventsZoomMeeting.settings.approval_type ===
                                0
                            ) {
                                registrationResult.html(
                                    '<mark class="yes fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-yes"></span> ' +
                                        (fooeventsZoomMeeting.type === 2
                                            ? zoomObj.meetingRegistrationCurrentlyEnabled
                                            : zoomObj.webinarRegistrationCurrentlyEnabled) +
                                        "</mark>"
                                );
                            } else {
                                registrationResult.html(
                                    '<mark class="error fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-warning"></span> ' +
                                        (fooeventsZoomMeeting.type === 2
                                            ? zoomObj.meetingRegistrationCurrentlyDisabled
                                            : zoomObj.webinarRegistrationCurrentlyDisabled) +
                                        '</mark><br/><a class="fooevents-enable-zoom-registration-link' +
                                        zoomMeetingID +
                                        '" href="javascript:fooeventsEnableZoomRegistration(\'' +
                                        fooeventsZoomMeeting.id +
                                        (fooeventsZoomMeeting.type == 5 ||
                                        fooeventsZoomMeeting.type == 6 ||
                                        fooeventsZoomMeeting.type == 9
                                            ? "_webinars"
                                            : "_meetings") +
                                        "', 0);\">" +
                                        (fooeventsZoomMeeting.type === 2
                                            ? zoomObj.enableMeetingRegistration
                                            : zoomObj.enableWebinarRegistration) +
                                        "</a>"
                                );
                            }
                        } else {
                            // Recurring meeting
                            meetingDetailsContainer.append(
                                zoomObj.registrationRequiredForAllOccurrences +
                                    "<br/>"
                            );

                            if (
                                fooeventsZoomMeeting.settings.approval_type ===
                                    0 &&
                                fooeventsZoomMeeting.settings
                                    .registration_type === 1
                            ) {
                                registrationResult.html(
                                    '<mark class="yes fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-yes"></span> ' +
                                        zoomObj.registrationAllOccurrencesEnabled +
                                        "</mark>"
                                );
                            } else {
                                registrationResult.html(
                                    '<a class="fooevents-enable-zoom-registration-link' +
                                        zoomMeetingID +
                                        '" href="javascript:fooeventsEnableZoomRegistration(\'' +
                                        fooeventsZoomMeeting.id +
                                        (fooeventsZoomMeeting.type == 5 ||
                                        fooeventsZoomMeeting.type == 6 ||
                                        fooeventsZoomMeeting.type == 9
                                            ? "_webinars"
                                            : "_meetings") +
                                        "', 1);\">" +
                                        zoomObj.checkRegistrationForAllOccurrences +
                                        "</a>"
                                );
                            }
                        }

                        meetingDetailsContainer.append(registrationResult);
                    }
                } else {
                    meetingDetailsContainer.html(
                        '<mark class="error fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-warning"></span> ' +
                            (zoomMeetingID.indexOf("_meetings")
                                ? zoomObj.unableToFetchMeeting
                                : zoomObj.unableToFetchWebinar) +
                            "</mark>"
                    );
                }

                checkMultiDetailsError();
            }
        );
    }
}

function fooeventsEnableZoomRegistration(zoomMeetingID, recurringMeeting) {
    jQuery(
        "a.fooevents-enable-zoom-registration-link" + zoomMeetingID
    ).replaceWith(
        '<img src="' +
            zoomObj.adminURL +
            'images/loading.gif" class="fooevents-ajax-spinner" />'
    );

    var data = {
        action: "fooevents_update_zoom_registration",
        zoomMeetingID: zoomMeetingID,
        recurringMeeting: recurringMeeting,
    };

    jQuery.post(ajaxurl, data, function (response) {
        var result = JSON.parse(response);

        if (result.status === "success") {
            jQuery(".fooevents-zoom-registration-result" + zoomMeetingID).html(
                '<mark class="yes fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-yes"></span> ' +
                    (recurringMeeting === 1
                        ? zoomObj.registrationAllOccurrencesEnabled
                        : zoomMeetingID.indexOf("_meetings")
                        ? zoomObj.meetingRegistrationCurrentlyEnabled
                        : zoomObj.webinarRegistrationCurrentlyEnabled) +
                    "</mark>"
            );
        } else {
            meetingDetailsContainer.html(
                '<mark class="error fooevents-zoom-test-access-result" style="padding:0;"><span class="dashicons dashicons-warning"></span> ' +
                    zoomObj.unableToFetchMeeting +
                    '</mark><br/><a class="fooevents-enable-zoom-registration-link' +
                    zoomMeetingID +
                    '" href="javascript:fooeventsEnableZoomRegistration(\'' +
                    fooeventsZoomMeeting.id +
                    (fooeventsZoomMeeting.type == 5 ||
                    fooeventsZoomMeeting.type == 6 ||
                    fooeventsZoomMeeting.type == 9
                        ? "_webinars"
                        : "_meetings") +
                    "', " +
                    recurringMeeting +
                    ');">' +
                    recurringMeeting ===
                    0
                    ? fooeventsZoomMeeting.type == 5 ||
                      fooeventsZoomMeeting.type == 6 ||
                      fooeventsZoomMeeting.type == 9
                        ? zoomObj.enableWebinarRegistration
                        : zoomObj.enableMeetingRegistration
                    : zoomObj.enableRegistrationForAllOccurrences + "</a>"
            );
        }
    });
}

function fooeventsZoomShowHideMulti() {
    var multiOption = jQuery(
        'input[name="WooCommerceEventsZoomMultiOption"]:checked'
    ).val();

    if (multiOption == "single") {
        jQuery("#fooevents_zoom_meeting_single").show();
        jQuery("#fooevents_zoom_meeting_multi").hide();
    } else {
        jQuery("#fooevents_zoom_meeting_single").hide();
        jQuery("#fooevents_zoom_meeting_multi").show();
    }
}

function initShowHideMultiDetails() {
    jQuery("a.fooevents-zoom-show-hide-meeting-details-link").click(function (
        e
    ) {
        e.preventDefault();

        var selectID = jQuery(this).attr("data-meeting");
        var meetingDetailsContainer = jQuery("#" + selectID + "Details");

        if (jQuery(this).hasClass("show")) {
            // Hide details
            meetingDetailsContainer.stop().slideUp();

            jQuery(this)
                .removeClass("show")
                .find("span.fooevents-zoom-show-hide-meeting-details-link-text")
                .text(zoomObj.showDetails);
        } else {
            // Show details
            meetingDetailsContainer.stop().slideDown();

            jQuery(this)
                .addClass("show")
                .find("span.fooevents-zoom-show-hide-meeting-details-link-text")
                .text(zoomObj.hideDetails);
        }
    });

    jQuery(".fooevents-zoom-multi-meeting-details-container")
        .text(zoomObj.notSet)
        .hide();
}

function checkMultiDetailsError() {
    var x = 1;

    jQuery(
        "#fooevents_zoom_meeting_multi .fooevents-zoom-registration-result"
    ).each(function () {
        if (jQuery(this).find("mark.error").length) {
            var selectID = jQuery(
                'a[data-meeting="WooCommerceEventsZoomWebinarMulti' + x + '"]'
            ).attr("data-meeting");
            var meetingDetailsContainer = jQuery("#" + selectID + "Details");

            // Show details
            meetingDetailsContainer.show();

            jQuery(
                'a[data-meeting="WooCommerceEventsZoomWebinarMulti' + x + '"]'
            )
                .addClass("show")
                .find("span.fooevents-zoom-show-hide-meeting-details-link-text")
                .text(zoomObj.hideDetails);
        }

        x++;
    });
}

function initZoomSelectChange() {
    if (jQuery("select.WooCommerceEventsZoomSelect").length) {
        jQuery("select.WooCommerceEventsZoomSelect")
            .off("change")
            .change(function () {
                var selectID = jQuery(this).attr("id");

                selectZoomMeeting(selectID);

                if (jQuery('a[data-meeting="' + selectID + '"]').length) {
                    jQuery('a[data-meeting="' + selectID + '"]')
                        .removeClass("show")
                        .click();
                }
            });

        jQuery("select.WooCommerceEventsZoomSelect").each(function () {
            selectZoomMeeting(jQuery(this).attr("id"));
        });
    }

    if (jQuery(".fooevents-search-list").length) {
        jQuery(".fooevents-search-list").select2();
    }
}

(function ($) {
    // Event Integration Settings
    initZoomSelectChange();

    jQuery('input[name="WooCommerceEventsCaptureAttendeeDetails"]').change(
        function () {
            if (jQuery("#fooevents_enable_attendee_details_note").length > 0) {
                if (
                    jQuery(
                        'input[name="WooCommerceEventsCaptureAttendeeDetails"]:checked'
                    ).length == 0
                ) {
                    jQuery(
                        "#fooevents_enable_attendee_details_note #fooevents_capture_attendee_details_disabled"
                    ).show();
                    jQuery(
                        "#fooevents_enable_attendee_details_note #fooevents_capture_attendee_details_enabled"
                    ).hide();
                } else {
                    jQuery(
                        "#fooevents_enable_attendee_details_note #fooevents_capture_attendee_details_disabled"
                    ).hide();
                    jQuery(
                        "#fooevents_enable_attendee_details_note #fooevents_capture_attendee_details_enabled"
                    ).show();
                }
            }
        }
    );

    // Multi-day
    if (jQuery('input[name="WooCommerceEventsZoomMultiOption"]').length) {
        jQuery('input[name="WooCommerceEventsZoomMultiOption"]').change(
            function () {
                fooeventsZoomShowHideMulti();
            }
        );

        fooeventsZoomShowHideMulti();
    }

    if (jQuery("#WooCommerceEventsNumDays").length) {
        jQuery("#WooCommerceEventsNumDays").change(function () {
            var numDays = jQuery(this).val();

            jQuery("#fooevents_zoom_meeting_multi").empty();

            for (var x = 1; x <= numDays; x++) {
                var formField = jQuery('<p class="form-field"></p>');

                if (x == 1) {
                    formField.append(
                        "<label>" +
                            zoomObj.linkMultiMeetingsWebinars +
                            "</label>"
                    );
                }

                formField.append(
                    '<span class="fooevents-zoom-day-override-title">' +
                        jQuery("#fooevents_zoom_meeting_multi").attr(
                            "data-day-term"
                        ) +
                        " " +
                        x
                );

                var zoomSelectClone = jQuery(
                    "select#WooCommerceEventsZoomWebinar"
                )
                    .clone()
                    .attr(
                        "class",
                        "WooCommerceEventsZoomSelect fooevents-search-list"
                    );

                zoomSelectClone.val("");

                zoomSelectClone.attr(
                    "name",
                    "WooCommerceEventsZoomWebinarMulti[]"
                );
                zoomSelectClone.attr(
                    "id",
                    "WooCommerceEventsZoomWebinarMulti" + x
                );

                formField.append(zoomSelectClone);

                var showHideDetailsLink = jQuery(
                    '<a href="#" class="fooevents-zoom-show-hide-meeting-details-link" data-meeting="WooCommerceEventsZoomWebinarMulti' +
                        x +
                        '"><span class="toggle-indicator fooevents-zoom-show-hide-meeting-details" aria-hidden="true"></span>' +
                        '<span class="fooevents-zoom-show-hide-meeting-details-link-text">' +
                        zoomObj.showDetails +
                        "</span></a>"
                );

                formField.append(showHideDetailsLink);

                formField.append(
                    '<img class="help_tip" data-meeting="' +
                        zoomObj.selectMeetingWebinarTooltip +
                        '" src="' +
                        zoomObj.pluginsURL +
                        '/woocommerce/assets/images/help.png" height="16" width="16" />'
                );

                jQuery("#fooevents_zoom_meeting_multi").append(formField);

                jQuery("#fooevents_zoom_meeting_multi").append(
                    '<p class="form-field fooevents-zoom-multi-meeting-details">' +
                        '<span class="fooevents-zoom-multi-meeting-details-container" id="WooCommerceEventsZoomWebinarMulti' +
                        x +
                        'Details">' +
                        zoomObj.notSet +
                        "</span></p>"
                );
            }

            initZoomSelectChange();
            initShowHideMultiDetails();
        });

        initShowHideMultiDetails();
    }
})(jQuery);
