<?php   
    /* 
    Plugin Name: Star Rating Feedback 
    Plugin URI:
    Description: This plugin allows you to add a Star Rating feedback form. Currently it is localised for use in the UK
    Author: Stephen Scott & Somme Sakounthong
    Version: 0.2
    Author URI:
    */

/*  Copyright 2013  Stephen Scott  (email : s.p.scott@kainos.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, <http://www.gnu.org/licenses/> or
    write to the Free Software Foundation Inc., 59 Temple Place, 
    Suite 330, Boston, MA  02111-1307  USA.

    The GNU General Public License does not permit incorporating this
    program into proprietary programs.
*/
global $feedback_db_version;
$feedback_db_version = "1.2";

//during plugin install create the feedback table
function feedback_install() {
   global $wpdb;
   global $feedback_db_version;
      
   $sql = "CREATE TABLE IF NOT EXISTS `feedback` (
  `id` INT PRIMARY KEY AUTO_INCREMENT,
  `comment` varchar(600) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `date_added` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );";

   require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
   dbDelta( $sql );
   
   //add version of the plugin
   add_option( "feedback_db_version", $feedback_db_version );
}

//update feedback table
function feedback_update() {

    global $wpdb;
    global $feedback_db_version;
    $installed_ver = get_option( "feedback_db_version" );

    if ( $installed_ver != $feedback_db_version ) {

    	$wpdb->get_results($wpdb->prepare("ALTER TABLE feedback ADD id INT PRIMARY KEY AUTO_INCREMENT;"));

    	update_option( "feedback_db_version", $feedback_db_version );
    }

}

function myplugin_update_db_check() {
    global $feedback_db_version;
    if ( get_site_option( 'feedback_db_version' ) != $feedback_db_version ) {
        feedback_update();
    }
}



//when the plugin is activated run the install function
register_activation_hook( __FILE__, 'feedback_install' );

add_action( 'plugins_loaded', 'myplugin_update_db_check' );
//initialise the plugin by adding references to the CSS and JS
add_action('init','ava_test_init');
//add the shortcode for the feedback form
add_shortcode("feedback_form", "feedbackform_handler");

//allow shortcode in text widget
add_filter( 'widget_text', 'do_shortcode' );

function ava_test_init() {

    //wp_enqueue_style('font-awesome', 'http://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css');
    wp_enqueue_style('font-awesome', plugins_url('/stylesheets/font-awesome.min.css', __FILE__ ));
    wp_enqueue_style( 'jquery.datepick.css', plugins_url( '/stylesheets/jquery.datepick.css', __FILE__ ));
    wp_enqueue_style( 'style.css', plugins_url( '/stylesheets/style.css', __FILE__ ));

    wp_enqueue_script( 'jquery.validate.js', plugins_url( '/javascripts/jquery.validate.js', __FILE__ ), array('jquery'));
    wp_enqueue_script( 'jquery.raty.min.js', plugins_url( '/javascripts/jquery.raty.min.js', __FILE__ ) , array('jquery'));
    wp_enqueue_script( 'jquery.datepick.js', plugins_url( '/javascripts/jquery.datepick.js', __FILE__ ) , array('jquery'));
    wp_enqueue_script( 'jquery.datepick-en-GB.js', plugins_url( '/javascripts/jquery.datepick-en-GB.js', __FILE__ ) , array('jquery'));
    wp_enqueue_script( 'feedback.js', plugins_url( '/javascripts/feedback.js', __FILE__ ) , array('jquery'));
    
    $params = array(  'templateUrl' => plugins_url('/images',__FILE__));
    
    wp_localize_script( 'jquery.raty.min.js', 'MyScriptParams', $params );
    myplugin_update_db_check();
}

function feedbackform_handler($atts) {
  //set short code defaults
  $atts = shortcode_atts(
  array(
      'type'        => 'icon',
      'icon'        => 'star',
      'color'       => '#777777',
      'color_on'    => '#F0B315',
      'colors'      => '#F0B315,#F0B315,#F0B315,#F0B315,#F0B315,#F0B315,#F0B315,#F0B315,#F0B315,#F0B315',
      'size'        => '24px',
      'question'    => 'How happy are you?',
      'message'     => 'Thank you for providing your feedback.',
      'comments'    => 'Feedback comments',
      'amount'      => '5',
      'hints'       => '1,2,3,4,5,6,7,8,9,10',
      'staron'      => 'star-on.png',
      'staroff'     => 'star-off.png'
  ), $atts, 'feedback_form' );
      $type         = $atts['type'];
      $icon         = $atts['icon'];
      $color        = $atts['color'];
      $color_on     = $atts['color_on'];
      $colors       = $atts['colors'];
      $size         = $atts['size'];
      $question     = $atts['question'];
      $message      = $atts['message'];
      $comments     = $atts['comments'];
      $amount       = $atts['amount'];
      $hints        = $atts['hints'];
      $staron       = $atts['staron'];
      $staroff      = $atts['staroff'];

  $feedback_output = feedback_function($type, $icon, $color,  $color_on, $colors,  $size, $question, $message, $comments, $amount, $hints, $staron, $staroff);
  return $feedback_output;
 }

