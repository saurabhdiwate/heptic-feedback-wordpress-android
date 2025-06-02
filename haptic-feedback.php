<?php
/**
 * Plugin Name: Haptic Feedback
 * Plugin URI: https://wordpress.org/plugins/haptic-feedback/
 * Description: A lightweight plugin that enables haptic feedback on buttons and hyperlinks for Android devices.
 * Version: 1.0.0
 * Author: Your Name
 * Author URI: https://yourwebsite.com
 * Text Domain: haptic-feedback
 * Domain Path: /languages
 * License: GPL-2.0+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

// Define plugin constants
define('HAPTIC_FEEDBACK_VERSION', '1.0.0');
define('HAPTIC_FEEDBACK_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('HAPTIC_FEEDBACK_PLUGIN_URL', plugin_dir_url(__FILE__));

/**
 * The core plugin class
 */
class Haptic_Feedback {
    
    /**
     * Plugin version
     *
     * @var string
     */
    private $version = '1.0.0';

    /**
     * Initialize the plugin
     */
    public function __construct() {
        // Register activation hook
        register_activation_hook(__FILE__, array($this, 'activate'));
        
        // Register deactivation hook
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
        
        // Enqueue frontend scripts
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        
        // Add admin menu
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Register settings
        add_action('admin_init', array($this, 'register_settings'));
        
        // Add plugin action links
        add_filter('plugin_action_links_' . plugin_basename(__FILE__), array($this, 'add_plugin_action_links'));
    }

    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        $defaults = array(
            'haptic_enabled' => 1,
            'haptic_duration' => 50,
            'haptic_selectors' => 'a, button, .btn, input[type="button"], input[type="submit"]'
        );
        
