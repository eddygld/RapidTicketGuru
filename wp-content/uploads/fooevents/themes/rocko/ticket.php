<?php
    if (empty($ticket['type'])) {
        $ticket['type'] = "HTML";
    }

    if (empty( $ticket['ticketNumber'])) {
        $ticket['ticketNumber'] = 1;
    }

    if ($ticket['type'] == "PDF") {
        $ticket['type'] .= "Single";
    }

    if ($ticket['WooCommerceEventsTicketButtonColor'] == "#55AF71" || empty($ticket['WooCommerceEventsTicketButtonColor']) || $ticket['name'] == __('Preview Event', 'woocommerce-events')) {
        $ticket['WooCommerceEventsTicketButtonColor'] = "#d51034";
    }

    if ($ticket['WooCommerceEventsTicketBackgroundColor'] == "#55AF71" || empty($ticket['WooCommerceEventsTicketBackgroundColor']) || $ticket['name'] == __('Preview Event', 'woocommerce-events')) {
        $ticket['WooCommerceEventsTicketBackgroundColor'] = "#19212c";
    }

    if (empty($ticket['WooCommerceEventsTicketTextColor']) || $ticket['name'] == __('Preview Event', 'woocommerce-events')) {
        $ticket['WooCommerceEventsTicketTextColor'] = "#ffffff";
    }

    $margin = 40;
    if (get_option('globalWooCommerceEventsEnableQRCode') == "yes" && $ticket['name'] != __('Preview Event', 'woocommerce-events')) {
        $margin = 0;
    }

    $themeFolder = "rocko";
    $upload_dir = wp_upload_dir();

    if ($ticket['type'] != "HTML") {
        $dir = $upload_dir['basedir'];
        $barcodeURL = $dir . "/fooevents/barcodes/";
        if (!empty($ticket['WooCommerceEventsTicketLogoPath']))
            $logo = $ticket['WooCommerceEventsTicketLogoPath'];
        if (!empty($ticket['WooCommerceEventsTicketHeaderImagePath']))
            $headerImg = $ticket['WooCommerceEventsTicketHeaderImagePath'];
        
    } else {
        $logo = $ticket['WooCommerceEventsTicketLogo'];
        $headerImg = $ticket['WooCommerceEventsTicketHeaderImage'];
        
        $dir = $upload_dir['baseurl'];
    }
    
?>

<?php if (!empty($ticket['ticketNumber']) && $ticket['ticketNumber'] > 1 && $ticket['type'] == "PDFSingle") : ?>
    <div style="page-break-before: always;"></div>  
<?php endif; ?>
<?php if (!empty($ticket['ticketNumber']) && $ticket['ticketNumber'] % 4 == 0 && $ticket['type'] == "PDFMultiple") : ?>
    <div style="page-break-before: always;"></div>
<?php endif; ?>





<?php if (($ticket['type'] == "PDFMultiple" && $ticket['ticketNumber'] % 4 == 0) || $ticket['ticketNumber'] == 1 || $ticket['type'] == "PDFSingle") : ?>   

<?php if(!empty($ticket['WooCommerceEventsTicketLogo']) || $ticket['name'] == __('Preview Event', 'woocommerce-events')) :?>
<div class="event_logo" style="max-width: 350px; padding: 30px 0; margin: 0 auto; width: 100%; text-align:center;">			
	<a style="border: 0; outline: none; text-decoration: none; color: #000000; display: inline-block;" href="<?php site_url(); ?>" align="middle">
        <img width="350" alt="Event Logo" title="Event Logo" src="<?php if ($ticket['name'] == __('Preview Event', 'woocommerce-events')) echo $dir . '/fooevents/themes/' . $themeFolder . '/images/logo.jpg'; else echo $logo; ?>" align="middle" style="display:inline; text-align:center;">
    </a>
</div>
<?php endif; ?>
<!--[if mso]>
    <table border="0" align="center" width="598" style="border-collapse: collapse;"><tr><td cellpadding="50" >&nbsp;</td></tr></table>
    <table border="0" width="598" bgcolor="<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>" align="center" style="max-width: 598px;margin:0 auto; border-collapse: collapse;"><tr><td>
