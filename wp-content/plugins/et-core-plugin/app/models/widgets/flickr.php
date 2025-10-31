<?php
namespace ETC\App\Models\Widgets;

use ETC\App\Models\Widgets;

/**
 * Flicker widget.
 *
 * @since      1.4.4
 * @package    ETC
 * @subpackage ETC/Models/Widgets
 */
class Flickr extends Widgets {
    
    function __construct()
    {
        $widget_ops = array('classname' => 'flickr', 'description' => 'Photos from flickr.');
        $control_ops = array('id_base' => 'etheme_flickr-widget');
        parent::__construct('etheme_flickr-widget', '8theme - Flickr Photos', $widget_ops, $control_ops);
    }
    
    function widget($args, $instance)
    {
	    if (parent::admin_widget_preview(esc_html__('Flickr Photos', 'xstore-core')) !== false) return;
	    $ajax = ( !empty($instance['ajax'] ) ) ? $instance['ajax'] : '';

	    if (apply_filters('et_ajax_widgets', $ajax)){
		    echo et_ajax_element_holder( 'Flickr', $instance, '', '', 'widget', $args );
		    return;
	    }
        extract($args);

        $screen_name = (!empty($instance['screen_name'])) ? $instance['screen_name'] : '';
        $number      = (!empty($instance['number'])) ? $instance['number'] : '';
        $show_button = (!empty($instance['show_button'])) ? $instance['show_button'] : '';
        
        if(!$screen_name || $screen_name == '') {
            $screen_name = '95572727@N00';
        }
        
        echo $before_widget;
	    echo parent::etheme_widget_title($args, $instance); // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotEscaped
        
        if($screen_name && $number) {
            echo '<script type="text/javascript" src="http://www.flickr.com/badge_code_v2.gne?count='.$number.'&display=latest&size=s&layout=x&source=user&user='.$screen_name.'"></script>';
        }
        
        echo $after_widget;
    }
    
    function update($new_instance, $old_instance)
    {
        $instance = $old_instance;

        $instance['title'] = strip_tags($new_instance['title']);
        $instance['screen_name'] = $new_instance['screen_name'];
        $instance['number'] = $new_instance['number'];
	    $instance['ajax'] = (bool) $new_instance['ajax'];
        return $instance;
    }

    function form($instance)
    {
        $defaults = array('title' => 'Photos from Flickr', 'screen_name' => '', 'number' => 6, 'show_button' => 1);
        $instance = wp_parse_args((array) $instance, $defaults);
	    $ajax = isset( $instance['ajax'] ) ? (bool) $instance['ajax'] : false; ?>
        
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
            <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
        </p>
        
        <p>
            <label for="<?php echo $this->get_field_id('screen_name'); ?>">Flickr ID</label>
            <input class="widefat" style="width: 216px;" id="<?php echo $this->get_field_id('screen_name'); ?>" name="<?php echo $this->get_field_name('screen_name'); ?>" value="<?php echo $instance['screen_name']; ?>" />
            <br/>
            <p class="help">To find your flickID visit <a href="http://idgettr.com/" target="_blank">idGettr</a>.</p>
        </p>


        <p>
            <label for="<?php echo $this->get_field_id('number'); ?>">Number of photos to show:</label>
            <input class="widefat" style="width: 30px;" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>" value="<?php echo $instance['number']; ?>" />
        </p>

        <?php parent::widget_input_checkbox( esc_html__( 'Use ajax preload for this widget', 'xstore-core' ), $this->get_field_id( 'ajax' ), $this->get_field_name( 'ajax' ), checked( $ajax, true, false ), 1 );
	    ?>
        
    <?php
    }
}
