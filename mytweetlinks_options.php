<?php



add_action('admin_menu', 'mytweetlinks_settings');



function mytweetlinks_settings()



{

    add_submenu_page('options-general.php', 'MyTweetLinks Options', 'MyTweetLinks', 'administrator', 'mytweetlinks.php', 'mytweetlinks_settings_output');

}



function mytweetlinks_settings_output()



{



//Options names

    $heading_text_name = "mytweetlinks_plg_heading_text";

    $suggested_follow_name = "mytweetlinks_plg_suggested_follow";

	$div_bg_color_name = "mytweetlinks_plg_div_bg_color";

	$buffer_name = "mytweetlinks_plg_buffer";

	$implementation_type_name = "mytweetlinks_plg_implementation_type";



	$heading_text_value = get_option($heading_text_name, "");

	$suggested_follow_value = get_option($suggested_follow_name, "");

	$div_bg_color_value = get_option($div_bg_color_name, "");	

	$implementation_type_value = get_option($implementation_type_name, "");

	$buffer_value = get_option($buffer_name, 1);



    if(isset($_POST["update_options"]))

    {



		$heading_text_value = $_POST["heading_text"];

		$suggested_follow_value = $_POST["suggested_follow"];

		$div_bg_color_value = $_POST["div_bg_color"];

		$implementation_type_value = $_POST["implementation_type"];

		$buffer_value = 0;

	        if(isset($_POST["buffer"]))

    	    {

        	    $buffer_value = 1;

        	}

			

        update_option($heading_text_name, $heading_text_value);

        update_option($suggested_follow_name, $suggested_follow_value);

		update_option($div_bg_color_name, $div_bg_color_value);

		update_option($implementation_type_name, $implementation_type_value);

		update_option($buffer_name, $buffer_value);



        ?>



        <div class="updated"><p><strong>Options saved.</strong></p></div>



        <?php



    }



    ?>

    <div class="wrap">

    

	    <div id="icon-plugins" class="icon32"><br /></div>

    		

            <h2>MyTweetLinks Options</h2>

			

            <form name="options_form" method="post" action="">

            

            <table class="form-table">

            	<tr>

                	<td>Heading Text:</td>

                    <td>

                    	<input type="text" id="heading_text" name="heading_text" value="<?php echo $heading_text_value;?>" size="30" />

                        <span class="description">This is the text that appears above the list of tweet links.</span>

	                </td>

	            </tr>

                

                <tr>

                	<td>Implementation:</td>

                    <td>

                    	<!--<input type="text" id="implementation_type" name="implementation_type" value="<?php echo $implementation_type_value;?>" size="30" />-->

						

                        <select name="implementation_type" id="implementation_type" style="width:190px">



							<option value="auto" <?php if ( $implementation_type_value == "auto" ) { echo 'selected="selected""'; } ?>>Show below post content</option>

					

							<option value="shortcode" <?php if ( $implementation_type_value == "shortcode" ) { echo 'selected="selected""'; } ?> >Use Shortcode</option>

					

						</select> 



                        <span class="description">If displaying via short code, use <strong>[mytweetlinks]</strong> in your post or <strong>&lt;?php echo do_shortcode('[mytweetlinks]'); ?&gt;</strong> in your theme.</span>

                        

                    </td>

                </tr>

                

                <tr>



                	<td>Use Buffer:</td>



	                <td>



    	                <input type="checkbox" name="buffer" id="buffer" <?php if($buffer_value) echo "checked='checked'"; ?> >



        	            Include Buffer buttons. &nbsp;&nbsp; 



            	        <span class="description" style="padding-left:42px"><a href="http://bufferapp.com" target="_blank">What is Buffer?</a></span>



                	</td>



            	</tr>



            	<tr>



	                <td>Suggested Follow:</td>



		            <td>



	                    <input type="text" id="suggested_follow" name="suggested_follow" value="<?php echo $suggested_follow_value;?>" size="30" />



                    	<span class="description">Your Twitter ID. After posting tweet, you can suggest they follow you.</span>

                    

                    </td>

                    

				</tr>

                

                <tr>

                

                	<td>Div Background Color:</td>

                    

                    <td>

                    

                    <input type="text" id="div_bg_color" name="div_bg_color" value="<?php echo $div_bg_color_value;?>" size="30" />

                    

                    <span class="description"><a href="http://www.colorpicker.com/" target="_blank">Hex value</a>. No "#" please. You can make additional stylings in your theme on the div "mtl".</span>

                    

                    </td>

				

                </tr>

			

            </table>



			<p class="submit">

				

                <input type="submit" name="update_options" class="button-primary" value="Update Options" />

                

			</p>

            

            <p>

            

            	<a href="mailto:jordan.lyall+mtl@gmail.com">Email me</a> with any feeback or feature requests.

			

            </p>



			</form>

		

        </div>



<?php



}

	

?>