<![endif]-->
<!--[if !mso]><!---->
<div class="two-column" style="max-width: 598px; width: 100%; padding: 0; background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>; margin: 0 auto;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';border:1px solid #282d33">
<![endif]-->
    <!--[if mso]>
    <table style="border-collapse: collapse;" border="0" bgcolor="<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>" width="598"><tr><td cellpadding="50">&nbsp;</td></tr></table>
    <![endif]-->

    <div class="column" style="width:100%; max-width:299px; float:left;padding: 0">
        
        <h1 style="font-size: 24px; color: #ffffff; font-weight: 100; line-height: 26px; text-align:center;padding:5px 10px 0 10px;"><?php _e('These are your tickets for', 'woocommerce-events'); ?> <b style="font-weight: bold;"><?php echo $ticket['name'] ?></b></h1>
		<!--[if !mso]><!---->
        <br />
        <![endif]-->
        <p style="font-size: 14px; color: #999999; font-weight: normal; line-height: 18px; margin: 0; text-align:center;padding: 0 10px 10px 10px;word-wrap:break-word">   
        <?php if(!empty($ticket['WooCommerceEventsTicketText']) || (!empty($ticket['WooCommerceEventsTicketDisplayZoom']) && $ticket['WooCommerceEventsTicketDisplayZoom'] != 'off' && !empty($ticket['WooCommerceEventsZoomText']))) : ?>
            <?php if(!empty($ticket['WooCommerceEventsTicketText'])) : ?>
                <?php echo nl2br($ticket['WooCommerceEventsTicketText']); ?>
            <?php endif; ?>
            <?php if((!empty($ticket['WooCommerceEventsTicketDisplayZoom']) && $ticket['WooCommerceEventsTicketDisplayZoom'] != 'off' && !empty($ticket['WooCommerceEventsZoomText']))) : ?>
                <?php echo nl2br($ticket['WooCommerceEventsZoomText']); ?>
            <?php endif; ?>
        <?php endif; ?>
        <!--[if mso]>
        <table style="border-collapse: collapse;" border="0" align="center" bgcolor="<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>" width="598"><tr><td>&nbsp;</td></tr></table>
        <![endif]-->
        </p>
    </div>
 
    <div class="column" style="width:100%; max-width:299px; float:left;padding:0;background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>">
         <?php if(!empty($ticket['WooCommerceEventsTicketHeaderImage']) || $ticket['name'] == __('Preview Event', 'woocommerce-events')) :?>	
            <img class="header-img" style="display:block;text-align:center;max-width:299px;width:100%" alt="Event Logo" title="Event Logo" src="<?php if ($ticket['name'] == __('Preview Event', 'woocommerce-events') || empty($headerImg)) echo $dir . '/fooevents/themes/' . $themeFolder . '/images/header_img.jpg'; else echo $headerImg; ?>" align="middle">
        <?php endif; ?>
    </div>
    <div style="clear:both"></div>
<!--[if !mso]><!---->
</div>
<![endif]-->
<!--[if mso]>
</td></tr></table>
<![endif]-->

