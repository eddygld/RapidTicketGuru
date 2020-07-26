(function($) {
   
    if (jQuery("#WooCommerceEventsReport").length) {
        
        if( (typeof localObj === "object") && (localObj !== null) )
        {

            jQuery('.WooCommerceEventsDate').datepicker({

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

            jQuery('.WooCommerceEventsDate').datepicker();

        }
        
        var dateFrom = jQuery('#WooCommerceEventsDateFrom').val();
        var dateTo = jQuery('#WooCommerceEventsDateTo').val();
        var eventID = jQuery('#eventID').val();
        
        display_tickets_sold_data(dateFrom, dateTo, eventID);
        display_revenue_data(dateFrom, dateTo, eventID);
        display_check_ins(dateFrom, dateTo, eventID);
        display_check_ins_today(dateFrom, dateTo, eventID);
        
    }
    
    function display_check_ins_today(dateFrom, dateTo, eventID) {
        
        var dataVariations = {
            'action': 'fetch_check_ins_today',
            'dateFrom': dateFrom,
            'dateTo': dateTo,
            'eventID': eventID
        };
        
        jQuery.post(ajaxurl, dataVariations, function(response) {
           
            var check_ins = JSON.parse(response);
            var toolTipData = [];
            
            $.each(check_ins, function (index, value) {
                
                toolTipData.push({
                    meta : index, 
                    value : value
                });
                
            });

            var labels = Object.keys(check_ins);
            var series = Object.values(check_ins);

            var chart = new Chartist.Line('#fooevents-report-check-ins-today', {
                labels: labels,
                series: [toolTipData]
              }, {
                low: 0,
                fullWidth: true,
                axisX: {
                    showLabel: false,
                },
                axisY: {
                    onlyInteger: true
                },
                plugins: [
                  Chartist.plugins.tooltip({

                      anchorToPoint: true

                  })
                ]
              });
            
        });
    }
    
    function display_check_ins(dateFrom, dateTo, eventID) {
        
        var dataVariations = {
            'action': 'fetch_check_ins',
            'dateFrom': dateFrom,
            'dateTo': dateTo,
            'eventID': eventID
        };
        
        jQuery.post(ajaxurl, dataVariations, function(response) {
            
            var check_ins = JSON.parse(response);
            var toolTipData = [];
            
            $.each(check_ins, function (index, value) {
                
                toolTipData.push({
                    meta : index, 
                    value : value
                });
                
            });

            var labels = Object.keys(check_ins);
            var series = Object.values(check_ins);
            
            var num_tickets = 0;
            
            $.each(series , function(index, val) { 
                num_tickets = parseInt(num_tickets) + parseInt(val);
            });
            
            jQuery('#WooCommerceCheckIns').html(num_tickets);

            var chart = new Chartist.Line('#fooevents-report-check-ins', {
                labels: labels,
                series: [toolTipData]
              }, {
                low: 0,
                fullWidth: true,
                axisX: {
                    showLabel: false,
                },
                axisY: {
                    onlyInteger: true
                },
                plugins: [
                  Chartist.plugins.tooltip({

                      anchorToPoint: true

                  })
                ]
              });
            
        });
        
    }
    
    function display_tickets_sold_data(dateFrom, dateTo, eventID) {

        var dataVariations = {
            'action': 'fetch_tickets_sold',
            'dateFrom': dateFrom,
            'dateTo': dateTo,
            'eventID': eventID
        };
        
        jQuery.post(ajaxurl, dataVariations, function(response) {
            
            var tickets_sold_per_day = JSON.parse(response);
            var toolTipData = [];
            
            $.each(tickets_sold_per_day, function (index, value) {
                
                toolTipData.push({
                    meta : index, 
                    value : value
                });
                
            });
            
            var labels = Object.keys(tickets_sold_per_day);
            var series = Object.values(tickets_sold_per_day);
            
            var num_tickets = 0;
            
            $.each(series , function(index, val) { 
                num_tickets = parseInt(num_tickets) + parseInt(val);
            });
            
            jQuery('#WooCommerceTicketsSold').html(num_tickets);

            var chart = new Chartist.Line('#fooevents-report-tickets-sold', {
                labels: labels,
                series: [toolTipData]
              }, {
                low: 0,
                fullWidth: true,
                axisX: {
                    showLabel: false,
                },
                axisY: {
                    onlyInteger: true
                },
                plugins: [
                  Chartist.plugins.tooltip({

                      anchorToPoint: true

                  })
                ]
          });
              
        });
        
    }
    
    function display_revenue_data(dateFrom, dateTo, eventID) {
        
        var dataVariations = {
            'action': 'fetch_tickets_revenue',
            'dateFrom': dateFrom,
            'dateTo': dateTo,
            'eventID': eventID
        };
        
        jQuery.post(ajaxurl, dataVariations, function(response) {
            
            var tickets_revenue_per_day = JSON.parse(response);
            var toolTipData = [];
            
            $.each(tickets_revenue_per_day, function (index, value) {
                
                toolTipData.push({
                    meta : index, 
                    value : value
                });
                
            });

            var labels = Object.keys(tickets_revenue_per_day);
            var series = Object.values(tickets_revenue_per_day);
            
            var total_revenue = 0;
            
            $.each(series , function(index, val) { 
                total_revenue = parseInt(total_revenue) + parseInt(val);
            });
            
            var dataVariationsRev = {
                'action': 'fetch_revenue_formatted',
                'total_revenue': total_revenue,
            }
            
            jQuery.post(ajaxurl, dataVariationsRev, function(responseRev) {
                
                jQuery('#WooCommerceTicketsRevenue').html(responseRev);
                
            });
 
            var chart = new Chartist.Line('#fooevents-report-tickets-revenue', {
                labels: labels,
                series: [toolTipData]
              }, {
                low: 0,
                fullWidth: true,
                axisX: {
                    showLabel: false,
                },
                axisY: {
                    onlyInteger: true
                },
                plugins: [
                  Chartist.plugins.tooltip({
                      anchorToPoint: true,
                      currency: localObj.currencySymbol
                  })
                ]
              });
            
        });
 
    }
    
})( jQuery );