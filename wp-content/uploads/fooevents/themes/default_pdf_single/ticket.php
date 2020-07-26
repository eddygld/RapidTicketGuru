<?php
    if ($ticket['type'] == "PDF") :
        $ticket['type'] .= "Single";
    endif;

    if (empty($ticket['type'])) :
        $ticket['type'] = "HTML";
    endif;
    
    $upload_dir = wp_upload_dir();
    $dir = $upload_dir['basedir'];

    if ($ticket['type'] != "HTML") {
        $barcodeURL = $dir . "/fooevents/barcodes/";
        $logo = $ticket['WooCommerceEventsTicketLogoPath'];
    } else {
        $logo = $ticket['WooCommerceEventsTicketLogo'];
    }

    if (empty($ticket['WooCommerceEventsTicketButtonColor']) || $ticket['WooCommerceEventsTicketButtonColor'] == 1 || $ticket['name'] == __('Preview Event', 'woocommerce-events')) {
        $ticket['WooCommerceEventsTicketButtonColor'] = "#55AF71";
    }

    if (empty($ticket['WooCommerceEventsTicketBackgroundColor']) || $ticket['WooCommerceEventsTicketBackgroundColor'] == 1 || $ticket['name'] == __('Preview Event', 'woocommerce-events')) {
        $ticket['WooCommerceEventsTicketBackgroundColor'] = "#55AF71";
    }

    if (empty($ticket['WooCommerceEventsTicketTextColor']) || $ticket['WooCommerceEventsTicketTextColor'] == 1) {
        $ticket['WooCommerceEventsTicketTextColor'] = "#ffffff";
    }
    

?>

<?php if (!empty($ticket['ticketNumber']) && $ticket['ticketNumber'] > 1 && $ticket['type'] == "PDFSingle") : ?>
    <div style="page-break-before: always;"></div>  
<?php endif; ?>
<?php if (!empty($ticket['ticketNumber']) && $ticket['ticketNumber'] % 4 == 0 && $ticket['type'] == "PDFMultiple") : ?>
    <div style="page-break-before: always;"></div>
<?php endif; ?>


<?php if ($ticket['type'] == "PDFMultiple" && ($ticket['ticketNumber'] % 4 == 0 || $ticket['ticketNumber'] == 1)) : ?>
    <div style="font-family:'DejaVu Sans','Helvetica','sans-serif';font-size: 14px;line-height:18px;padding:20px 50px;font-size: 14px;line-height:18px;">
    <h1 style="font-weight: normal;padding:0;margin:0 0 10px;font-size: 20px;line-height: 24px;"><?php echo $ticket['name'];  ?></h1>
    <?php if(!empty($ticket['WooCommerceEventsTicketText']) || (!empty($ticket['WooCommerceEventsTicketDisplayZoom']) && $ticket['WooCommerceEventsTicketDisplayZoom'] != 'off' && !empty($ticket['WooCommerceEventsZoomText']))) : ?>
        <p style="font-size:12px;color:#777777">
            <?php if(!empty($ticket['WooCommerceEventsTicketText'])) : ?>
                <?php echo nl2br($ticket['WooCommerceEventsTicketText']); ?>
            <?php endif; ?>
            <?php if((!empty($ticket['WooCommerceEventsTicketDisplayZoom']) && $ticket['WooCommerceEventsTicketDisplayZoom'] != 'off' && !empty($ticket['WooCommerceEventsZoomText']))) : ?>
                <?php echo nl2br($ticket['WooCommerceEventsZoomText']); ?>
            <?php endif; ?>
        </p>
    <?php endif; ?> 
    <br />
    <?php if($ticket['WooCommerceEventsTicketDisplayDateTime'] != 'off') :?>
        <?php if(!empty($ticket['WooCommerceEventsDate'])) : ?>
            <strong><?php _e('Date:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsDate']; if(!empty($ticket['WooCommerceEventsEndDate'])) : echo " - " . $ticket['WooCommerceEventsEndDate']; endif; ?><br />
        <?php endif; ?> 
        <?php if(!empty($ticket['WooCommerceEventsHour'])) : ?>
            <strong><?php _e('Time:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsHour']; ?>:<?php echo $ticket['WooCommerceEventsMinutes']; ?><?php echo (!empty($ticket['WooCommerceEventsPeriod']))? $ticket['WooCommerceEventsPeriod'] : '' ?>
            <?php echo (!empty($ticket['WooCommerceEventsTimeZone']))? " " . $ticket['WooCommerceEventsTimeZone'] : '' ?>
            <?php if($ticket['WooCommerceEventsHourEnd'] != '00') : ?>
                - <?php echo $ticket['WooCommerceEventsHourEnd']; ?>:<?php echo $ticket['WooCommerceEventsMinutesEnd']; ?><?php echo (!empty($ticket['WooCommerceEventsEndPeriod']))? $ticket['WooCommerceEventsEndPeriod'] : '' ?>
                <?php echo (!empty($ticket['WooCommerceEventsTimeZone']))? " " . $ticket['WooCommerceEventsTimeZone'] : '' ?>
            <?php endif; ?>
        <?php endif; ?> 
        <br />
    <?php endif; ?>              
    <?php if(!empty($ticket['WooCommerceEventsLocation'])) :?>
        <strong><?php _e('Location:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsLocation'] ?><br />
    <?php endif; ?>
    <?php if(!empty($ticket['WooCommerceEventsDirections'])) :?>
        <strong><?php _e('Directions:', 'woocommerce-events'); ?></strong> <?php echo $ticket['WooCommerceEventsDirections']; ?>
    <?php endif; ?>

    </div>
