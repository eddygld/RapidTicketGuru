<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FooEvents Attendee Badges</title>
    <style>
        html, body, div, h1, h2 {
            vertical-align: baseline;
            margin: 0;
            padding: 0;
            border: 0;
        }

        html {
            line-height: 1;
        }

        ol, ul {
            padding: 0;
            white-space: normal;
        }
        
        .fooevents_clear {
            clear: both;
        }

        .fooevents_letter_10, .fooevents_letter_30 {
            width: 8.5in;
            padding: .50in 0 0 .30in;
        }

        .fooevents_letter_6 {
            width: 8.25in;
            padding: 1in 0 0 .25in;
        }

        .fooevents_letter_labels_5 {
            width: 11in;
            padding: 0.375in 0 0 1.25in;
        }

        .fooevents_letter_labels_5 .fooevents_ticket {
            width: 9.75in;
            height: 1.45in;
            margin-bottom: 0.375in;
        }

        .fooevents_ticket_page.fooevents_ticket_line_on.fooevents_letter_labels_5 .fooevents_ticket {
            border-top: 1px dotted #cccccc;
            border-left: 1px dotted #cccccc;
        }

        .fooevents_letter_6 .fooevents_ticket {
            padding: .025in .3in 0;
            width: 4in;
            height: 3in;
        }

        .fooevents_a4_12 {
            width: 190.5mm;
            padding: 4.5mm 9.75mm;
        }

        .fooevents_a4_16 {
            width: 198mm;
            padding: 12.9mm 6mm;
        }

        .fooevents_a4_24 {
            width: 210mm;
            padding: 9mm 0 8mm 0;
        }
        
        .fooevents_a4_39 {
            width: 198mm;
            padding: 14.015mm 6mm;
        }

        .fooevents_a4_45 {
            width: 192.5mm;
            padding: 13.95mm 8.75mm;
        }

        .fooevents_a4_12 .fooevents_ticket {
            width: 63.5mm;
            height: 72mm;
        }

        .fooevents_a4_16 .fooevents_ticket {
            width: 99mm;
            height: 33.9mm;
        }

        .fooevents_a4_24 .fooevents_ticket {
            width: 70mm;
            height: 35mm;
        }
        
        .fooevents_a4_39 .fooevents_ticket {
            width: 66mm;
            height: 20.69mm;
        }

        .fooevents_a4_45 .fooevents_ticket {
            width: 38.5mm;
            height: 29.9mm;
        }

        .fooevents_badge_page, .fooevents_ticket_page {
            margin: 0;
            font-family: Arial, Sans-serif;
            page-break-after: always;
        }

        .fooevents_letter_10 .fooevents_ticket, .fooevents_letter_30 .fooevents_ticket {
            padding: .025in .3in 0;
            margin-right: .18in;
            margin-top: .025in;
        }

        .fooevents_badge {
            float: left;
            overflow: hidden;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            position: relative;
        }

        .fooevents_badge_line_on {
            outline: 1px dotted #ccc;
        }

        .fooevents_letter_10 .fooevents_ticket {
            width: 4.025in;
            height: 2.05in;
        }

        .fooevents_letter_30 .fooevents_ticket {
            width: 2.625in;
            height: 1in;
        }

        .fooevents_badge_16 .fooevents_event_car {
            margin: 7px auto;
            display: block;
        }

        .fooevents_badge_39 .fooevents_event_car, .fooevents_badge_45 .fooevents_event_car {
            margin: 3px auto;
            display: block;
        }

        .fooevents_badge_30 .fooevents_event_car, .fooevents_badge_24 .fooevents_event_car {
            font-size: 10px;
        }
        
        .fooevents_badge_10 .fooevents_event_car, .fooevents_badge_12 .fooevents_event_car {
            font-size: 16px;
            line-height: 20px;
        }

        .fooevents_badge_10 h3, .fooevents_badge_12 h3 {
            font-size: 18px;
            margin-top: 20px;
        }

        .fooevents_badge_30 img, .fooevents_badge_16 img, .fooevents_badge_24 img {
            width: 130px;
        }

        .fooevents_badge_39 img, .fooevents_badge_45 img {
            width: 100px;
        }
        
        .fooevents_badge_inner {
            margin: 0;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 90%;
            overflow-wrap: break-word;
            word-wrap: break-word;
        }
        
        .fooevents_ticket {
            float: left;
            overflow: hidden;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
            position: relative;
        }

        .fooevents_ticket h1, .fooevents_ticket h3 {
            margin: 0px;
        }        
       
        .fooevents_ticket_small, .fooevents_ticket_small_uppercase {
            font-size: 70%;
            line-height: 100%;
            opacity: 0.7
        }

        .fooevents_ticket_medium, .fooevents_ticket_medium_uppercase {
            font-size: 100%;
            line-height: 100%;
            margin: 0px;
        }

        .fooevents_ticket_large, .fooevents_ticket_large_uppercase {
            font-size: 150%;
            line-height: 100%;
            font-weight: normal;
        }

        .fooevents_ticket_small b, .fooevents_ticket_small_uppercase b {
            opacity: 0.9;
            padding-right: 5px;
        }

        .fooevents_ticket_medium b, .fooevents_ticket_medium_uppercase b {
            opacity: 0.9;
            padding-right: 5px;
        }

        .fooevents_ticket_small_uppercase, .fooevents_ticket_medium_uppercase, .fooevents_ticket_large_uppercase {
            text-transform: uppercase;
        }        

        .fooevents_ticket_hr {
            width: 30%;
            opacity: 0.3;
            margin-bottom: 0;
            margin-top: 10px;
        }

        .fooevents_ticket_spacer {
            width: 100%;
            height: 10px;
        }

        .fooevents_tickets_letter_10, .fooevents_tickets_avery_letter_10 {
            width: 11in;
            padding: 0;            
        }

        .fooevents_tickets_letter_10 .fooevents_ticket,
        .fooevents_tickets_avery_letter_10 .fooevents_ticket {
            width: 5.5in;
            height: 1.7in;
            position: relative;
        }

        .fooevents_tickets_a4_10 {
            width: 297mm;
            padding: 0;            
        }

        .fooevents_tickets_a4_10 .fooevents_ticket {
            width: 148.4mm;
            height: 42mm;
            position: relative;
        }

        .fooevents_ticket_page.fooevents_ticket_line_on .fooevents_ticket {
            border-bottom: 1px dotted #cccccc;
            border-right: 1px dotted #cccccc;
        }

        .fooevents_ticket_page.fooevents_letter_6.fooevents_ticket_line_on .fooevents_ticket,
        .fooevents_ticket_page.fooevents_letter_10.fooevents_ticket_line_on .fooevents_ticket,
        .fooevents_ticket_page.fooevents_a4_12.fooevents_ticket_line_on .fooevents_ticket:nth-child(3n+1),
        .fooevents_ticket_page.fooevents_a4_16.fooevents_ticket_line_on .fooevents_ticket:nth-child(2n+1),
        .fooevents_ticket_page.fooevents_a4_39.fooevents_ticket_line_on .fooevents_ticket:nth-child(3n+1),
        .fooevents_ticket_page.fooevents_a4_45.fooevents_ticket_line_on .fooevents_ticket:nth-child(5n+1),
        .fooevents_ticket_page.fooevents_letter_30.fooevents_ticket_line_on .fooevents_ticket {
            border-left: 1px dotted #cccccc;
        }

        .fooevents_ticket_page.fooevents_letter_6.fooevents_ticket_line_on .fooevents_ticket:nth-child(1),
        .fooevents_ticket_page.fooevents_letter_6.fooevents_ticket_line_on .fooevents_ticket:nth-child(2),
        .fooevents_ticket_page.fooevents_a4_12.fooevents_ticket_line_on .fooevents_ticket:nth-child(1),
        .fooevents_ticket_page.fooevents_a4_12.fooevents_ticket_line_on .fooevents_ticket:nth-child(2),
        .fooevents_ticket_page.fooevents_a4_12.fooevents_ticket_line_on .fooevents_ticket:nth-child(3),
        .fooevents_ticket_page.fooevents_a4_16.fooevents_ticket_line_on .fooevents_ticket:nth-child(1),
        .fooevents_ticket_page.fooevents_a4_16.fooevents_ticket_line_on .fooevents_ticket:nth-child(2),
        .fooevents_ticket_page.fooevents_a4_24.fooevents_ticket_line_on .fooevents_ticket:nth-child(1),
        .fooevents_ticket_page.fooevents_a4_24.fooevents_ticket_line_on .fooevents_ticket:nth-child(2),
        .fooevents_ticket_page.fooevents_a4_24.fooevents_ticket_line_on .fooevents_ticket:nth-child(3),
        .fooevents_ticket_page.fooevents_a4_39.fooevents_ticket_line_on .fooevents_ticket:nth-child(1),
        .fooevents_ticket_page.fooevents_a4_39.fooevents_ticket_line_on .fooevents_ticket:nth-child(2),
        .fooevents_ticket_page.fooevents_a4_39.fooevents_ticket_line_on .fooevents_ticket:nth-child(3),
        .fooevents_ticket_page.fooevents_a4_45.fooevents_ticket_line_on .fooevents_ticket:nth-child(1),
        .fooevents_ticket_page.fooevents_a4_45.fooevents_ticket_line_on .fooevents_ticket:nth-child(2),
        .fooevents_ticket_page.fooevents_a4_45.fooevents_ticket_line_on .fooevents_ticket:nth-child(3),
        .fooevents_ticket_page.fooevents_a4_45.fooevents_ticket_line_on .fooevents_ticket:nth-child(4),
        .fooevents_ticket_page.fooevents_a4_45.fooevents_ticket_line_on .fooevents_ticket:nth-child(5),
        .fooevents_ticket_page.letter_30.fooevents_ticket_line_on .fooevents_ticket {
            border-top: 1px dotted #cccccc;
        }

        .fooevents_ticket_page.fooevents_letter_10.fooevents_ticket_line_on .fooevents_ticket {
            border: none;
            outline: 1px dotted #cccccc;
        }

        .fooevents_ticket_inner {
            display:flex;
            flex-direction: row;
            flex-wrap: nowrap;
            height: 100%;
        }

        .fooevents_ticket_info {
            flex-grow: 1;
            flex-basis: 0;
            box-sizing: border-box;
            display:flex;
            flex-direction: column;
            flex-wrap: nowrap;
            text-align: center;
        }

        .fooevents_ticket_info div {
            flex-grow: 1;
            flex-basis: 0;
            padding: 3%;
            box-sizing: border-box;
            min-height: 0;
            justify-content: center;
            align-items: center;
            display: flex;
        }

        .fooevents_ticket_email {
            word-wrap: anywhere;
        }

        .fooevents_ticket_info div img {
            max-width: 100%;
            max-height: 100%;
            display: block;
            margin: auto;
        }

        .fooevents_tickets_letter_10 .fooevents_ticket_info:last-child {
            max-width: 1.5in;
            min-width: 1.5in;
        }

        .fooevents_tickets_letter_10.fooevents_ticket_line_on .fooevents_ticket_info:last-child {
            border-left: 1px dotted #aaaaaa;
        }
    </style>
    <script type="text/javascript">
        function printFunction() { 
            window.print();
        }
    </script>