<!--[if mso]>
<table border="0" width="598" bgcolor="#141A23" align="center" style="max-width: 598px;margin:0 auto; border-collapse: collapse;"><tr><td>
<table style="border-collapse: collapse;" border="0" align="center" bgcolor="#141A23" width="598"><tr><td>&nbsp;</td></tr></table>
<table style="border-collapse: collapse;" border="0" align="center" bgcolor="#141A23" width="500"><tr><td width="80">
<![endif]-->
<!--[if !mso]><!---->
<div class="four-column" style="max-width: 598px; width: 100%; padding: 30px 0; background-color:#141A23; margin: 0 auto;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';border-bottom:1px solid #282d33; border-left: 1px solid #282d33; border-right: 1px solid #282d33">
<![endif]-->
    <div class="column" style="width:100%;max-width:82px; float:left;padding:0 0 20px 0">
        <img align="middle" style="border: 0; outline: none; display:block; margin: auto; text-align:left; padding: 0 10px 10px 20px" src=<?php echo $dir . "/fooevents/themes/" . $themeFolder . "/images/location.jpg"; ?> alt="Location" title="Location">
    </div>
     <!--[if mso]>
    </td><td width="170">
    <![endif]-->
    <div class="column" style="width:100%;max-width:217px; float:left;padding: 0 0 20px 0;">
        <h2 style="color: #ffffff; font-size: 18px; line-height: 20x; margin: 0; padding: 0 10px 0 20px;font-weight: 100;"><?php echo $ticket['WooCommerceEventsLocation'] ?></h2>
		<!--[if !mso]><!---->
        <br />
        <![endif]-->
        <div style="color: #999999 !important;border: 0;outline: none;text-decoration: none;font-size: 14px;line-height:17px;padding: 0 10px 0 20px;"><?php echo $ticket['WooCommerceEventsDirections']; ?></div>
    </div>
     <!--[if mso]>
    </td><td width="80">
    <![endif]-->
    <div class="column" style="width:100%;max-width:82px; float:left;padding:0">
        <img style="border: 0; outline: none; display:block; margin: auto; text-align:left;padding: 0 10px 10px 20px;" src=<?php echo $dir . "/fooevents/themes/" . $themeFolder . "/images/time.jpg"; ?> alt="Time" title="Time">
    </div>
    <!--[if mso]>
    </td><td width="170">
    <![endif]-->
    <?php if($ticket['WooCommerceEventsTicketDisplayDateTime'] != 'off') :?>
    <div class="column" style="width:100%;max-width:217px; float:left;padding: 0;">
        <?php if(!empty($ticket['WooCommerceEventsDate'])) : ?>
            <h2 style="color: #ffffff; font-size: 18px; line-height: 20px; margin: 0; padding: 0 10px 0 20px; font-weight: 100;"><?php echo $ticket['WooCommerceEventsDate'];
            if(!empty($ticket['WooCommerceEventsEndDate'])) : echo " - " . $ticket['WooCommerceEventsEndDate']; endif; ?></h2>
        <?php endif; ?>
        
        <!--[if !mso]><!---->
        <br />
        <![endif]-->
        <?php if(!empty($ticket['WooCommerceEventsHour'])) : ?>
        <div style="color: #999999 !important;border: 0;outline: none;text-decoration: none;font-size: 14px;line-height:17px;padding: 0 10px 0 20px;">
            <?php echo $ticket['WooCommerceEventsHour']; ?>:<?php echo $ticket['WooCommerceEventsMinutes']; ?><?php echo (!empty($ticket['WooCommerceEventsPeriod']))? $ticket['WooCommerceEventsPeriod'] : '' ?>
                <?php if($ticket['WooCommerceEventsHourEnd'] != '00') : ?>
                    - <?php echo $ticket['WooCommerceEventsHourEnd']; ?>:<?php echo $ticket['WooCommerceEventsMinutesEnd']; ?><?php echo (!empty($ticket['WooCommerceEventsEndPeriod']))? $ticket['WooCommerceEventsEndPeriod'] : '' ?>
            <?php endif; ?>
            <?php echo (!empty($ticket['WooCommerceEventsTimeZone']))? " " . $ticket['WooCommerceEventsTimeZone'] : '' ?>
        </div>
         <?php endif; ?> 
    </div>
    <?php endif; ?>
    <div style="clear:both"></div>
<!--[if !mso]><!---->
</div>
<![endif]-->
<!--[if mso]>
</td></tr></table>
<table style="border-collapse: collapse;" border="0" align="center" bgcolor="#141A23" width="598"><tr><td>&nbsp;</td></tr></table>
</td></tr></table>
<![endif]-->

