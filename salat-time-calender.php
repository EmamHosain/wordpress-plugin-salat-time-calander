<?php
/*
Plugin Name: Salat Time Calender
Plugin URI: https://eshopper.husaindev.com/
Description: A brief description of what your plugin does.
Version: 1.0
Author: Emam Hossain
Author URI: https://husaindev.com/
License: GPL2
*/



if (!defined('ABSPATH')) {
    // exit("Can't access");
    header("Location: /"); //
}


// run the function when the plugin is active
function stc_plugin_registration()
{

}
register_activation_hook(__FILE__, 'stc_plugin_registration');


// run the function when the plugin is deactive
function stc_plugin_deactivation()
{

}
register_deactivation_hook(__FILE__, 'stc_plugin_deactivation');



// external js and css file include
function stc_plugin_script_and_css_setup()
{
    // Enqueue JavaScript
    $path = plugins_url('assets/script.js', __FILE__);
    $dependency = array('jquery');
    $version = filemtime(plugin_dir_path(__FILE__) . 'assets/script.js');
    wp_enqueue_script('my-custom-js', $path, $dependency, $version, true);


    wp_add_inline_script('my-custom-js', 'var ajax_url = "' . admin_url('admin-ajax.php') . '";', 'before');


    // Enqueue CSS
    $css_path = plugins_url('assets/style.css', __FILE__);
    $css_version = filemtime(plugin_dir_path(__FILE__) . 'assets/style.css');
    $css_dependency = array();
    wp_enqueue_style('my-custom-css', $css_path, $css_dependency, $css_version);

}
add_action('wp_enqueue_scripts', 'stc_plugin_script_and_css_setup'); // files load only for frontend 






function stc_main_page_func()
{
    date_default_timezone_set('Asia/Dhaka');
    // The third-party API URL
    $current_month = date('m');
    $current_year = date('Y');
    $api_url = "https://api.aladhan.com/v1/calendarByCity?city=rajshahi&country=bangladesh&month=$current_month&year=$current_year";

    // Make the API request
    $response = wp_remote_get($api_url);

    // Check for errors
    if (is_wp_error($response)) {
        echo 'Error fetching data.';
        return;
    }

    // Decode the JSON response
    $data = json_decode(wp_remote_retrieve_body($response), true);

    // Check if data is valid
    if (empty($data) || !is_array($data)) {
        echo 'No data found.';
        return;
    }


    $current_date = date('d-m-Y');
    $current_data = array_filter($data['data'], function ($item) use ($current_date) {
        if ($item['date']['gregorian']['date'] === $current_date) {
            return $item;
        }
    });

    include 'admin/main-page.php';
}
function stc_admin_menu()
{
    add_menu_page('Salat Time Calender', 'Salat Time Calender', 'manage_options', 'salat-time-calender', 'stc_main_page_func', '', 6);
}
add_action('admin_menu', 'stc_admin_menu');







// sorting function 
function formatDate($date)
{
    $slice_date = substr($date, 0, 5); // Extracts the first 5 characters (HH:MM)
    $dateTime = DateTime::createFromFormat('H:i', $slice_date); // Parse as "H:i" format
    if ($dateTime) {
        return $dateTime->format('h:i A'); // Format as "h:i A" (12-hour format with AM/PM)
    }
    return $slice_date; // Return original if parsing fails
}

function sorting_func()
{
    date_default_timezone_set('Asia/Dhaka');
    $city = $_POST['city'];
    $month = $_POST['month'];
    $year = !empty($_POST['year']) ? $_POST['year'] : date('Y');
    $city = empty($city) ? 'rajshahi' : strtolower($city);
    $country = 'bangladesh';

    if (empty($month)) {
        $month = date('m'); // current month and 04 = April this format
    } else {
        $dateTime = DateTime::createFromFormat('F', $month);
        $month = $dateTime->format('m'); // 04 = April
    }

    $api_url = "https://api.aladhan.com/v1/calendarByCity?city=$city&country=$country&month=$month&year=$year";
    // Make the API request
    $response = wp_remote_get($api_url);

    // Check for errors
    if (is_wp_error($response)) {
        echo 'Error fetching data.';
        return;
    }

    // Decode the JSON response
    $data = json_decode(wp_remote_retrieve_body($response), true);
    // Check if data is valid
    if (empty($data) || !is_array($data)) {
        echo 'No data found.';
        return;
    }

    $day = $_POST['day'];
    if (!empty($day)) {
        $data['data'] = array_filter($data['data'], function ($item) use ($day) {
            return $item['date']['gregorian']['day'] == $day; // Return true for matching day
        });
    }

    ob_start();
    if (!empty($data['data'])) {
        foreach ($data['data'] as $item) {
            ?>
            <tr>
                <td><?php echo $item['date']['gregorian']['date']; ?></td>
                <td><?php echo formatDate($item['timings']['Fajr']); ?></td>
                <td><?php echo formatDate($item['timings']['Sunrise']); ?></td>
                <td><?php echo formatDate($item['timings']['Dhuhr']); ?></td>
                <td><?php echo formatDate($item['timings']['Asr']); ?></td>
                <td><?php echo formatDate($item['timings']['Sunset']); ?></td>
                <td><?php echo formatDate($item['timings']['Maghrib']); ?></td>
                <td><?php echo formatDate($item['timings']['Isha']); ?></td>
            </tr>
            <?php
        }
    } else {
        ?>
        <tr>
            <td style="text-align:center" colspan="8">No data found.</td>
        </tr>
        <?php
    }
    echo ob_get_clean();
    wp_die();

}
add_action('wp_ajax_sorting_func', 'sorting_func');
add_action('wp_ajax_nopriv_sorting_func', 'sorting_func'); // this is for unauthenticated user