        add_option('haptic_feedback_options', $defaults);
    }

    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Cleanup if needed
    }

    /**
     * Enqueue front-end scripts and styles
     */
    public function enqueue_scripts() {
        $options = get_option('haptic_feedback_options');
        
        // Only enqueue if haptic feedback is enabled
        if (!empty($options['haptic_enabled'])) {
            wp_enqueue_script(
                'haptic-feedback-js',
                HAPTIC_FEEDBACK_PLUGIN_URL . 'js/haptic-feedback.js',
                array('jquery'),
                HAPTIC_FEEDBACK_VERSION,
                true
            );
            
            // Pass options to script
            wp_localize_script(
                'haptic-feedback-js',
                'hapticFeedback',
                array(
                    'duration' => absint($options['haptic_duration']),
                    'selectors' => esc_attr($options['haptic_selectors'])
                )
            );
        }
    }

    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_options_page(
            __('Haptic Feedback Settings', 'haptic-feedback'),
            __('Haptic Feedback', 'haptic-feedback'),
            'manage_options',
            'haptic-feedback-settings',
            array($this, 'render_admin_page')
        );
    }

    /**
     * Register plugin settings
     */
    public function register_settings() {
        register_setting(
            'haptic_feedback_options_group',
            'haptic_feedback_options',
            array($this, 'validate_options')
        );
        
        add_settings_section(
            'haptic_feedback_general_section',
            __('General Settings', 'haptic-feedback'),
            array($this, 'render_general_section'),
            'haptic-feedback-settings'
        );
        
        add_settings_field(
            'haptic_enabled',
            __('Enable Haptic Feedback', 'haptic-feedback'),
            array($this, 'render_haptic_enabled_field'),
            'haptic-feedback-settings',
            'haptic_feedback_general_section'
        );
        
        add_settings_field(
            'haptic_duration',
            __('Vibration Duration (ms)', 'haptic-feedback'),
            array($this, 'render_haptic_duration_field'),
            'haptic-feedback-settings',
            'haptic_feedback_general_section'
        );
        
        add_settings_field(
            'haptic_selectors',
            __('CSS Selectors', 'haptic-feedback'),
            array($this, 'render_haptic_selectors_field'),
            'haptic-feedback-settings',
            'haptic_feedback_general_section'
        );
        
        // Load text domain for translations
        load_plugin_textdomain('haptic-feedback', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Validate options
     */
    public function validate_options($input) {
        $validated = array();
        
        // Verify nonce field - added for security
        if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'haptic_feedback_options_group-options')) {
            add_settings_error('haptic_feedback_options', 'security_error', __('Security check failed. Please try again.', 'haptic-feedback'), 'error');
            return get_option('haptic_feedback_options');
        }
        
        $validated['haptic_enabled'] = isset($input['haptic_enabled']) ? 1 : 0;
        
        $validated['haptic_duration'] = absint($input['haptic_duration']);
        if ($validated['haptic_duration'] < 1) {
            $validated['haptic_duration'] = 50; // Default value
        } elseif ($validated['haptic_duration'] > 1000) {
            $validated['haptic_duration'] = 1000; // Maximum value
            add_settings_error('haptic_feedback_options', 'duration_error', __('Vibration duration cannot exceed 1000ms. Value has been adjusted.', 'haptic-feedback'), 'warning');
        }
        
        // Enhanced sanitization
        $validated['haptic_selectors'] = sanitize_text_field($input['haptic_selectors']);
        
        return $validated;
    }

    /**
     * Render general section info
     */
    public function render_general_section() {
        echo '<p>' . __('Configure haptic feedback settings for your WordPress site.', 'haptic-feedback') . '</p>';
    }

    /**
     * Render haptic enabled field
     */
    public function render_haptic_enabled_field() {
        $options = get_option('haptic_feedback_options');
        ?>
        <input type='checkbox' name='haptic_feedback_options[haptic_enabled]' <?php checked($options['haptic_enabled'], 1); ?> value='1'>
        <span class="description"><?php _e('Enable haptic feedback for Android devices', 'haptic-feedback'); ?></span>
        <?php
    }

    /**
     * Render haptic duration field
     */
    public function render_haptic_duration_field() {
        $options = get_option('haptic_feedback_options');
        ?>
        <input type='number' name='haptic_feedback_options[haptic_duration]' min='1' max='1000' value='<?php echo absint($options['haptic_duration']); ?>'>
        <span class="description"><?php _e('Duration of vibration in milliseconds (1-1000)', 'haptic-feedback'); ?></span>
        <?php
    }

    /**
     * Render haptic selectors field
     */
    public function render_haptic_selectors_field() {
        $options = get_option('haptic_feedback_options');
        ?>
        <input type='text' name='haptic_feedback_options[haptic_selectors]' class='regular-text' value='<?php echo esc_attr($options['haptic_selectors']); ?>'>
        <p class="description"><?php _e('CSS selectors to apply haptic feedback (comma-separated)', 'haptic-feedback'); ?></p>
        <?php
    }

    /**
     * Render admin settings page
     */
    public function render_admin_page() {
        if (!current_user_can('manage_options')) {
            return;
        }
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            <form action='options.php' method='post'>
                <?php
                settings_fields('haptic_feedback_options_group');
                do_settings_sections('haptic-feedback-settings');
                submit_button();
                ?>
            </form>
            <div class="haptic-feedback-info">
                <h2><?php _e('About Haptic Feedback', 'haptic-feedback'); ?></h2>
                <p><?php _e('This plugin provides haptic feedback (vibration) for Android devices when users interact with elements on your website.', 'haptic-feedback'); ?></p>
                <p><?php _e('Note: Haptic feedback only works on Android devices that support the vibration API.', 'haptic-feedback'); ?></p>
            </div>
        </div>
        <?php
    }
}

/**
 * Add settings link to plugin actions
 *
 * @param array $links Plugin action links
 * @return array Modified plugin action links
 */
public function add_plugin_action_links($links) {
    $settings_link = '<a href="' . admin_url('options-general.php?page=haptic-feedback-settings') . '">' . __('Settings', 'haptic-feedback') . '</a>';
    array_unshift($links, $settings_link);
    return $links;
}

}

// Initialize the plugin
$haptic_feedback = new Haptic_Feedback();
