<?php
/**
 * Site List
 *
 * @package bcmsr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Object to store list of all sites and their users
 */
class BCMSR_Site_List {
	// vars
	protected $sites;


	public function __construct() {
		$this->sites = array();
	}


	public function output() {
		$output  = '';
		$output .= '
		<table class="bcmsr-table widefat fixed posts">
			<thead>
				<tr>
					<th data-sort="string">Site</th>
					<th data-sort="string">Block Editor</th>
					<th data-sort="string">Optional</th>
					<th data-sort="string">Theme</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th>Site</th>
					<th>Block Editor</th>
					<th>Optional</th>
					<th>Theme</th>
				</tr>
			</tfoot>
			<tbody>';

		foreach ( $this->sites as $site ) {
			$output .= '<tr><td>';
			$output .= $site->site_link();
			$output .= '</td><td>';
			$output .= $site->checkmark( $site->block_editor() );
			$output .= '</td><td>';
			$output .= $site->checkmark( $site->block_editor_switch() );
			$output .= '</td><td>';
			$output .= $site->theme_info();
			$output .= '</td>';
		}
			$output .= '</tbody>
		</table>';
		return $output;
	}

	private function sort_sites( $data ) {
		usort( $data, array( $this, 'sort_by_site_name' ) );
		return $data;
	}

	private function sort_by_site_name( $a, $b ) {
		return strnatcmp( $a->name(), $b->name() );
	}

	public function load_sites() {
		// Cache results to a transient for performance. If runs if transient is expired.
		if ( false === ( $this->sites = get_transient( 'bcmsr-sites' ) ) ) {
			// this code runs when there is no valid transient set.
			global $wpdb;
			$blogs = get_sites(
				array(
					'number'   => 2048, // arbitrary large number.
					'archived' => 0,
					'deleted'  => 0,
				)
			);
			foreach ( $blogs as $blog ) {
				$blog_id = (int) $blog->blog_id;
				$info    = get_blog_details( $blog_id );
				switch_to_blog( $blog_id );
				$block_editor        = 'block' === get_option( 'classic-editor-replace' ) ? true : false;
				$block_editor_switch = 'allow' === get_option( 'classic-editor-allow-users' ) ? true : false;
				$theme               = wp_get_theme();
				$theme_name          = $theme->get( 'Name' );
				$theme_version       = $theme->get( 'Version' );
				restore_current_blog();

				$this->sites[ $blog_id ] = new BCMSR_Site(
					$info->blogname,
					$info->siteurl,
					$block_editor,
					$block_editor_switch,
					$theme_name,
					$theme_version
				);

			}
			$this->sites = $this->sort_sites( $this->sites );

			// Set the transient.
			set_transient( 'bcmsr-sites', $this->sites, 30 );
		}
	}
}
