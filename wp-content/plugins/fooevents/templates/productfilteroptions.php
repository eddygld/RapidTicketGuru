<select name="fooevents_filter">

    <option value=""><?php esc_attr_e('All Products', 'woocommerce-events'); ?></option>
    
    <option value="events" 
    <?php 
    if ('events' == $fooevents_filter) {
        echo 'selected';} 
    ?>
     ><?php esc_attr_e('Events', 'woocommerce-events'); ?></option>

    <option value="non-events" 
    <?php 
    if ('non-events' == $fooevents_filter) {
        echo 'selected';} 
    ?>
     ><?php esc_attr_e('Non-Events', 'woocommerce-events'); ?></option>
    
</select>