<?php endif; ?>




<br /><br />
<div style="border-top:1px dashed #dddddd;width:100%">&nbsp;</div>
 <!--[if mso]>
<table border="0" width="600" align="center" style="max-width: 600px;margin:0 auto; border-collapse: collapse;"><tr><td width="300">
<![endif]-->
<div style="page-break-inside: avoid;float:left;text-align:center; width:50%;max-width:300px;font-family:'DejaVu Sans','Helvetica','sans-serif';font-size: 14px;line-height:18px; margin-top:10px;">
    <?php if(!empty($ticket['WooCommerceEventsTicketLogoPath'])) :?>
        <img src="<?php echo $logo; ?>" alt="" width="150px"/><br /><br /><br />
    <?php endif; ?>
    <?php if($ticket['WooCommerceEventsTicketDisplayBarcode'] != 'off') :?>
        <img src="<?php echo $barcodeURL; ?><?php if($ticket['name'] == __('Preview Event', 'woocommerce-events')) echo $ticket['WooCommerceEventsTicketID']; else echo $ticket['barcodeFileName']; ?>.jpg" alt="Barcode: <?php echo $ticket['WooCommerceEventsTicketID']; ?>" width="150px" />
   
    <br /><br /><?php echo $ticket['WooCommerceEventsTicketID']; ?>
    <?php endif; ?>  