//function to create the feedback form when the shortcode is added to a page
function feedback_function($type, $icon, $color, $color_on, $colors, $size, $question, $message, $comments, $amount, $hints, $staron, $staroff) {
   //process plugin
   if (!isset($_POST['submit'])){

      //TO DO: noscript version based on amount of stars and hints

       $feedback_output .= "<form id='feedback' action='' method='post'>";
       $feedback_output .= '<div>
                            <div>
                              <h2 class="fauxLabel">' . $question . '</h2>
                              <noscript>
                                <div class="twelve columns radio">
                                  <label for="vUnhappy" class="inli$ne"><input type="radio" id="vUnhappy" value="1" name="happiness">Very unhappy</label> 
                                  <label for="unhappy" class="inline"><input type="radio" id="unhappy" value="2"  name="happiness">Unhappy</label> 
                                  <label for="fine" class="inline"><input type="radio" id="fine" value="3"  name="happiness">Fine</label>
                                  <label for="happy" class="inline"><input type="radio" id="happy" value="4"  name="happiness">Happy</label>
                                  <label for="vHappy" class="inline"><input type="radio" id="vHappy" value="5"  name="happiness">Very happy</label>
                                </div>
                              </noscript>
              		            <div class="star-rating" data-type="' . $type . '" data-icon="' . $icon . '"  data-color="' . $color . '" data-color-on="' . $color_on . '" data-colors="' . $colors . '" data-size="' . $size . '" data-amount="' . $amount . '" data-hints="' . $hints . '" data-staron="' . plugins_url('/images/',__FILE__) . $staron . '" data-staroff="' . plugins_url('/images/',__FILE__) . $staroff . '"></div>
                              <input type="hidden" id="rating" name="rating" value="0">
                              <label class="feedback-header" for="comments">' . $comments . '</label>
                              <textarea id="comments" name="comments" placeholder="Message (optional)" rows="5" maxlength="500" class="span8"></textarea>
                            </div>
                          </div>';
       $feedback_output .= "<input type='submit' name='submit' value='Submit' />";
       $feedback_output .= "</form>";
   }
   else
   {
       //submit form data to the Database
	$feedback_output = '<p class="message">'. $message.'</p>';
        global $wpdb;
	$comment = $_POST['comments'];
        $rating = 0;
	if (isset($_POST['happiness']))
        {
            $rating = $_POST['happiness'];
        }
        else
            $rating = $_POST['rating'];
        date_default_timezone_set('Europe/Dublin');
        $wpdb->insert('feedback', 
                array('comment' => $comment , 'rating' => $rating, 'date_added' => date("Y-m-d H:i:s")),
                array('%s', '%s', '%s')
                );
   }	 
   //send back text to calling function
   return $feedback_output;
 }

function xssafe($data,$encoding='UTF-8'){
  return htmlspecialchars($data,ENT_QUOTES | ENT_HTML401,$encoding);
}

function xecho($data){
  echo stripslashes(xssafe($data));
}

