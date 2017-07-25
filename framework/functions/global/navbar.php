<?php

// =============================================================================
// FUNCTIONS/GLOBAL/NAVBAR.PHP
// -----------------------------------------------------------------------------
// Handles all custom functionality for the navbar.
// =============================================================================

// =============================================================================
// TABLE OF CONTENTS
// -----------------------------------------------------------------------------
//   01. Get One Page Navigation Menu
//   02. Is One Page Navigation
//   03. Output Primary Navigation
//   04. Get Navbar Positioning
//   05. Get Logo and Navigation Layout
//   06. Navbar Searchform Popup
//   07. Navbar Search Navigation Item
// =============================================================================

// Output Primary Navigation
// =============================================================================

if ( ! function_exists( 'x_output_primary_navigation' ) ) :
	function x_output_primary_navigation() {
		crb_menus();
	}

endif;