<?php if($ticket['WooCommerceEventsTicketAddCalendar'] != 'off') :?>
<!--[if mso]>
<table border="0" width="598" bgcolor="<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>" align="center" style="max-width: 598;margin:0 auto; border-collapse: collapse;"><tr><td>
<table style="border-collapse: collapse;" border="0" align="center" bgcolor="<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>" width="598"><tr><td cellpadding="50" >&nbsp;</td></tr></table>
<![endif]-->
<div class="add_calendar" style="max-width: 600px;margin: 0 auto;width: 100%;background-color:<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>;text-align:center;padding: 15px 0;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';">
     <a style="border: 0;outline: none;text-decoration: none;color: <?php echo $ticket['WooCommerceEventsTicketTextColor']; ?>;font-size: 16px;text-transform:uppercase;line-height:16px" href="<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=fooevents_ics&event=<?php echo $ticket['WooCommerceEventsProductID']; ?>"><?php _e('Add to calendar', 'woocommerce-events'); ?>&nbsp;+</a>
</div>
<!--[if mso]>
<table style="border-collapse: collapse;" border="0" align="center" bgcolor="<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>" width="598"><tr><td cellpadding="50" >&nbsp;</td></tr></table>
</td></tr></table>
<![endif]-->
<?php endif; ?> 
<?php endif; ?>
<!--[if mso]>
</td></tr></table>
<![endif]-->


