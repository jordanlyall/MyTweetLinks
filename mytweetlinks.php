<?php



/*

Plugin Name: MyTweetLinks

Plugin URI: http://mytweetlinks.com

Description: Let your readers easily tweet the key points of a blog post. 

Version: 1.1.1

Author: Jordan Lyall

Author URI: http://about.me/jordan

*/



//Front



function mytweetlinks_add_content($text)



{

    global $wpdb, $post;



	if(get_post_type($post) == "post")

{



		$post_id = $post->ID;



        $tweets = $wpdb->get_results( 



            "

            SELECT tweet_content

            FROM wp_mytweetlinks_plg_tweets

            WHERE post_id = $post_id

            ORDER BY id

            "



        );



        if(count($tweets))



        {



            $heading_text_value = get_option("mytweetlinks_plg_heading_text");

            $suggested_follow_value = get_option("mytweetlinks_plg_suggested_follow");

			$div_bg_color_value = get_option("mytweetlinks_plg_div_bg_color");

			$buffer_value = get_option("mytweetlinks_plg_buffer");

			



			$permalink = get_permalink($post_id);



            $text_add = "<div id=\"mtl\" style=\"background-color: #{$div_bg_color_value}; padding: 15px 5px 5px 15px; margin: 0 0 10px 0;\"><h2>{$heading_text_value}</h2>";



            foreach ($tweets as $tweet)



            {



                $text_add .= "<ul>";



 				$tweet->tweet_content = substr($tweet->tweet_content, 0, 100);



                if(strlen($tweet->tweet_content) == 100)



               {



					$tweet->tweet_content = substr($tweet->tweet_content, 0, strrpos($tweet->tweet_content, " "));



                    $tweet->tweet_content .= "...";





                }





                $text_encoded = urlencode($tweet->tweet_content);            



                $text_add .= "<li>{$tweet->tweet_content} <a href='https://twitter.com/share' class='twitter-share-button' data-url='{$permalink}' data-text='{$tweet->tweet_content}' data-via='mytweetlinks' data-related='{$suggested_follow_value}' data-count='none'>Tweet</a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src='//platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document,'script','twitter-wjs');</script>";



                if($buffer_value)



                {



                    $text_add .= "&nbsp;<a href='http://bufferapp.com/add' class='buffer-add-button' data-text='{$tweet->tweet_content}' data-url='{$permalink}' data-count='none'>Buffer</a><script type='text/javascript' src='http://static.bufferapp.com/js/button.js'></script></li>";



                }



                $text_add .= "</ul>";



            }



			$text_add .= "</div><!-- end mtl div -->";	



            return $text . $text_add;



        }





    }



    return $text;



}



$implementation_type_value = get_option("mytweetlinks_plg_implementation_type");



if ( $implementation_type_value == "auto" ) 



{  

add_filter('the_content', 'mytweetlinks_add_content');

}



else

{

add_shortcode( 'mytweetlinks', 'mytweetlinks_add_content' );

}







//Admin



register_activation_hook( __FILE__, 'mytweetlinks_activate' );



require_once "mytweetlinks_admin.php";





//Options



require_once "mytweetlinks_options.php";



?>