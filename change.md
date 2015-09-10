/* Habilitar multiples imagenes para las categorias de los productos woocoomerce
add categories to attachments  */

/*function wptp_add_categories_to_attachments() {
    //register_taxonomy_for_object_type( 'category', 'attachment' ); 
    register_taxonomy_for_object_type( 'product_cat', 'attachment' ); 
}  
add_action( 'init' , 'wptp_add_categories_to_attachments' );

/* mostrar el el panel de administracion las categorias de los productos woocommerce */
// Add to admin_init function
/*function maletek_columns_head($defaults) {
    $defaults['first_column']  = 'Categorias de Productos';
    return $defaults;
}
// GENERAL PURPOSE
function maletek_columns_content($column_name, $post_ID) {
    if ($column_name == 'first_column') {
        // DO STUFF FOR first_column COLUMN
       //echo 'The post ID is: ' . $post_ID;
        $product_cats = wp_get_post_terms( $post_ID , 'product_cat' );

        foreach ($product_cats as $product_cats ) {
            echo $product_cats->name . ",";
        }
    }
}
add_filter('manage_media_columns', 'maletek_columns_head');
add_filter('manage_media_custom_column', 'maletek_columns_content', 10, 2);