<?php if($ticket['WooCommerceEventsTicketPurchaserDetails'] != 'off' || $ticket['WooCommerceEventsTicketDisplayBarcode'] != 'off') :?>
<!--[if mso]>
<table style="border-collapse: collapse;" border="0" align="center" bgcolor="#ffffff" width="598"><tr><td width="298">
<![endif]-->
<!--[if !mso]><!---->
<div class="two-column" style="page-break-inside: avoid;max-width: 598px; margin: 0 auto;width: 100%;border-bottom:1px solid #282d33; border-left: 1px solid #282d33; border-right: 1px solid #282d33;background-color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';">
<![endif]-->
    <div class="column" style="float: left;padding: 0 48px;max-width:202px;width:100%;background-color:#ffffff;text-align:center;vertical-align:middle;min-height:150px;">
    <?php if($ticket['WooCommerceEventsTicketDisplayBarcode'] != 'off') :?>
        <br />
        <img style="border: 0;outline: none;text-align:center;padding:<?php echo $margin; ?>px 0 0 0;vertical-align:middle" valign="middle" src="<?php echo $barcodeURL; ?><?php if($ticket['name'] == __('Preview Event', 'woocommerce-events')) echo $ticket['WooCommerceEventsTicketID']; else echo $ticket['barcodeFileName']; ?>.jpg" alt="Barcode: <?php echo $ticket['WooCommerceEventsTicketID']; ?>" title="Barcode">
        <div style="color:#999999 !important;line-height: 16px;font-size: 14px;" class="ticket_detail_text"><font style="color:#999999 !important; text-decoration:none;" color="#999999"><?php echo $ticket['WooCommerceEventsTicketID']; ?></font></div>
         <!--[if !mso]><!---->
         <br />
        <![endif]-->
    <?php endif; ?>
    </div>
    <!--[if mso]>
    </td><td width="69" bgcolor="#141A23">
    <![endif]-->
    <!--[if !mso]><!---->
    <div class="column" style="max-width:300px;width:100%;float:left;background-color:#141A23;min-height:150px;">
    <![endif]-->
        <?php if($ticket['WooCommerceEventsTicketPurchaserDetails'] != 'off') :?>
        <div class="ticket_icon" style="width:18%;float:left;background-color:#141A23;padding: 30px 3% 30px 3%;">
            <img style="border: 0;outline: none;background-color: #141A23;" src=<?php echo $dir . "/fooevents/themes/" . $themeFolder . "/images/ticket.jpg"; ?> alt="Ticket" title="Ticket">
        </div>
        
        <!--[if mso]>
        </td><td width="230" bgcolor="#141A23">
        <table style="border-collapse: collapse;" border="0" align="center" bgcolor="#141A23" width="230"><tr><td>&nbsp;</td></tr></table>
        <![endif]-->
        <div class="ticket_details" style="width:70%;float:right;padding: 30px 3% 30px 3%;background-color:#141A23;">
            <h3 style="color: #ffffff;font-weight: normal;line-height: 18px;font-size: 16px;margin: 0 0 10px 0;"><?php _e('Ticket details', 'woocommerce-events'); ?></h3>
                <span style="color:#999999 !important;line-height: 16px;font-size: 14px;" class="ticket_detail_text"><font style="color:#999999 !important; text-decoration:none;" color="#999999"><?php echo $ticket['customerFirstName']; ?> <?php echo $ticket['customerLastName']; ?></font></span><span class="ticket_detail_bullet" style="color: <?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>;font-size: 40px;line-height: 0;;=padding:0 0 0 5px">.</span>
                <?php if(!empty($ticket['WooCommerceEventsAttendeeTelephone'])) :?>
                    <span style="color:#999999 !important;line-height: 16px;font-size: 14px;" class="ticket_detail_text"><font style="color:#999999 !important;text-decoration:none" color="#999999"><?php echo $ticket['WooCommerceEventsAttendeeTelephone']; ?></font></span><span class="ticket_detail_bullet" style="color: <?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>;font-size: 40px;line-height: 0;padding:0 0 0 5px">.</span>
                <?php endif; ?>
                <?php if(!empty($ticket['WooCommerceEventsAttendeeCompany'])) :?>
                    <span style="color:#999999 !important;line-height: 16px;font-size: 14px;" class="ticket_detail_text"><font style="color:#999999 !important; text-decoration:none;" color="#999999"><?php echo $ticket['WooCommerceEventsAttendeeCompany']; ?></font></span><span class="ticket_detail_bullet" style="color: <?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>;font-size: 40px;line-height: 0;padding:0 0 0 5px">.</span>
                <?php endif; ?>
                <?php if(!empty($ticket['WooCommerceEventsAttendeeDesignation'])) :?>
                    <span style="color:#999999 !important;line-height: 16px;font-size: 14px;" class="ticket_detail_text"><font style="color:#999999 !important; text-decoration:none;" color="#999999"><?php echo $ticket['WooCommerceEventsAttendeeDesignation']; ?></font></span><span class="ticket_detail_bullet" style="color: <?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>;font-size: 40px;line-height: 0;padding:0 0 0 5px">.</span>
                <?php endif; ?>

                <?php if(!empty($ticket['WooCommerceEventsVariations'])) :?>
                    
                        <?php foreach($ticket['WooCommerceEventsVariations'] as $variationName => $variationValue) :?>
                            <?php 
                            $variationNameOutput = str_replace('attribute_', '', $variationName);
                            $variationNameOutput = str_replace('pa_', '', $variationNameOutput);
                            $variationNameOutput = str_replace('_', ' ', $variationNameOutput);
                            $variationNameOutput = str_replace('-', ' ', $variationNameOutput);
                            $variationNameOutput = str_replace('Pa_', '', $variationNameOutput);
                            $variationNameOutput = ucwords($variationNameOutput);

                            $variationValueOutput = str_replace('_', ' ', $variationValue);
                            $variationValueOutput = str_replace('-', ' ', $variationValueOutput);
                            $variationValueOutput = ucwords($variationValueOutput);
                            ?>
                            <?php echo '<span class="ticket_detail_text" style="color:#999999 !important;line-height: 16px;font-size: 14px;"><font style="color:#999999 !important; text-decoration:none;" color="#999999"><strong>' . urldecode($variationNameOutput) . ':</strong> ' . urldecode($variationValueOutput) . '</span><span class="ticket_detail_bullet" style="color: ' . $ticket['WooCommerceEventsTicketButtonColor'] . ';font-size: 40px;line-height: 0;padding:0 0 0 5px">.</span>'; ?>
                        <?php endforeach; ?>
                    
                    <?php endif; ?>
                    
                    <?php if(!empty($ticket['fooevents_custom_attendee_fields_options']) && (isset($ticket['WooCommerceEventsIncludeCustomAttendeeDetails']) && $ticket['WooCommerceEventsIncludeCustomAttendeeDetails'] != 'off')) :?>
                        <?php echo '<span class="ticket_detail_text" style="color:#999999 !important;line-height: 16px;font-size: 14px;"><font style="color:#999999 !important; text-decoration:none;" color="#999999">' . $ticket['fooevents_custom_attendee_fields_options'] . '</font></span><span class="ticket_detail_bullet" style="color: ' . $ticket['WooCommerceEventsTicketButtonColor'] . ';font-size: 40px;line-height: 0;padding:0 0 0 5px">.</span>'; ?>
                    <?php endif; ?>

                    <?php if(!empty($ticket['fooevents_seating_options']) ) :?>
                        <?php echo '<span class="ticket_detail_text" style="color:#999999 !important;line-height: 16px;font-size: 14px;"><font style="color:#999999 !important; text-decoration:none;" color="#999999">' . $ticket['fooevents_seating_options'] . '</font></span><span class="ticket_detail_bullet" style="color: ' . $ticket['WooCommerceEventsTicketButtonColor'] . ';font-size: 40px;line-height: 0;padding:0 0 0 5px">.</span>'; ?>
                    <?php endif; ?>
                    
                    <?php if($ticket['WooCommerceEventsTicketDisplayPrice'] != 'off') :?>
                        <span class="ticket_detail_text" style="color:#999999 !important;line-height: 16px;font-size: 14px;"><font style="color:#999999 !important; text-decoration:none;" color="#999999"><?php if(!empty($ticket['WooCommerceEventsPrice'])) echo $ticket['WooCommerceEventsPrice']; else if(!empty($ticket['price'])) echo $ticket['price']; ?></font></span>
                        
                    <?php endif; ?>  
        </div>
        <?php endif; ?>
        <div style="clear:both"></div>
    <!--[if !mso]><!---->
    </div>
    <![endif]-->
    <div style="clear:both"></div>