</head>
<body onload="printFunction();">
    <?php
        $content_to_echo1 = "";
        $content_to_echo2 = "";
        $content_to_echo3 = "";
        $content_to_echo4 = "";
        $content_to_echo5 = "";
        $content_to_echo6 = "";
        $content_to_echo7 = "";
        $content_to_echo8 = "";
        $content_to_echo9 = "";
        $event = "";
        $event_var = "";
        $var_only = "";
        $barcode = "";
        $logo = "";
        $ticketnr = "";
        $name = "";
        $email = "";
        $phone = "";
        $company = "";
        $designation = "";
        $seat = "";
        $location = "";        
        $custom = "";        
        $spacer = "";        

        foreach($sorted_rows as $item) {
            $ticketnr = "<p><b>Ticket:</b> " . $item["TicketID"] . "</p>";

            if (!empty($item["TicketHash"])) {

                $item["TicketHash"] .= "-";

            }
        
            $barcode = "<div><img src='" . esc_url($this->Config->barcodeURL) . $item["TicketHash"] . str_replace("#","",$item["TicketID"]) . ".png' /></div>";
            $name = urldecode($item["Attendee First Name"]) . " " . urldecode($item["Attendee Last Name"]);
            $event = urldecode($item["Event Name"]);
            $event_var = urldecode($item["Event Name Variations"]);
            $var_only = urldecode($item["Variation"]);
            $email = $item["Attendee Email"];
            $phone = $item["Attendee Telephone"];
            $company = urldecode($item["Attendee Company"]);
            $designation = urldecode($item["Attendee Designation"]);
            $seat = urldecode($item["SeatInfo"]);
            $location = urldecode($item["Location"]);
            $spacer = "<div></div>";
            
            switch($ticketfield1) {
                case "barcode":
                    $content_to_echo1 = $barcode;
                    break;
                case "logo":
                    $content_to_echo1 = '<div><img class="fooevents_ticket_logo" src=' .  $logo1 . ' /></div>';
                    break;
                case "event":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . "'>" . $event . "</div>";
                    break;
                case "event_var":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . "'>" . $event_var . "</div>";
                    break;
                case "var_only":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . "'>" . $var_only . "</div>";
                    break;
                case "ticketnr":
                    $content_to_echo1 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font1 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                    break;
                case "name":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . "'>" . $name . "</div>";
                    break;
                case "email":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . " fooevents_ticket_email'>" . $email . "</div>";
                    break;
                case "phone":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . " fooevents_ticket_phone'>" . $phone . "</div>";
                    break;
                case "company":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . " fooevents_ticket_company'>" . $company . "</div>";
                    break;
                case "designation":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . " fooevents_ticket_designation'>" . $designation . "</div>";
                    break;
                case "seat":
                    $content_to_echo1 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font1 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                    break;
                case "location":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . "'>" . $location . "</div>";
                    break;
                case "custom":
                    $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . "'>" . $custom1 . "</div>";
                    break;
                case "spacer":
                    $content_to_echo1 = $spacer;
                    break;
                case stristr($ticketfield1,'fooevents_custom'):
                    $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield1)));
                    
                    if (!empty($item[$ticketfield1])) {
                        
                        $content_to_echo1 = "<div class='fooevents_ticket_" . $font1 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield1] . "</span></div>";

                    }
                    break;
            }

            if ($nrrow > 1 ) {

                switch($ticketfield2) {
                    case "barcode":
                        $content_to_echo2 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo2 = '<div><img class="fooevents_ticket_logo" src=' .  $logo2 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo2 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font2 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . " fooevents_ticket_email'>" . $email . "</div>";
                        break;
                    case "phone":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "seat":
                        $content_to_echo2 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font2 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "location":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . "'>" . $custom2 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo2 = $spacer;
                        break;
                    case stristr($ticketfield2,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield2)));
                    
                        if (!empty($item[$ticketfield2])) {

                            $content_to_echo2 = "<div class='fooevents_ticket_" . $font2 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield2] . "</span></div>";
                        }
                        break;
                }

            }

            if ($nrrow > 2 ) {

                switch($ticketfield3) {
                    case "barcode":
                        $content_to_echo3 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo3 = '<div><img class="fooevents_ticket_logo" src=' .  $logo3 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo3 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font3 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . " ticket_email'>" . $email . "</div>";
                        break;
                    case "seat":
                        $content_to_echo3 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font3 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "phone":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "location":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . "'>" . $custom3 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo3 = $spacer;
                        break;
                    case stristr($ticketfield3,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield3)));

                        if (!empty($item[$ticketfield3])) {

                            $content_to_echo3 = "<div class='fooevents_ticket_" . $font3 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield3] . "</span></div>";
                        
                        }
                        break;
                }

            }

            if ($nrcol > 1 ) {

                switch($ticketfield4) {
                    case "barcode":
                        $content_to_echo4 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo4 = '<div><img class="fooevents_ticket_logo" src=' .  $logo4 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo4 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font4 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . " fooevents_ticket_email'>" . $email . "</div>";
                        break;
                    case "phone":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "seat":
                        $content_to_echo4 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font4 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "location":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . "'>" . $custom4 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo4 = $spacer;
                        break;
                    case stristr($ticketfield4,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield4)));
                        if (!empty($item[$ticketfield4])) {

                            $content_to_echo4 = "<div class='fooevents_ticket_" . $font4 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield4] . "</span></div>";

                        }
                        break;
                }

            }

            if ($nrrow > 1 && $nrcol > 1 ) {

                switch($ticketfield5){
                    case "barcode":
                        $content_to_echo5 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo5 = '<div><img class="fooevents_ticket_logo" src=' .  $logo5 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo5 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font5 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . " fooevents_ticket_email'>" . $email . "</div>";
                        break;
                    case "phone":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "seat":
                        $content_to_echo5 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font5 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "location":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . "'>" . $custom5 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo5 = $spacer;
                        break;
                    case stristr($ticketfield5,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield5)));

                        if (!empty($item[$ticketfield5])) {

                            $content_to_echo5 = "<div class='fooevents_ticket_" . $font5 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield5] . "</span></div>";

                        }
                        break;
                }

            }

            if ($nrrow > 2 && $nrcol > 1 ) {

                switch($ticketfield6){
                    case "barcode":
                        $content_to_echo6 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo6 = '<div><img class="fooevents_ticket_logo" src=' .  $logo6 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo6 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font6 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . " fooevents_ticket_email'>" . $email . "</div>";
                        break;
                    case "phone":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "seat":
                        $content_to_echo6 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font6 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "location":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . "'>" . $custom6 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo6 = $spacer;
                        break;
                    case stristr($ticketfield6,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield6)));

                        if (!empty($item[$ticketfield6])) {

                            $content_to_echo6 = "<div class='fooevents_ticket_" . $font6 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield6] . "</span></div>";

                        }
                        break;
                }

            }

            if ($nrcol > 2 ) {

                switch($ticketfield7){
                    case "barcode":
                        $content_to_echo7 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo7 = '<div><img class="fooevents_ticket_logo" src=' .  $logo7 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo7 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font7 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . " fooevents_ticket_email'>" . $email . "</div>";
                        break;
                    case "phone":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "seat":
                        $content_to_echo7 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font7 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "location":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . "'>" . $custom7 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo7 = $spacer;
                        break;
                    case stristr($ticketfield7,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield7)));

                        if (!empty($item[$ticketfield7])) {

                            $content_to_echo7 = "<div class='fooevents_ticket_" . $font7 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield7] . "</span></div>";

                        }
                        break;
                }

            }

            if ($nrrow > 1 && $nrcol > 2 ) {

                switch($ticketfield8) {
                    case "barcode":
                        $content_to_echo8 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo8 = '<div><img class="fooevents_ticket_logo" src=' .  $logo8 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo8 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font8 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . " fooevents_ticket_email'>" . $email . "</div>";
                        break;
                    case "phone":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "seat":
                        $content_to_echo8 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font8 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "location":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . "'>" . $custom8 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo8 = $spacer;
                        break;
                    case stristr($ticketfield8,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield8)));

                        if (!empty($item[$ticketfield8])) {

                            $content_to_echo8 = "<div class='fooevents_ticket_" . $font8 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield8] . "</span></div>";

                        }
                        break;
                }

            }

            if ($nrrow > 2 && $nrcol > 2 ) {

                switch($ticketfield9) {
                    case "barcode":
                        $content_to_echo9 = $barcode;
                        break;
                    case "logo":
                        $content_to_echo9 = '<div><img class="fooevents_ticket_logo" src=' .  $logo9 . ' /></div>';
                        break;
                    case "event":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . "'>" . $event . "</div>";
                        break;
                    case "event_var":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . "'>" . $event_var . "</div>";
                        break;
                    case "var_only":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . "'>" . $var_only . "</div>";
                        break;
                    case "ticketnr":
                        $content_to_echo9 = $item["TicketID"] != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font9 . "'><b>Ticket</b><span>" . $item["TicketID"] . "</span></div>" : "";
                        break;
                    case "name":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . "'>" . $name . "</div>";
                        break;
                    case "email":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . " fooevents_ticket_email'>" . $email . "</div>";
                        break;
                    case "phone":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . " fooevents_ticket_phone'>" . $phone . "</div>";
                        break;
                    case "company":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . " fooevents_ticket_company'>" . $company . "</div>";
                        break;
                    case "designation":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . " fooevents_ticket_designation'>" . $designation . "</div>";
                        break;
                    case "seat":
                        $content_to_echo9 = $seat != "" ? "<div class='fooevents_ticket_seat fooevents_ticket_" . $font9 . "'><span><b>Seat</b>" . $seat . "</span></div>" : "";
                        break;
                    case "location":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . "'>" . $location . "</div>";
                        break;
                    case "custom":
                        $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . "'>" . $custom9 . "</div>";
                        break;
                    case "spacer":
                        $content_to_echo9 = $spacer;
                        break;
                    case stristr($ticketfield9,'fooevents_custom'):
                        $cf_name = ucwords(str_replace('_', ' ', str_replace('fooevents_custom_', ' ', $ticketfield9)));

                        if (!empty($item[$ticketfield9])) {

                            $content_to_echo9 = "<div class='fooevents_ticket_" . $font9 . "'><b>" . $cf_name . "</b><span>" . $item[$ticketfield9] . "</span></div>";

                        }
                        break;
                }

            }

            if ($y == 0) {

                echo '<div class="fooevents_ticket_page ' . $page_size . ' fooevents_ticket_line_' . $cutlines . '">'; 

            } elseif ($y%$nr_per_page == 0) {

                echo '</div><div class="fooevents_ticket_page ' . $page_size . ' fooevents_ticket_line_' . $cutlines . '">'; 

            }

        ?>
    
        <div class="fooevents_ticket">
            <div class="fooevents_ticket_inner">    
                <?php if (!empty($content_to_echo1) || (!empty($content_to_echo2)) || (!empty($content_to_echo2))) { ?>
                <div class="fooevents_ticket_info">
                    <?php        
                        echo $content_to_echo1;
                        echo $content_to_echo2;
                        echo $content_to_echo3;
                     ?>
                </div>
                <?php } ?>
                <?php if (!empty($content_to_echo4) || (!empty($content_to_echo5)) || (!empty($content_to_echo6))) { ?>
                <div class="fooevents_ticket_info">
                    <?php        
                        echo $content_to_echo4;
                        echo $content_to_echo5;
                        echo $content_to_echo6;
                     ?>
                </div>
                <?php } ?>
                <?php if (!empty($content_to_echo7) || (!empty($content_to_echo8)) || (!empty($content_to_echo9))) { ?>
                <div class="fooevents_ticket_info">
                    <?php        
                        echo $content_to_echo7;
                        echo $content_to_echo8;
                        echo $content_to_echo9;
                     ?>
                </div>
                <?php } ?>
            </div>
        </div>
   
    <?php
        $y++;
    }
    ?>
    <div class="fooevents_clear"></div>
</body>
</html>


