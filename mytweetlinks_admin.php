<?php

function mytweetlinks_activate()

{

    global  $wpdb;

    $sql = "CREATE TABLE IF NOT EXISTS wp_mytweetlinks_plg_tweets (

    id int(11) NOT NULL AUTO_INCREMENT,

    post_id bigint(20) unsigned NOT NULL,

    tweet_content text,

    tweet_weight decimal(4,2) NOT NULL,

    PRIMARY KEY (id)

    ) ENGINE=MyISAM ";

    $wpdb->query($sql);

}



add_action('admin_head', 'mytweetlinks_plg_add_new_point_javascript');



function mytweetlinks_plg_add_new_point_javascript()



{



?>



<script type="text/javascript" >



function mytweetlinks_plg_add_new_point(post_id)



{

	

    var new_point = jQuery("#heading_text_new").val();



    var data = {



        action: 'mytweetlinks_plg_add_new_point',



        whatever: new_point,



        post_id: post_id



    };

	

    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php



    jQuery.post(ajaxurl, data, function(response) {



        //alert('Got this from the server: ' + response);



        jQuery("#table_key_points_tweets").html(response);



    });



}



</script>



<?php

}



add_action('wp_ajax_mytweetlinks_plg_add_new_point', 'mytweetlinks_plg_add_new_point_callback');



function mytweetlinks_plg_add_new_point_callback() {



    global $wpdb; // this is how you get access to the database



    $whatever = $_POST['whatever'];



    $post_id = $_POST['post_id'];



    $wpdb->query(

				 

            "

            INSERT INTO  wp_mytweetlinks_plg_tweets (post_id, tweet_content)

            VALUES ($post_id, '$whatever')

            "

    );





    echo mytweetlinks_render_tweet_table($post_id);



    die(); // this is required to return a proper result



}



add_action('admin_head', 'mytweetlinks_plg_delete_point_javascript');



function mytweetlinks_plg_delete_point_javascript()



{



?>



<script type="text/javascript" >



function mytweetlinks_plg_delete_point(key_point_id, post_id)



{

    var data = {



action: 'mytweetlinks_plg_delete_key_point',



        key_point_id: key_point_id,



        post_id: post_id



    };



    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php



	jQuery.post(ajaxurl, data, function(response) {



	//alert('Got this from the server: ' + response);



        jQuery("#table_key_points_tweets").html(response);



    });



}



</script>



<?php



}



add_action('wp_ajax_mytweetlinks_plg_delete_key_point', 'mytweetlinks_plg_delete_key_point_callback');



function mytweetlinks_plg_delete_key_point_callback() {



    global $wpdb; // this is how you get access to the database



    $post_id = $_POST['post_id'];



    $key_point_id = $_POST['key_point_id'];



    $wpdb->query(



        "

        DELETE FROM  wp_mytweetlinks_plg_tweets WHERE id=$key_point_id

        "



    );



    echo mytweetlinks_render_tweet_table($post_id);



    die(); // this is required to return a proper result



}



add_action('admin_head', 'mytweetlinks_plg_update_point_javascript');



function mytweetlinks_plg_update_point_javascript()



{



?>



<script type="text/javascript" >



function mytweetlinks_plg_update_point(key_point_id, post_id)



{



    var updated_point = jQuery('#tweet_'+key_point_id).val();



    var data = {



		action: 'mytweetlinks_plg_update_key_point',



        key_point_id: key_point_id,



        post_id: post_id,



        updated_point: updated_point



    };





    // since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php



    jQuery.post(ajaxurl, data, function(response) {



        //alert('Got this from the server: ' + response);



        jQuery("#table_key_points_tweets").html(response);



    });



}



</script>



<?php



}



add_action('wp_ajax_mytweetlinks_plg_update_key_point', 'mytweetlinks_plg_update_key_point_callback');



function mytweetlinks_plg_update_key_point_callback() {



    global $wpdb; // this is how you get access to the database



    $post_id = $_POST['post_id'];



    $key_point_id = $_POST['key_point_id'];



    $updated_point = $_POST['updated_point'];



    $sql = $wpdb->prepare("UPDATE wp_mytweetlinks_plg_tweets SET tweet_content='$updated_point' WHERE id=$key_point_id");



    $wpdb->query($sql);



    echo mytweetlinks_render_tweet_table($post_id);



    die(); // this is required to return a proper result



}



/* Define the custom box */



add_action( 'add_meta_boxes', 'mytweetlinks_add_custom_box' );



/* Adds a box to the main column on the Post and Page edit screens */



function mytweetlinks_add_custom_box() {



    add_meta_box( 



        'mytweetlinks_sectionid',



        'My Tweet Links',



        'mytweetlinks_admin_custom_box',



        'post' 



    );



}





/* Prints the box content */



function mytweetlinks_admin_custom_box($post)



{

    $post_id = $post->ID;



    //Use nonce for verification



    wp_nonce_field( plugin_basename( __FILE__ ), 'mytweetlinks_noncename' );



    // The actual fields for data entry



    echo 'Enter key points that visitors can easily tweet.<br /><br />';



    echo "<table id='table_key_points_tweets' class='form-table'>";



    echo mytweetlinks_render_tweet_table($post_id);



    echo '</table>';



}



function mytweetlinks_render_tweet_table($post_id)



{



    global $wpdb;



    $tweets = $wpdb->get_results( 



        "

        SELECT id, tweet_content

        FROM wp_mytweetlinks_plg_tweets

        WHERE post_id = $post_id

        ORDER BY id

        "



    );



    $output = "";



    $i = 1;



    foreach ($tweets as $tweet) 



    {


        $output .= "



        <tr>

            <td>

                $i<input type='text' id='tweet_{$tweet->id}' name='tweet_{$tweet->id}' value=\"{$tweet->tweet_content}\" size='60' maxlength='100' /> $value

				<input type='button' value='Update' title='Update Key Point' onclick='mytweetlinks_plg_update_point({$tweet->id},$post_id);return false;' class='button-secondary'/> 

                <input type='button' value='Delete' title='Delete Key Point' onclick='mytweetlinks_plg_delete_point({$tweet->id},$post_id);return false;' class='button-secondary'/> 

            </td>

        </tr>



        ";



        $i++;



    }

    

	$output .= "

	

    <tr>



		<td>



            <input type='text' id='heading_text_new' name='heading_text_new' value='' size='60' maxlength='100'  />



            <input type='button' value='Add New' onclick='mytweetlinks_plg_add_new_point($post_id);return false;' class='button-secondary'/> 



        </td>



    </tr>



    ";



    return $output;



}



?>