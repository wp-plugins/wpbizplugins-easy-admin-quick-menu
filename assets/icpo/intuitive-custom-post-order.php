<?php
/*
Plugin Name: Intuitive Custom Post Order
Plugin URI: http://hijiriworld.com/web/plugins/intuitive-custom-post-order/
Description: Intuitively, Order Items (Posts, Pages, and Custom Post Types) using a Drag and Drop Sortable JavaScript.
Version: 2.0.8
Author: hijiri
Author URI: http://hijiriworld.com/web/
*/

/*  Copyright 2013 hijiri

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/** 
 *
 * Modified by WPBizPlugins to fit plugin EAQM specifically.
 * Wrapped needed stuff in conditionals to only load in admin, as that's the only place the plugin is used.
 *
 **/

/***************************************************************

	Define

***************************************************************/

define( 'HICPO_WPBIZPLUGINS_URL', plugins_url('', __FILE__) );

define( 'HICPO_WPBIZPLUGINS_DIR', plugin_dir_path(__FILE__) );

// Define a static post type for this.
define( 'HICPO_WPBIZPLUGINS_POST_TYPE', 'wpbizplugins-eaqm' );


/***************************************************************

	Class & Method

***************************************************************/

$hicpo = new Hicpo_EAQM();

class Hicpo_EAQM
{
	function __construct()
	{
	
		if( wpbizplugins_eaqm_return_post_type() == HICPO_WPBIZPLUGINS_POST_TYPE ) {
			add_action( 'admin_init', array( &$this, 'refresh' ) );
			add_action( 'admin_init', array( &$this, 'load_script_css' ) );
		}
		
		add_action( 'wp_ajax_update-menu-order', array( &$this, 'update_menu_order' ) );
		
		// pre_get_posts
		add_filter( 'pre_get_posts', array( &$this, 'hicpo_filter_active' ) );
		add_filter( 'pre_get_posts', array( &$this, 'hicpo_pre_get_posts' ) );
		
		// previous_post_link(), next_post_link()
		add_filter( 'get_previous_post_where', array( &$this, 'hicpo_previous_post_where' ) );
		add_filter( 'get_previous_post_sort', array( &$this, 'hicpo_previous_post_sort' ) );
		add_filter( 'get_next_post_where', array( &$this, 'hocpo_next_post_where' ) );
		add_filter( 'get_next_post_sort', array( &$this, 'hicpo_next_post_sort' ) );
	}
	
	function load_script_css() {

		// load JavaScript
		wp_enqueue_script( 'jQuery' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'hicpojs', HICPO_WPBIZPLUGINS_URL.'/js/hicpo.js', array( 'jquery' ), null, true );
		// load CSS
		wp_enqueue_style( 'hicpo', HICPO_WPBIZPLUGINS_URL.'/css/hicpo.css', array(), null );

	}
	
	function refresh()
	{

		// menu_orderを再構築する
		global $wpdb;
		
		$object = HICPO_WPBIZPLUGINS_POST_TYPE;
		
		$sql = "SELECT
					ID
				FROM
					$wpdb->posts
				WHERE
					post_type = '".$object."'
					AND post_status IN ('publish', 'pending', 'draft', 'private', 'future')
				ORDER BY
					menu_order ASC
				";
				
		$results = $wpdb->get_results($sql);
		
		foreach( $results as $key => $result ) {
			// 新規追加した場合「menu_order=0」で登録されるため、常に1からはじまるように振っておく
			$wpdb->update( $wpdb->posts, array( 'menu_order' => $key+1 ), array( 'ID' => $result->ID ) );
		}

	}
	
	function update_menu_order()
	{
		global $wpdb;
		
		parse_str($_POST['order'], $data);
		
		if ( is_array($data) ) {
			
			// ページに含まれる記事のIDをすべて取得
			$id_arr = array();
			foreach( $data as $key => $values ) {
				foreach( $values as $position => $id ) {
					$id_arr[] = $id;
				}
			}
			
			// ページに含まれる記事のmenu_orderをすべて取得
			$menu_order_arr = array();
			foreach( $id_arr as $key => $id ) {
				$results = $wpdb->get_results("SELECT menu_order FROM $wpdb->posts WHERE ID = ".$id);
				foreach( $results as $result ) {
					$menu_order_arr[] = $result->menu_order;
				}
			}
			// menu_order配列をソート（キーと値の相関関係は維持しない）
			sort($menu_order_arr);
			
			foreach( $data as $key => $values ) {
				foreach( $values as $position => $id ) {
					$wpdb->update( $wpdb->posts, array( 'menu_order' => $menu_order_arr[$position] ), array( 'ID' => $id ) );
				}
			}
		}
	}
	
