<?php
/**
 * Site Class
 *
 * @package bcmsr
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Object to store site data
 */
class BCMSR_Site {
	// vars
	protected $name;
	protected $url;
	protected $block_editor;
	protected $block_editor_switch;

	public function __construct( $name, $url, $block_editor, $block_editor_switch, $theme_name, $theme_version ) {
		$this->name                = $name;
		$this->url                 = $url;
		$this->block_editor        = $block_editor;
		$this->block_editor_switch = $block_editor_switch;
		$this->theme_name          = $theme_name;
		$this->theme_version       = $theme_version;
	}

	public function site_link() {
		return "<a href='$this->url'>$this->name</a>";
	}

	public function checkmark( $input ) {
		return true === $input ? '<span class="dashicons dashicons-yes-alt"></span><span class="screen-reader-text">Yes</span>' : '';
	}

	public function url() {
		return $this->url;
	}

	public function name() {
		return $this->name;
	}

	public function block_editor() {
		return $this->block_editor;
	}

	public function block_editor_switch() {
		return $this->block_editor_switch;
	}

	public function theme_info() {
		return "<strong>$this->theme_name</strong> <small>$this->theme_version</small>";
	}
}