//display the Feedback admin form to allow the user to search feedback
function feedback_page () {
?>
<div class="wrap">
 	<form class="filter feedback-filter" method="POST" action="" >
            <div class="feedback-filter-header">
              <div id="icon-edit-comments" class="icon32"><br></div>
              <h3>Show feedback</h3>     
              </div>        
              <div class="feedback-inline">

                <div class="feedback-row">
                  <label for="dateRange1" ><span class="visually-hidden">Show feedback from </span></label>
                  <input type="text" name="afterDate" id="dateRange1" value="<?php if (isset($_POST['afterDate'])) 
                                                                                        xecho ($_POST['afterDate']);
                                                                                   else
                                                                                   {
                                                                                       $today = date("m/d/Y");
                                                                                       $date = strtotime($today .' -1 months');
                                                                                       xecho (date('d/m/Y', $date));
                                                                                   }
                                                                             ?>">
                </div>
                <div class="feedback-row">
                  <label for="dateRange2" ><span class="visually-hidden">Show feedback to </span></label>
                  <input type="text" name="beforeDate" id="dateRange2" value="<?php if (isset($_POST['beforeDate'])) 
                                                                                        xecho ($_POST['beforeDate']);
                                                                                    else
                                                                                    {
                                                                                       $today = date("m/d/Y");
                                                                                       $date = strtotime($today .' +1 days');
                                                                                       xecho (date("d/m/Y", $date));  
                                                                                    }
                                                                              ?>">
                </div>
                <div class="feedback-row">
                  <div class="feedback-row-button"><input type="submit" value="Refresh" class="button button-secondary"></div>

              </div>
   <div class="feedback-row">
               <div class="feedback-row-button"><input type="submit" name="deleteAll" value="Delete all messages" class="button button-secondary"></div>
</div>
              </div>
     
  
        </form>

  
        <table class="admin-table wp-list-table widefat fixed posts" cellspacing="0">
          <thead>
            <tr>
              <th scope="col" class="msg">Feedback message</th>
              <th scope="col" class="ratingcount">Rating</th>
              <th scope="col" class="date">Date</th>
              <th scope="col" class="delete">Delete</th>
            </tr>
          </thead>
          <tbody>
            <?php
                date_default_timezone_set('Europe/Dublin');
                global $wpdb;

                if ( isset( $_POST['deleteAll'] ) ) {
                    $wpdb->get_results($wpdb->prepare("DELETE FROM feedback"));
                }

                if ( isset( $_POST['delete'] ) ) {
                    $id_to_delete = $_POST['delete'];
                    $wpdb->get_results($wpdb->prepare("DELETE FROM feedback WHERE id = %s", $id_to_delete));
                }

                //get the dates from the form
                if (isset($_POST['beforeDate']) == false && isset($_POST['afterDate']) == false)
                {
                    $today = date("m/d/Y");
                    $date = strtotime($today .' -1 months');
                    $date2 = strtotime($today .' +1 days');
                    $afterDate = date('d/m/Y', $date);
                    $beforeDate = date("d/m/Y", $date2);
                }
                else
                {
                    $beforeDate = $_POST['beforeDate'];
                    $afterDate = $_POST['afterDate'];
                }

                $beforeDate = implode('/', array_reverse(explode('/', $beforeDate)));
                $afterDate = implode('/', array_reverse(explode('/', $afterDate)));

                $fields = array(
                            'beforeDate' => $beforeDate,
                            'afterDate' => $afterDate . ' 23:59:59'
                        );
                
                //get the feedback from the DB for the dates

		$comments= $wpdb->get_results($wpdb->prepare("SELECT * FROM feedback
                                                        where date_added >= %s 
                                                        and date_added <= %s",
                                            $afterDate,
                                            $beforeDate . ' 23:59:59')
                                        );
                $resultsHTML = '';
                if ($comments != null)
                {
                    $colour = 0;
                    
                    //output each feedback comment
                    foreach ($comments as $comment)
                    {
                        $rating = $comment -> rating;
                        $feedbackLarge = $comment -> comment;
                        $feedbackSmall = substr($feedbackLarge, 0, 100);
                        $date_added = $comment -> date_added;
                        $id = $comment -> id;
                        
                        //alternate the colour
                        if ($colour == 0)
                            $colour = 1;
                        else 
                            $colour = 0;

                        //add class="alternate" to below tr for alternating colours
                        echo "<tr class='admin-table-row";
                        if ($colour == 1) 
                        echo " alternate"; 
                        echo "'>";
                        echo "<td scope='row'><DIV class=text-container><DIV class='text-content short-text'>";
                  			xecho ($feedbackLarge);
                  			echo "</DIV></DIV></td>";
                        echo "<td scope='col'>";
                  			//xecho ($rating);
                        getStars($rating);
                  			echo "</td>";
                  			echo "<td scope='col'>";
                  			xecho ($date_added);
                  			echo "</td><td><form class='filter feedback-filter' method='POST' action='' ><button type='submit' name='delete' value='";
                  			echo $id;
                  			echo "' class='btn'><i class='fa fa-trash-o'>";
                        echo "</i></button></form></td></tr>";
                    }
                }

            ?>
          </tbody>
        </table>
      </div>
<?php
 }

 //add Admin feedback menu item
