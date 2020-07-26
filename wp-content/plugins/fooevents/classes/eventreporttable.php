<?php if(!defined('ABSPATH')) exit;

// WP_List_Table is not loaded automatically so we need to load it in our application
if(!class_exists('WP_List_Table')) {
    
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
    
}

/**
 * Create a new table class that will extend the WP_List_Table
 */
class Events_List_Table extends WP_List_Table
{
    /**
     * Prepare the items for the table to process
     *
     * @return Void
     */
    public function prepare_items()
    {
        
        global $wpdb;

        $paged = $this->get_pagenum();;
        $perPage = 20;
        
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $data = $this->table_data($perPage, $paged);

        $totalItems = $this->table_data('', '', true);

        $this->set_pagination_args( array(
            'total_items' => $totalItems,
            'per_page'    => $perPage
        ) );

        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
        
    }
    
    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        
        $columns = array(
            'id'            => __('ID', 'woocommerce-events'),
            'event_name'    => __('Event Name', 'woocommerce-events'), 
            'tickets_sold'  => __('Tickets Sold', 'woocommerce-events'), 
            'tickets_available'  => __('Available Tickets', 'woocommerce-events'),
            'total_sales'   => __('Total Revenue', 'woocommerce-events'),
            'settings'       => '',
        );
        
        return $columns;
        
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
        
        $sortable_columns = array(
            //'id' => array('id', true),
            //'event_name' => array('event_name', true),
        );
        
        return $sortable_columns;
        
    }
    
    /**
     * Get the table data
     * @param int $perPage
     * @param int $paged
     * 2param bool $returnPostCount
     * @return Array
     */
    private function table_data($perPage, $paged, $returnPostCount = false)
    {
        
        wp_reset_postdata();
        
        if($returnPostCount) {
            
            $perPage = -1;
            
        }
        
        $events_query = new WP_Query(array('post_type' => array('product'), 'posts_per_page' => $perPage, 'paged' => $paged, 'meta_query' => array(array( 'key' => 'WooCommerceEventsEvent', 'value' => 'Event', 'compare' => '='))));
        $events = $events_query->get_posts();
        
        if($returnPostCount) {
            
            return $events_query->post_count;
            
        } else {
        
            return $events;
        
        }
        
    }
    /**
     * Define what data to show on each column of the table
     *
     * @param  Array $item        Data
     * @param  String $column_name - Current column name
     *
     * @return Mixed
     */
    public function column_default( $item, $column_name )
    {
        
        switch($column_name) {
            
            case 'id':
                return $item->ID;
            case 'event_name':
                return '<strong><a href="admin.php?page=fooevents-event-report&event='.$item->ID.'">'.$item->post_title.'</a></strong>';
            case 'tickets_sold':
                return '<strong><a href="edit.php?post_type=event_magic_tickets&event_id='.$item->ID.'">'.$this->_get_tickets_sold($item->ID).'</a></strong>';
            case 'tickets_available':
                return $this->tickets_available_column($item->ID);
            case 'total_sales':
                return wc_price($this->_get_total_revenue($item->ID));
            case 'settings':
                return '<a href="post.php?post='.$item->ID.'&action=edit">'.__('Edit', 'woocommerce-events').'</a>';

            default:
                return '';
                
        }
    }
    
    /**
     * Displays tickets available
     * 
     * @param int $ID
     * @return string
     */
    public function tickets_available_column($ID) {
        
        $product = wc_get_product($ID);

        $variations = '';

        if(get_post_meta($ID,'_manage_stock', true) == 'yes') {
            
            return round(get_post_meta($ID, '_stock', true ));
            
        } elseif($product->is_type('variable')) {
            
            $variations = $product->get_available_variations();
            
            $stockOutput = 0;
            foreach($variations as $variation) {

                $variation_id = $variation['variation_id'];
                $variation_obj = new WC_Product_variation($variation_id);
                $stock = $variation_obj->get_stock_quantity();
  
                if(!empty($stock)) {
                    
                    $stockOutput = $stockOutput + $stock;
                    
                }
                
            }
            
            if(empty($stockOutput)) {
                
                $stockOutput = '-';
                
            }
            
            return $stockOutput;
            
        } else {
            
            return '-';
            
        }

    }
    
    /**
     * Fetch a product's total revenue
     * 
     * @global object $wpdb
     * @param int $id
     * @return int
     */
    private function _get_total_revenue($id) {
        
        global $wpdb;
        $prefix = $wpdb->base_prefix;

        $sql = "SELECT SUM( order_item_meta__line_total.meta_value) 
                as order_item_amount 
                FROM ".$prefix."posts AS posts 
                INNER JOIN ".$prefix."woocommerce_order_items AS order_items ON posts.ID = order_items.order_id 
                INNER JOIN ".$prefix."woocommerce_order_itemmeta AS order_item_meta__line_total ON (order_items.order_item_id = order_item_meta__line_total.order_item_id)  AND (order_item_meta__line_total.meta_key = '_line_total') 
                INNER JOIN ".$prefix."woocommerce_order_itemmeta AS order_item_meta__product_id_array ON order_items.order_item_id = order_item_meta__product_id_array.order_item_id 
                WHERE posts.post_type IN ('shop_order','shop_order_refund') 
                    AND posts.post_status IN ('wc-completed') 
                    AND ((order_item_meta__product_id_array.meta_key IN ('_product_id','_variation_id')
                    AND order_item_meta__product_id_array.meta_value IN ('%d')))";

        $num = $wpdb->get_var($wpdb->prepare($sql, $id));
        
        if(empty($num)) {
           
           $num = 0;
           
       }
       
       return $num;
        
    }
    
    private function _get_tickets_sold($ID) {
        
        $events_query = new WP_Query(array('post_type' => array('event_magic_tickets'), 'posts_per_page' => -1, 'meta_query' => array(array('key' => 'WooCommerceEventsProductID', 'value' => $ID))));

        return $events_query->found_posts; 
        
    }
    
}