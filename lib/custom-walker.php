<?php

class Walker_Nav_Menu_Mobile extends Walker_Nav_Menu{
	var $to_depth = -1;

    function start_lvl(&$output, $depth){
      $output .= '</option>';
    }

    function end_lvl(&$output, $depth){
      $indent = str_repeat("\t", $depth); // don't output children closing tag
    }
	
    function start_el(&$output, $item, $depth, $args){
		$indent = ( $depth ) ? str_repeat( "&nbsp;", $depth * 4 ) : '';
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		$value = ' value="'. $item->url .'"';
		$output .= '<option'.$id.$value.$class_names.'>';
		$item_output = $args->before;
		$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
		$output .= $indent.$item_output;
    }

    function end_el(&$output, $item, $depth){
		if(substr($output, -9) != '</option>')
      		$output .= "</option>"; // replace closing </li> with the option tag
    }

}