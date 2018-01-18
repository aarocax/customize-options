<?php
/*
    Plugin Name: Customize Options Plugin
    Description: Set options for text in footer and social urls.
    Author: MasMedios
    Version: 1.0.0
*/

defined( 'ABSPATH' ) or die( ' ' );

class Customize_options {
    private $fields_name;

    public function __construct() {
        load_plugin_textdomain( 'php-code-widget', false, dirname( plugin_basename( __FILE__ ) ) );
        add_action( 'admin_menu', array( $this, 'create_plugin_settings_page' ) );
        add_action( 'admin_init', array( $this, 'setup_sections' ) );
        add_action( 'admin_init', array( $this, 'setup_fields' ) );
    }
    public function create_plugin_settings_page() {
        // Add the menu item and page
        $page_title = 'Customize Options';
        $menu_title = 'Customize Options';
        $capability = 'manage_options';
        $slug = 'customize_options_fields';
        $callback = array( $this, 'plugin_settings_page_content' );
        add_submenu_page( 'options-general.php', $page_title, $menu_title, $capability, $slug, $callback );
    }
    public function plugin_settings_page_content() {?>
        <div class="wrap">
            <h2>Customize Options Page</h2><?php
            if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] ){
                  $this->admin_notice();
            } ?>
            <form method="POST" action="options.php">
                <?php
                    settings_fields( 'customize_options_fields' );
                    do_settings_sections( 'customize_options_fields' );
                    submit_button();
                ?>
            </form>
        </div> <?php
    }
    
    public function admin_notice() { ?>
        <div class="notice notice-success is-dismissible">
            <p>Your settings have been updated!</p>
        </div><?php
    }
    public function setup_sections() {
        add_settings_section( 'footer_text_section', '<h1><b>Footer Text</b></h1>', array( $this, 'section_callback' ), 'customize_options_fields' );
        add_settings_section( 'social_links_section', '<h1><b>Social Links</b></h1>', array( $this, 'section_callback' ), 'customize_options_fields' );
    }
    public function section_callback( $arguments ) {
        switch( $arguments['id'] ){
            case 'footer_text_section':
                echo 'This is the text to show in footer!';
                break;
            case 'social_links_section':
                echo 'Social links';
                break;
        }
    }
    public function setup_fields() {
        $fields = array(
            
            array(
                'uid' => 'co_footer_textarea_field',
                'label' => 'Text to show in footer',
                'section' => 'footer_text_section',
                'type' => 'textarea',
                'supplimental' => 'use: echo get_option( "co_footer_textarea_field" );',
            ),

            array(
                'uid' => 'co_facebook_url_field',
                'label' => 'facebook url Field',
                'section' => 'social_links_section',
                'type' => 'text',
                'placeholder' => 'https://www.facebook.com/your-page',
                'helper' => 'Insert the facebook url',
                'supplimental' => 'use: echo get_option( "co_facebook_url_field" );',
            ),
            array(
                'uid' => 'co_twitter_url_field',
                'label' => 'twitter url Field',
                'section' => 'social_links_section',
                'type' => 'text',
                'placeholder' => 'https://twitter.com/your-page',
                'helper' => 'Insert the twitter url',
                'supplimental' => 'use: echo get_option( "co_twitter_url_field" );',
            ),
            array(
                'uid' => 'co_instagram_url_field',
                'label' => 'instagram url Field',
                'section' => 'social_links_section',
                'type' => 'text',
                'placeholder' => 'https://instagram.com/your-page',
                'helper' => 'Insert the instagram url',
                'supplimental' => 'use: echo get_option( "co_instagram_url_field" );',
            ),
            array(
                'uid' => 'co_youtube_url_field',
                'label' => 'youtube url Field',
                'section' => 'social_links_section',
                'type' => 'text',
                'placeholder' => 'https://youtube.com/your-page',
                'helper' => 'Insert the youtube url',
                'supplimental' => 'use: echo get_option( "co_instagram_url_field" );',
            ),

            
        );
        foreach( $fields as $field ){
            add_settings_field( $field['uid'], $field['label'], array( $this, 'field_callback' ), 'customize_options_fields', $field['section'], $field );
            register_setting( 'customize_options_fields', $field['uid'] );
        }
        
        $this->fields_name = $fields;
    }
    public function field_callback( $arguments ) {
        $value = get_option( $arguments['uid'] );

        if( ! $value ) {
            $value = $arguments['default'];
        }
        switch( $arguments['type'] ){
            case 'text':
            case 'password':
            case 'number':
                printf( '<input name="%1$s" id="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />', $arguments['uid'], $arguments['type'], $arguments['placeholder'], $value );
                break;
            case 'textarea':
                printf( '<textarea name="%1$s" id="%1$s" placeholder="%2$s" rows="5" cols="50">%3$s</textarea>', $arguments['uid'], $arguments['placeholder'], $value );
                break;
            case 'select':
            case 'multiselect':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $attributes = '';
                    $options_markup = '';
                    foreach( $arguments['options'] as $key => $label ){
                        $options_markup .= sprintf( '<option value="%s" %s>%s</option>', $key, selected( $value[ array_search( $key, $value, true ) ], $key, false ), $label );
                    }
                    if( $arguments['type'] === 'multiselect' ){
                        $attributes = ' multiple="multiple" ';
                    }
                    printf( '<select name="%1$s[]" id="%1$s" %2$s>%3$s</select>', $arguments['uid'], $attributes, $options_markup );
                }
                break;
            case 'radio':
            case 'checkbox':
                if( ! empty ( $arguments['options'] ) && is_array( $arguments['options'] ) ){
                    $options_markup = '';
                    $iterator = 0;
                    foreach( $arguments['options'] as $key => $label ){
                        $iterator++;
                        $options_markup .= sprintf( '<label for="%1$s_%6$s"><input id="%1$s_%6$s" name="%1$s[]" type="%2$s" value="%3$s" %4$s /> %5$s</label><br/>', $arguments['uid'], $arguments['type'], $key, checked( $value[ array_search( $key, $value, true ) ], $key, false ), $label, $iterator );
                    }
                    printf( '<fieldset>%s</fieldset>', $options_markup );
                }
                break;
        }
        if( $helper = $arguments['helper'] ){
            printf( '<span class="helper"> %s</span>', $helper );
        }
        if( $supplimental = $arguments['supplimental'] ){
            printf( '<p class="description">%s</p>', $supplimental );
        }
    }

    public function getFields() {
        return $this->fields_name;
    }
}
new Customize_options();