	function hicpo_previous_post_where( $where )
	{
		global $post;

		$current_menu_order = $post->menu_order;	
		$where = "WHERE p.menu_order > '".$current_menu_order."' AND p.post_type = '". $post->post_type ."' AND p.post_status = 'publish'";	
		
		return $where;
	}
	
	function hicpo_previous_post_sort( $orderby )
	{

		$orderby = 'ORDER BY p.menu_order ASC LIMIT 1';
		
		return $orderby;
	}
	
	function hocpo_next_post_where( $where )
	{
		global $post;


		$current_menu_order = $post->menu_order;
		$where = "WHERE p.menu_order < '".$current_menu_order."' AND p.post_type = '". $post->post_type ."' AND p.post_status = 'publish'";

		return $where;
	}
	
	function hicpo_next_post_sort( $orderby )
	{
		
		$orderby = 'ORDER BY p.menu_order DESC LIMIT 1';	
		return $orderby;
	}
	
	function hicpo_filter_active( $wp_query )
	{
		// get_postsの場合 suppress_filters=true となる為、フィルタリングを有効にする
		if ( isset($wp_query->query['suppress_filters']) ) $wp_query->query['suppress_filters'] = false;
		if ( isset($wp_query->query_vars['suppress_filters']) ) $wp_query->query_vars['suppress_filters'] = false;
		return $wp_query;
	}
	
	function hicpo_pre_get_posts( $wp_query )
	{
		$objects[] = HICPO_WPBIZPLUGINS_POST_TYPE;

		if ( is_array( $objects ) ) {
		
			// for Admin ---------------------------------------------------------------
			
			if ( is_admin() && !defined( 'DOING_AJAX' ) ) {
			
				// post_type=post or page or custom post type
				// adminの場合、post_tyope=postも渡される
				if ( isset( $wp_query->query['post_type'] ) ) {
					if ( in_array( $wp_query->query['post_type'], $objects ) ) {
						$wp_query->set( 'orderby', 'menu_order' );
						$wp_query->set( 'order', 'ASC' );
					}
				}
			
			// for Template ------------------------------------------------------------
			
			} else {
				
				$active = false;
				
				// postsのWordpressループ ----------------
				
				// $wp_query->queryが空配列の場合
				// WordPressループでもposts以外はpost_typeが渡される
				
				if ( empty( $wp_query->query ) ) {
					if ( in_array( 'post', $objects ) ) {
						$active = true;
					}
				} else {
				
					// get_posts() ----------------------
				
					// 完全な判別ではないが、suppress_filtersパラメータの有無で判別
					// get_posts()の場合、post_type, orderby, orderパラメータは必ず渡される
				
					if ( isset($wp_query->query['suppress_filters']) ) {
						
						// post_type判定
						if ( is_array( $wp_query->query['post_type'] ) ) {
							$post_types = $wp_query->query['post_type'];
							foreach( $post_types as $post_type ) {
								if ( in_array( $post_type, $objects ) ) {
									$active = true;
								}
							}
						} else {
							if ( in_array( $wp_query->query['post_type'], $objects ) ) {
								$active = true;
							}
						}
							
					// query_posts() or WP_Query()
					} else {
						
						// post_typeが指定されている場合
						if ( isset( $wp_query->query['post_type'] ) ) {
							
							// post_type判定
							if ( is_array( $wp_query->query['post_type'] ) ) {
								$post_types = $wp_query->query['post_type'];
								foreach( $post_types as $post_type ) {
									if ( in_array( $post_type, $objects ) ) {
										$active = true;
									}
								}
							} else {
								if ( in_array( $wp_query->query['post_type'], $objects ) ) {
									$active = true;
								}
							}
						// post_typeが指定されてい場合はpost_type=post
						} else {
							if ( in_array( 'post', $objects ) ) {
								$active = true;
							}
						}
					}	
				}
				
				if ( $active ) {
					if ( !isset( $wp_query->query['orderby'] ) || $wp_query->query['orderby'] == 'date' ) $wp_query->set( 'orderby', 'menu_order' );
					if ( !isset( $wp_query->query['order'] ) || $wp_query->query['order'] == 'DESC' ) $wp_query->set( 'order', 'ASC' );
				}				
			}
		}
	}
}


?>