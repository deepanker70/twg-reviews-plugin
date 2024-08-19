<?php
/*
Plugin Name: TWG Reviews Plugin
Description: A plugin to add reviews with custom fields.
Version: 1.0
Author: Deepanker Verma
*/

// Register Custom Post Type
function twg_register_review_post_type() {
    $labels = array(
        'name'               => 'Reviews',
        'singular_name'      => 'Review',
        'menu_name'          => 'Reviews',
        'name_admin_bar'     => 'Review',
        'add_new'            => 'Add New',
        'add_new_item'       => 'Add New Review',
        'new_item'           => 'New Review',
        'edit_item'          => 'Edit Review',
        'view_item'          => 'View Review',
        'all_items'          => 'All Reviews',
        'search_items'       => 'Search Reviews',
        'not_found'          => 'No reviews found.',
        'not_found_in_trash' => 'No reviews found in Trash.',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'publicly_queryable' => true,
        'show_ui'            => true,
        'show_in_menu'       => true,
        'query_var'          => true,
        'rewrite'            => array( 'slug' => 'review' ),
        'capability_type'    => 'post',
        'has_archive'        => true,
        'hierarchical'       => false,
        'menu_position'      => null,
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
    );

    register_post_type( 'twg_review', $args );
}
add_action( 'init', 'twg_register_review_post_type' );


// Register Brand Taxonomy
function twg_register_brand_taxonomy() {
    $labels = array(
        'name'              => _x('Brands', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Brand', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Brands', 'textdomain'),
        'all_items'         => __('All Brands', 'textdomain'),
        'parent_item'       => __('Parent Brand', 'textdomain'),
        'parent_item_colon' => __('Parent Brand:', 'textdomain'),
        'edit_item'         => __('Edit Brand', 'textdomain'),
        'update_item'       => __('Update Brand', 'textdomain'),
        'add_new_item'      => __('Add New Brand', 'textdomain'),
        'new_item_name'     => __('New Brand Name', 'textdomain'),
        'menu_name'         => __('Brand', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'brand'),
    );

    register_taxonomy('twg_brand', array('twg_review'), $args);
}

// Register Product Type Taxonomy
function twg_register_product_type_taxonomy() {
    $labels = array(
        'name'              => _x('Product Types', 'taxonomy general name', 'textdomain'),
        'singular_name'     => _x('Product Type', 'taxonomy singular name', 'textdomain'),
        'search_items'      => __('Search Product Types', 'textdomain'),
        'all_items'         => __('All Product Types', 'textdomain'),
        'parent_item'       => __('Parent Product Type', 'textdomain'),
        'parent_item_colon' => __('Parent Product Type:', 'textdomain'),
        'edit_item'         => __('Edit Product Type', 'textdomain'),
        'update_item'       => __('Update Product Type', 'textdomain'),
        'add_new_item'      => __('Add New Product Type', 'textdomain'),
        'new_item_name'     => __('New Product Type Name', 'textdomain'),
        'menu_name'         => __('Product Type', 'textdomain'),
    );

    $args = array(
        'hierarchical'      => true,
        'labels'            => $labels,
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array('slug' => 'product-type'),
    );

    register_taxonomy('twg_product_type', array('twg_review'), $args);
}

// Hook into the 'init' action
add_action('init', 'twg_register_brand_taxonomy', 0);
add_action('init', 'twg_register_product_type_taxonomy', 0);

function twg_add_review_meta_boxes() {
    add_meta_box(
        'twg_review_details',
        __( 'Review Details', 'twg_review' ),
        'twg_render_review_meta_box',
        'twg_review',
        'normal',
        'high'
    );
}
add_action( 'add_meta_boxes', 'twg_add_review_meta_boxes' );