function feedback_menu () {
     add_menu_page('Feedback', 'Feedback Admin', 'edit_posts', 'feedback_admin', 'feedback_page');
 }

add_action('admin_menu', 'feedback_menu');

//add widget to Wordpress dashboard
function feedback_widget_function() {
	// Display whatever it is you want to show
        global $wpdb;
        $avgRating= $wpdb->get_row("SELECT avg(rating) as avgrating from feedback");
	echo '<table class="feedbackdashtable">';
  echo "<thead><tr><td></td><td>Rating</td><td>Total</td></tr></thead>";
  echo '<tbody><tr class="alternate"><td>';
  echo "Average";
  echo "</td><td>";
  getStars($avgRating -> avgrating); echo "</td><td>";
        $totalRes= $wpdb->get_row("SELECT count(*) as totalres from feedback");
	echo "" . $totalRes -> totalres . "</td>";
  



        $avgThisRating= $wpdb->get_row('SELECT avg(Rating) as avgrating from feedback where
                                date_added > date_format( curdate( ) - INTERVAL 0 MONTH , "%Y-%m-01 00:00:00" ) 
                                AND date_added < date_format( last_day( curdate( ) - INTERVAL 0 MONTH ) , "%Y-%m-%d 23:59:59" )');
	
  echo "</td></tr><tr><td>";
  echo "This Month";
  echo "</td><td>";
   getStars($avgThisRating -> avgrating); echo "</td><td>";
        $totalThisRes= $wpdb->get_row('SELECT count(*) as totalres from feedback where
                                date_added > date_format( curdate( ) - INTERVAL 0 MONTH , "%Y-%m-01 00:00:00" ) 
                                AND date_added < date_format( last_day( curdate( ) - INTERVAL 0 MONTH ) , "%Y-%m-%d 23:59:59" )');
	echo "" . $totalThisRes -> totalres . '</td></tr><tr class="alternate"><td>';
        
        $avgLastRating= $wpdb->get_row('SELECT avg(Rating) as avgrating from feedback where
                                date_added > date_format( curdate( ) - INTERVAL 1 MONTH , "%Y-%m-01 00:00:00" ) 
                                AND date_added < date_format( last_day( curdate( ) - INTERVAL 1 MONTH ) , "%Y-%m-%d 23:59:59" )');
	


  echo "Last Month"; 
  echo "</td><td>";
  getStars($avgLastRating -> avgrating); echo "</td><td>";
        $totalLastRes= $wpdb->get_row('SELECT count(*) as totalres from feedback where
                                date_added > date_format( curdate( ) - INTERVAL 1 MONTH , "%Y-%m-01 00:00:00" ) 
                                AND date_added < date_format( last_day( curdate( ) - INTERVAL 1 MONTH ) , "%Y-%m-%d 23:59:59" )');
	echo "" . $totalLastRes -> totalres . "</td></tr><tr><td>";
        
        $avgLast2Rating= $wpdb->get_row('SELECT avg(Rating) as avgrating from feedback where
                                date_added > date_format( curdate( ) - INTERVAL 2 MONTH , "%Y-%m-01 00:00:00" ) 
                                AND date_added < date_format( last_day( curdate( ) - INTERVAL 2 MONTH ) , "%Y-%m-%d 23:59:59" )');
	


  echo "Last 2 Month";
  echo "</td><td>";
   getStars($avgLast2Rating -> avgrating); echo "</td><td>";
        $totalLast2Res= $wpdb->get_row('SELECT count(*) as totalres from feedback where
                                date_added > date_format( curdate( ) - INTERVAL 2 MONTH , "%Y-%m-01 00:00:00" ) 
                                AND date_added < date_format( last_day( curdate( ) - INTERVAL 2 MONTH ) , "%Y-%m-%d 23:59:59" )');
	echo "" . $totalLast2Res -> totalres . "</tr></tbody></table>";
} 

// Create the function use in the action hook

//add widget to dashboard
function feedback_dashboard_widgets() {
	wp_add_dashboard_widget('feedback_widget', 'Feedback Dashboard', 'feedback_widget_function');	
} 

// Hook into the 'wp_dashboard_setup' action to register our other functions
add_action('wp_dashboard_setup', 'feedback_dashboard_widgets' );

function getStars($star){
    $roundStar = round($star);
    for ($x = 1; $x <= $roundStar; $x++) {
        echo '<i class="fa fa-star"></i>';
    }
}
?>