<!--[if !mso]><!---->
</div>
<![endif]-->
<!--[if mso]>
<table style="border-collapse: collapse;" border="0" align="center" bgcolor="#141A23" width="230"><tr><td>&nbsp;</td></tr></table>
</td></tr></table>
<![endif]-->
<?php endif; ?>


<?php if (($ticket['type'] == "PDFMultiple" && $ticket['ticketNumber'] % 3 == 0) || $ticket['type'] == "PDFSingle") : ?>  
<?php if(!empty($ticket['FooEventsTicketFooterText'])) :?>
    <!--[if mso]>
    <table style="border-collapse: collapse;" border="0" align="center" width="600" cellpadding="10" bgcolor="#<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>"><tr><td>
        <p style="padding:0 15px;text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';"><?php echo $ticket['FooEventsTicketFooterText'];?></p>
    </td></tr></table>
    <![endif]-->
    <!--[if !mso]><!---->
    <div class="footer" style="border-bottom:1px solid #282d33; border-left: 1px solid #282d33; border-right: 1px solid #282d33;max-width: 598px; margin: 0 auto; width: 100%; background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>; text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';">
        <p style="padding:0 15px"><?php echo $ticket['FooEventsTicketFooterText'];?></p>
    </div>
    <br /><br />
    <![endif]-->
    <?php endif; ?>
    <?php if($ticket['name'] == __('Preview Event', 'woocommerce-events')): ?>
    <!--[if mso]>
    <table style="border-collapse: collapse;" border="0" align="center" width="600" cellpadding="10" bgcolor="#<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>"><tr><td>
        <p style="padding:0 15px;text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';"><?php _e('Contact us for questions and concerns.', 'woocommerce-events'); ?></p>
    </td></tr></table>
    <![endif]-->
    <!--[if !mso]><!---->
    <div class="footer" style="border-bottom:1px solid #282d33; border-left: 1px solid #282d33; border-right: 1px solid #282d33;max-width: 598px; margin: 0 auto; width: 100%; background-color:<?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>; text-align:center; padding: 10px 0; font-size:12px; color:#ffffff;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';">
        <p style="padding:0 15px"><?php _e('Contact us for questions and concerns.', 'woocommerce-events'); ?></p>
    </div>
    <br /><br /><br /><br />
    <![endif]-->
    <?php endif; ?>
    
<?php endif; ?>