function twg_render_review_meta_box( $post ) {
    $product_name = get_post_meta( $post->ID, '_twg_product_name', true );
    $summary = get_post_meta( $post->ID, '_twg_review_summary', true );
    $stars = get_post_meta( $post->ID, '_twg_review_stars', true );
    $pros = get_post_meta( $post->ID, '_twg_review_pros', true );
    $cons = get_post_meta( $post->ID, '_twg_review_cons', true );
    $price = get_post_meta( $post->ID, '_twg_review_price', true );
    $buy_link = get_post_meta( $post->ID, '_twg_buy_link', true );

    wp_nonce_field( 'twg_save_review_meta_box_data', 'twg_review_meta_box_nonce' );
    ?>
    <div class="twg-meta-box">
        <!-- Product Name -->
        <p>
            <label for="twg_product_name"><?php _e( 'Product Name', 'twg_review' ); ?></label>
            <input type="text" id="twg_product_name" name="twg_product_name" value="<?php echo esc_attr( $product_name ); ?>" style="width:100%;" />
        </p>

        <!-- Review Summary -->
        <p>
            <label for="twg_review_summary"><?php _e( 'Review Summary', 'twg_review' ); ?></label>
            <textarea id="twg_review_summary" name="twg_review_summary" rows="4" style="width:100%;"><?php echo esc_textarea( $summary ); ?></textarea>
        </p>

        <!-- Star Rating -->
        <p>
            <label for="twg_review_stars"><?php _e( 'Star Rating', 'twg_review' ); ?></label>
            <div id="twg_star_rating_slider"></div>
            <input type="hidden" id="twg_review_stars" name="twg_review_stars" value="<?php echo esc_attr( $stars ); ?>" />
            <span id="twg_star_rating_value"><?php echo esc_attr( $stars ); ?></span>
        </p>

        <!-- Pros -->
        <p>
            <label for="twg_review_pros"><?php _e( 'Pros', 'twg_review' ); ?></label>
            <textarea id="twg_review_pros" name="twg_review_pros" rows="4" style="width:100%;"><?php echo esc_textarea( $pros ); ?></textarea>
        </p>

        <!-- Cons -->
        <p>
            <label for="twg_review_cons"><?php _e( 'Cons', 'twg_review' ); ?></label>
            <textarea id="twg_review_cons" name="twg_review_cons" rows="4" style="width:100%;"><?php echo esc_textarea( $cons ); ?></textarea>
        </p>

        <!-- Price -->
        <p>
            <label for="twg_review_price"><?php _e( 'Price', 'twg_review' ); ?></label>
            <input type="text" id="twg_review_price" name="twg_review_price" value="<?php echo esc_attr( $price ); ?>" style="width:100%;" />
        </p>

        <!-- Buy Link -->
        <p>
            <label for="twg_buy_link"><?php _e( 'Buy Link', 'twg_review' ); ?></label>
            <input type="url" id="twg_buy_link" name="twg_buy_link" value="<?php echo esc_attr( $buy_link ); ?>" style="width:100%;" />
        </p>
    </div>
    <?php
}

function twg_save_review_meta_box_data( $post_id ) {
    if ( !isset( $_POST['twg_review_meta_box_nonce'] ) || !wp_verify_nonce( $_POST['twg_review_meta_box_nonce'], 'twg_save_review_meta_box_data' ) ) {
        return;
    }

    if ( isset( $_POST['twg_product_name'] ) ) {
        update_post_meta( $post_id, '_twg_product_name', sanitize_text_field( $_POST['twg_product_name'] ) );
    }

    if ( isset( $_POST['twg_review_summary'] ) ) {
        update_post_meta( $post_id, '_twg_review_summary', sanitize_textarea_field( $_POST['twg_review_summary'] ) );
    }

    if ( isset( $_POST['twg_review_stars'] ) ) {
        update_post_meta( $post_id, '_twg_review_stars', floatval( $_POST['twg_review_stars'] ) );
    }

    if ( isset( $_POST['twg_review_pros'] ) ) {
        update_post_meta( $post_id, '_twg_review_pros', sanitize_textarea_field( $_POST['twg_review_pros'] ) );
    }

    if ( isset( $_POST['twg_review_cons'] ) ) {
        update_post_meta( $post_id, '_twg_review_cons', sanitize_textarea_field( $_POST['twg_review_cons'] ) );
    }

    if ( isset( $_POST['twg_review_price'] ) ) {
        update_post_meta( $post_id, '_twg_review_price', sanitize_text_field( $_POST['twg_review_price'] ) );
    }

    if ( isset( $_POST['twg_buy_link'] ) ) {
        update_post_meta( $post_id, '_twg_buy_link', esc_url_raw( $_POST['twg_buy_link'] ) );
    }
}
add_action( 'save_post', 'twg_save_review_meta_box_data' );