</div>
<!--[if mso]>
</td><td width="300">
<![endif]-->
<div style="page-break-inside: avoid;width:50%; vertical-align:middle;float:left;border-left: solid 2px <?php echo $ticket['WooCommerceEventsTicketBackgroundColor']; ?>;padding-left:20px;max-width:278px;font-family:'DejaVu Sans','Helvetica','sans-serif';font-size: 14px;line-height:18px;">
    <?php if ($ticket['type'] == "PDFSingle" || $ticket['type'] == "HTML") : ?>
        <h1 style="font-weight: normal;padding:0;margin:0 0 10px;font-size: 20px;line-height: 24px;"><?php echo $ticket['name'];  ?></h1>
        <br />
        <?php if($ticket['WooCommerceEventsTicketDisplayDateTime'] != 'off') :?>
            <?php if(!empty($ticket['WooCommerceEventsDate'])) : ?>
                <strong><?php _e('Date:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsDate']; ?><br />
            <?php endif; ?> 
            <?php if(!empty($ticket['WooCommerceEventsHour'])) : ?>
                <strong><?php _e('Time:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsHour']; ?>:<?php echo $ticket['WooCommerceEventsMinutes']; ?><?php echo (!empty($ticket['WooCommerceEventsPeriod']))? $ticket['WooCommerceEventsPeriod'] : '' ?>
                <?php echo (!empty($ticket['WooCommerceEventsTimeZone']))? " " . $ticket['WooCommerceEventsTimeZone'] : '' ?>
                <?php if($ticket['WooCommerceEventsHourEnd'] != '00') : ?>
                    - <?php echo $ticket['WooCommerceEventsHourEnd']; ?>:<?php echo $ticket['WooCommerceEventsMinutesEnd']; ?><?php echo (!empty($ticket['WooCommerceEventsEndPeriod']))? $ticket['WooCommerceEventsEndPeriod'] : '' ?>
                    <?php echo (!empty($ticket['WooCommerceEventsTimeZone']))? " " . $ticket['WooCommerceEventsTimeZone'] : '' ?>
                <?php endif; ?>
            <?php endif; ?> 
            <br />
        <?php endif; ?>              
        <?php if(!empty($ticket['WooCommerceEventsLocation'])) :?>
            <strong><?php _e('Location:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsLocation'] ?><br />
        <?php endif; ?>
        <?php if(!empty($ticket['WooCommerceEventsDirections'])) :?>
            <strong><?php _e('Directions:', 'woocommerce-events'); ?></strong> <?php echo $ticket['WooCommerceEventsDirections']; ?>
        <?php endif; ?>
        <br /><br />
    <?php endif; ?>

    <strong><?php _e('Ticket Number:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsTicketID']; ?><br />
    
    <?php if($ticket['WooCommerceEventsTicketPurchaserDetails'] != 'off') :?>
        <strong><?php _e('Ticket Holder:','woocommerce-events') ?></strong> <?php echo $ticket['customerFirstName']; ?> <?php echo $ticket['customerLastName']; ?><br />
    
        <?php if(!empty($ticket['WooCommerceEventsAttendeeTelephone'])) :?>
            <strong><?php _e('Telephone Number:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsAttendeeTelephone']; ?><br />
        <?php endif; ?>
    
        <?php if(!empty($ticket['WooCommerceEventsAttendeeCompany'])) :?>
            <strong><?php _e('Company:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsAttendeeCompany']; ?><br />
        <?php endif; ?>
    
        <?php if(!empty($ticket['WooCommerceEventsAttendeeDesignation'])) :?>
            <strong><?php _e('Designation:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsAttendeeDesignation']; ?><br />
        <?php endif; ?>
    <?php endif; ?>
    
    
    <?php if(!empty($ticket['fooevents_seating_options'])) :?>
        <?php
            $ticket['fooevents_seating_options'] = str_replace("Row Name:", "<strong>Row Name:</strong>", $ticket['fooevents_seating_options']);
            $ticket['fooevents_seating_options'] = str_replace("Seat Number:", "<strong>Seat Number:</strong>", $ticket['fooevents_seating_options']);
            echo $ticket['fooevents_seating_options'];
        ?>
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
            <?php echo '<strong>'.$variationNameOutput.':</strong> '.$variationValueOutput.'<br />'; ?>
        <?php endforeach; ?>

    <?php endif; ?>
    <?php if($ticket['WooCommerceEventsTicketDisplayPrice'] != 'off') :?>
    <strong><?php _e('Price:','woocommerce-events') ?></strong> <?php echo $ticket['WooCommerceEventsPrice']; ?>
    <?php endif; ?>  
    <br />
    <?php if(!empty($ticket['fooevents_custom_attendee_fields_options'])) :?>
        <?php echo $ticket['fooevents_custom_attendee_fields_options']; ?>
    <?php endif; ?>
    <br />


    <?php if ($ticket['type'] != "PDFMultiple"): ?>
        <?php if(!empty($ticket['WooCommerceEventsTicketText']) || (!empty($ticket['WooCommerceEventsTicketDisplayZoom']) && $ticket['WooCommerceEventsTicketDisplayZoom'] != 'off' && !empty($ticket['WooCommerceEventsZoomText']))) : ?>
            <p style="font-size:12px;color:#777777">
                <?php if(!empty($ticket['WooCommerceEventsTicketText'])) : ?>
                    <?php echo nl2br($ticket['WooCommerceEventsTicketText']); ?>
                <?php endif; ?>
                <?php if((!empty($ticket['WooCommerceEventsTicketDisplayZoom']) && $ticket['WooCommerceEventsTicketDisplayZoom'] != 'off' && !empty($ticket['WooCommerceEventsZoomText']))) : ?>
                    <?php echo nl2br($ticket['WooCommerceEventsZoomText']); ?>
                <?php endif; ?>
            </p>
        <?php endif; ?> 
        <br />
    <?php endif; ?> 
    
    <?php if($ticket['WooCommerceEventsTicketAddCalendar'] != 'off') :?>
    <!--[if mso]>
    <table border="0" width="300" bgcolor="<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>" align="center" style="max-width: 300px;margin:0 auto; border-collapse: collapse;text-align:center"><tr><td align="center" style="text-align:center">
    <![endif]-->
        <a style="background-color:<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>;text-align:center;padding: 10px 15px;display:block;font-family:  'DejaVu Sans', 'Helvetica', 'sans-serif';line-height:16px;border: 0;outline: none;text-decoration: none;color: <?php echo $ticket['WooCommerceEventsTicketTextColor']; ?> !important;font-size: 16px;" href="<?php echo site_url(); ?>/wp-admin/admin-ajax.php?action=fooevents_ics&event=<?php echo $ticket['WooCommerceEventsProductID']; ?>">
        <!--[if mso]>
        <table bgcolor="<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>"" style="border-collapse: collapse;" border="0" align="center" width="300"><tr><td>&nbsp;</td></tr></table>
        <![endif]-->
        <?php _e('Add to calendar', 'woocommerce-events'); ?>
        <!--[if mso]>
        <table bgcolor="<?php echo $ticket['WooCommerceEventsTicketButtonColor']; ?>"" style="border-collapse: collapse;" border="0" align="center" width="300"><tr><td>&nbsp;</td></tr></table>
        <![endif]-->
        </a>
    <!--[if mso]>
    </td></tr></table>
    <![endif]-->
    <?php endif; ?> 
    <br />
</div>
<div style="clear:both"></div>
<!--[if mso]>
</td></tr></table>
<table border="0" align="center" width="600" style="border-collapse: collapse;"><tr><td cellpadding="50" >&nbsp;</td></tr></table>
<![endif]-->

<?php if (($ticket['type'] == "PDFMultiple" && $ticket['ticketNumber'] % 3 == 0) || $ticket['type'] == "PDFSingle") : ?>  
    <br />
    <div style="width:100%;border-top: 1px solid #eee;max-width:600px;font-family:'DejaVu Sans','Helvetica','sans-serif';">
        <?php if(!empty($ticket['FooEventsTicketFooterText'])) :?>
        <!--[if mso]>
            <table border="0" align="center" width="600" style="border-collapse: collapse;"><tr><td cellpadding="50" >&nbsp;</td></tr></table>
        <![endif]-->
            <p style="text-align:center;font-size:12px;color:#777;margin:0;padding:20px 30px;line-height:15px;"><?php echo $ticket['FooEventsTicketFooterText'];?></p>
        <?php endif; ?>
    </div>
<?php endif; ?>
