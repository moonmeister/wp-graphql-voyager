<?php
/**
 * Plugin Name:     WP GraphQL Voyager
 * Plugin URI:      https://wpgraphql.com
 * Description:     This plugin provides the GraphQL Voyager interactive graph as an admin page in WordPress, allowing the GraphQL WPGraphQL
 * schema to be browsed from within WordPress.
 * Author:          Alex Moon, WPGraphQL, Jason Bahl
 * Author URI:      http://wpgraphql.com
 * Text Domain:     wp-graphql-voyager
 * Domain Path:     /languages
 * Version:         0.0.1
 *
 * @package         WP GraphQL Voyager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WPGraphQLVoyager' ) ) :

class WPGraphQLVoyager {

	public function init() {
		if ( ! defined( 'WPGRAPHQL_VOYAGER_PLUGIN_DIR' ) ) {
			define( 'WPGRAPHQL_VOYAGER_PLUGIN_DIR', plugin_dir_url( __FILE__ ) );
		}
		add_action( 'admin_menu', [ $this, 'register_admin_page' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_react_app' ] );
	}

	public function is_wpgraphql_active() {
		return class_exists( 'WPGraphQL' );
	}

	public function register_admin_page() {
		add_menu_page(
			__( 'GraphQL Voyager', 'wp-graphql-voyager' ),
			'GraphQL Voyager',
			'manage_options',
			'wp-graphql-voyager/wp-graphql-voyager.php',
			[ $this, 'render_admin_page' ],
			'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCA0MDAgNDAwIj48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNNTcuNDY4IDMwMi42NmwtMTQuMzc2LTguMyAxNjAuMTUtMjc3LjM4IDE0LjM3NiA4LjN6Ii8+PHBhdGggZmlsbD0iI0UxMDA5OCIgZD0iTTM5LjggMjcyLjJoMzIwLjN2MTYuNkgzOS44eiIvPjxwYXRoIGZpbGw9IiNFMTAwOTgiIGQ9Ik0yMDYuMzQ4IDM3NC4wMjZsLTE2MC4yMS05Mi41IDguMy0xNC4zNzYgMTYwLjIxIDkyLjV6TTM0NS41MjIgMTMyLjk0N2wtMTYwLjIxLTkyLjUgOC4zLTE0LjM3NiAxNjAuMjEgOTIuNXoiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNNTQuNDgyIDEzMi44ODNsLTguMy0xNC4zNzUgMTYwLjIxLTkyLjUgOC4zIDE0LjM3NnoiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNMzQyLjU2OCAzMDIuNjYzbC0xNjAuMTUtMjc3LjM4IDE0LjM3Ni04LjMgMTYwLjE1IDI3Ny4zOHpNNTIuNSAxMDcuNWgxNi42djE4NUg1Mi41ek0zMzAuOSAxMDcuNWgxNi42djE4NWgtMTYuNnoiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNMjAzLjUyMiAzNjdsLTcuMjUtMTIuNTU4IDEzOS4zNC04MC40NSA3LjI1IDEyLjU1N3oiLz48cGF0aCBmaWxsPSIjRTEwMDk4IiBkPSJNMzY5LjUgMjk3LjljLTkuNiAxNi43LTMxIDIyLjQtNDcuNyAxMi44LTE2LjctOS42LTIyLjQtMzEtMTIuOC00Ny43IDkuNi0xNi43IDMxLTIyLjQgNDcuNy0xMi44IDE2LjggOS43IDIyLjUgMzEgMTIuOCA0Ny43TTkwLjkgMTM3Yy05LjYgMTYuNy0zMSAyMi40LTQ3LjcgMTIuOC0xNi43LTkuNi0yMi40LTMxLTEyLjgtNDcuNyA5LjYtMTYuNyAzMS0yMi40IDQ3LjctMTIuOCAxNi43IDkuNyAyMi40IDMxIDEyLjggNDcuN00zMC41IDI5Ny45Yy05LjYtMTYuNy0zLjktMzggMTIuOC00Ny43IDE2LjctOS42IDM4LTMuOSA0Ny43IDEyLjggOS42IDE2LjcgMy45IDM4LTEyLjggNDcuNy0xNi44IDkuNi0zOC4xIDMuOS00Ny43LTEyLjhNMzA5LjEgMTM3Yy05LjYtMTYuNy0zLjktMzggMTIuOC00Ny43IDE2LjctOS42IDM4LTMuOSA0Ny43IDEyLjggOS42IDE2LjcgMy45IDM4LTEyLjggNDcuNy0xNi43IDkuNi0zOC4xIDMuOS00Ny43LTEyLjhNMjAwIDM5NS44Yy0xOS4zIDAtMzQuOS0xNS42LTM0LjktMzQuOSAwLTE5LjMgMTUuNi0zNC45IDM0LjktMzQuOSAxOS4zIDAgMzQuOSAxNS42IDM0LjkgMzQuOSAwIDE5LjItMTUuNiAzNC45LTM0LjkgMzQuOU0yMDAgNzRjLTE5LjMgMC0zNC45LTE1LjYtMzQuOS0zNC45IDAtMTkuMyAxNS42LTM0LjkgMzQuOS0zNC45IDE5LjMgMCAzNC45IDE1LjYgMzQuOSAzNC45IDAgMTkuMy0xNS42IDM0LjktMzQuOSAzNC45Ii8+PC9zdmc+'
		);
	}

	public function render_admin_page() {

		if ( $this->is_wpgraphql_active() ) {
			echo '<div class="wrap"><div id="wp-graphql-voyager" class="graphql-voyager-container">Loading ...</div></div>';
		} else {
			echo '<div class="wrap"><h1>This plugin requires WPGraphQL to be installed to work. Please install WPGraphQL (https://github.com/wp-graphql/wp-graphql) and visit this page again.</h1></div>';
		}

	}

	/**
	 * Gets the contents of the Create React App manifest file
	 *
	 * @return array|bool|string
	 */
	public function get_app_manifest() {
		$manifest = file_get_contents( dirname( __FILE__ ) . '/assets/app/build/asset-manifest.json' );
		$manifest = (array) json_decode( $manifest );
		return $manifest;
	}

	/**
	 * Gets the path to the stylesheet compiled by Create React App
	 *
	 * @return string
	 */
	public function get_app_stylesheet() {
		$manifest = $this->get_app_manifest();
		if ( empty( $manifest['main.css'] ) ) {
			return '';
		}
		return WPGRAPHQL_VOYAGER_PLUGIN_DIR . 'assets/app/build/' . $manifest['main.css'];
	}

	/**
	 * Gets the path to the built javascript file compiled by Create React App
	 *
	 * @return string
	 */
	public function get_app_script() {
		$manifest = $this->get_app_manifest();
		if ( empty( $manifest['main.js'] ) ) {
			return '';
		}
		return WPGRAPHQL_VOYAGER_PLUGIN_DIR . 'assets/app/build/' . $manifest['main.js'];
	}

	/**
	 * Enqueues the stylesheet and js for the WPGraphiQL app
	 */
	public function enqueue_react_app() {

		/**
		 * Only enqueue the assets on the proper admin page, and only if WPGraphQL is also active
		 */
		if ( strpos( get_current_screen()->id, 'wp-graphql-voyager/wp-graphql-voyager' ) && $this->is_wpgraphql_active() ) {

			wp_enqueue_style( 'wp-graphql-voyager', $this->get_app_stylesheet(), array(), false, false );
			wp_enqueue_script( 'wp-graphql-voyager-helpers', $this->get_app_script_helpers(), array( 'jquery' ), false, true );
			wp_enqueue_script( 'wp-graphql-voyager', $this->get_app_script(), array(), false, true );

			/**
			 * Create a nonce
			 */
			wp_localize_script(
				'wp-graphql-voyager',
				'wpGraphQLVoyagerSettings',
				array(
					'nonce' => wp_create_nonce( 'wp_rest' ),
					'graphqlEndpoint' => trailingslashit( site_url() ) . 'index.php?' . \WPGraphQL\Router::$route,
				)
			);

		}
	}

}

endif; // End if class_exists()

add_action( 'plugins_loaded', function() {

	$wp_graphiql = new WPGraphQLVoyager();
	$wp_graphiql->init();

} );