function twg_display_review_details( $content ) {
    if ( is_singular( 'twg_review' ) ) {
        $summary = get_post_meta( get_the_ID(), '_twg_review_summary', true );
        $stars = get_post_meta( get_the_ID(), '_twg_review_stars', true );
        $pros = get_post_meta( get_the_ID(), '_twg_review_pros', true );
        $cons = get_post_meta( get_the_ID(), '_twg_review_cons', true );
        $price = get_post_meta( get_the_ID(), '_twg_review_price', true );
        $product_name = get_post_meta( get_the_ID(), '_twg_product_name', true );
        $buy_link = get_post_meta( get_the_ID(), '_twg_buy_link', true );

        $output = "<div class='twg-review-details'>";
        $output .= "<h3>{$product_name}</h3>";
        $output .= "<p>{$summary}</p>";
        $output .= "<div class='twg-star-rating'>" . twg_get_star_rating_html( $stars ) . "</div>";
        
        $output .= "<div class='twg-pros-cons'>";
        $output .= "<div class='twg-pros'><h4>Pros</h4><ul>" . twg_format_list( $pros ) . "</ul></div>";
        $output .= "<div class='twg-cons'><h4>Cons</h4><ul>" . twg_format_list( $cons ) . "</ul></div>";
        $output .= "</div>";
        
        if($price!='')
            $output .= "<div class='twg-price'>Price: {$price}</div>";
            
        if($buy_link!='')
            $output .= "<div class='twg-buy-link'><a href='{$buy_link}' target='_blank' class='buy-now-button'>Buy Now</a></div>";
        $output .= "</div>";

        $schema = array(
            "@context" => "https://schema.org",
            "@type" => "Review",
            "itemReviewed" => array(
                "@type" => "Product",
                "name" => $product_name,
            ),
            "reviewRating" => array(
                "@type" => "Rating",
                "ratingValue" => $stars,
                "bestRating" => "5",
                "worstRating" => "1"
            ),
            "author" => array(
                "@type" => "Person",
                "name" => get_the_author(),
            ),
            "reviewBody" => $summary,
            "offers" => array(
                "@type" => "Offer",
                "price" => $price,
                "priceCurrency" => "USD",
                "url" => $buy_link
            ),
            "pros" => explode( "\n", $pros ),
            "cons" => explode( "\n", $cons ),
        );

        $output .= '<script type="application/ld+json">' . wp_json_encode( $schema ) . '</script>';

        $content .= $output;
    }
    return $content;
}

add_filter( 'the_content', 'twg_display_review_details' );

function twg_format_list( $text ) {
    $items = explode( "\n", trim( $text ) ); // Split by new lines
    $list = '';

    foreach ( $items as $item ) {
        $list .= "<li>" . esc_html( $item ) . "</li>";
    }

    return $list;
}

function twg_get_star_rating_html( $rating ) {
    $output = '';
    $whole_stars = floor( $rating );
    $half_star = $rating - $whole_stars >= 0.5;
    
    for ( $i = 0; $i < $whole_stars; $i++ ) {
        $output .= '<span class="star full">&#9733;</span>'; // Full star
    }
    
    if ( $half_star ) {
        $output .= '<span class="star half">&#9733;</span>'; // Half star
    }
    
    $remaining_stars = 5 - ceil( $rating );
    for ( $i = 0; $i < $remaining_stars; $i++ ) {
        $output .= '<span class="star empty">&#9733;</span>'; // Empty star
    }

    return $output;
}


function twg_enqueue_review_styles() {
    wp_enqueue_style( 'twg-review-style', plugins_url( 'twg-reviews-style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'twg_enqueue_review_styles' );

function twg_enqueue_admin_scripts() {
    wp_enqueue_script( 'jquery-ui-slider' );
    wp_enqueue_style( 'jquery-ui-slider-css', '//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css' );
    wp_enqueue_script( 'twg-admin-script', plugin_dir_url( __FILE__ ) . 'js/twg-admin.js', array( 'jquery', 'jquery-ui-slider' ), null, true );
    
}
add_action( 'admin_enqueue_scripts', 'twg_enqueue_admin_scripts' );