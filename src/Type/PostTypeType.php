<?php
namespace WPGraphQL\Type;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQLRelay\Relay;
use WPGraphQL\Connections;
use WPGraphQL\Data\DataSource;
use WPGraphQL\Types;

class PostTypeType extends ObjectType {

	public function __construct() {

		$node_definition = DataSource::get_node_definition();

		$config = [
			'name' => 'postType',
			'description' => __( 'An Post Type object', 'wp-graphql' ),
			'fields' => function() {
				$fields = [
					'id' => [
						'type' => Types::non_null( Types::id() ),
						'resolve' => function( $post_type, $args, $context, ResolveInfo $info ) {
							return ( ! empty( $post_type->name ) && ! empty( $post_type->name ) ) ? Relay::toGlobalId( 'post_type', $post_type->name ) : null;
						},
					],
					'name' => [
						'type' => Types::string(),
						'description' => esc_html__( 'The internal name of the post type. This should not be used for 
						display purposes.', 'wp-graphql' ),
					],
					'label' => [
						'type' => Types::string(),
						'description' => esc_html__( 'Display name of the content type.', 'wp-graphql' ),
					],
					//@todo: 'labels' => $types->post_type_labels(),
					'description' => [
						'type' => Types::string(),
						'description' => esc_html__( 'Description of the content type.', 'wp-graphql' ),
					],
					'public' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether a post type is intended for use publicly either via the 
						admin interface or by front-end users. While the default settings of exclude_from_search, 
						publicly_queryable, show_ui, and show_in_nav_menus are inherited from public, each does not 
						rely on this relationship and controls a very specific intention.', 'wp-graphql' ),
					],
					'hierarchical' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether the post type is hierarchical, for example pages.', 'wp-graphql' ),
					],
					'excludeFromSearch' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether to exclude posts with this post type from front end search 
						results.', 'wp-graphql' ),
						'resolve' => function( $post_type, $args, $context, ResolveInfo $info ) {
							return ! empty( $post_type->exclude_from_search ) ? $post_type->exclude_from_search : false;
						},
					],
					'publicly_queryable' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether queries can be performed on the front end for the post 
						type as part of parse_request().', 'wp-graphql' ),
					],
					'show_ui' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether to generate and allow a UI for managing this post type 
						in the admin.', 'wp-graphql' ),
					],
					'show_in_menu' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Where to show the post type in the admin menu. To work, $show_ui 
						must be true. If true, the post type is shown in its own top level menu. If false, no menu is shown. If a string of an existing top level menu (eg. "tools.php" or "edit.php?post_type=page"), the post type will be placed as a sub-menu of that.', 'wp-graphql' ),
					],
					'show_in_nav_menus' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Makes this post type available for selection in navigation 
						menus.', 'wp-graphql' ),
					],
					'show_in_admin_bar' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Makes this post type available via the admin bar.', 'wp-graphql' ),
					],
					'menu_position' => [
						'type' => Types::int(),
						'description' => esc_html__( 'The position of this post type in the menu. Only applies if 
						show_in_menu is true.', 'wp-graphql' ),
					],
					'menu_icon' => [
						'type' => Types::string(),
						'description' => esc_html__( 'The name of the icon file to display as a menu icon.', 'wp-graphql' ),
					],
					//                  @todo: build out this field
					//					'taxonomies'          => [
					//						'type'            => Types::list_of( Types::string() ),
					//						'description'     => esc_html__( 'List of taxonomies available to the post type.', 'wp-graphql' ),
					//					],
					'has_archive' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether this content type should have archives. Content archives 
						are generated by type and by date.', 'wp-graphql' ),
					],
					'can_export' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether this content type should can be exported.', 'wp-graphql' ),
					],
					'delete_with_user' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether delete this type of content when the author of it is 
						deleted from the system.', 'wp-graphql' ),
					],
					'show_in_rest' => [
						'type' => Types::boolean(),
						'description' => esc_html__( 'Whether to add the post type route in the REST API `wp/v2` 
						namespace.', 'wp-graphql' ),
					],
					'rest_base' => [
						'type' => Types::string(),
						'description' => esc_html__( 'Name of content type to diplay in REST API `wp/v2` 
						namespace.', 'wp-graphql' ),
					],
					'rest_controller_class' => [
						'type' => Types::string(),
						'description' => esc_html__( 'The REST Controller class assigned to handling this content 
						type.', 'wp-graphql' ),
					],
					'show_in_graphql' => [
						'type' => Types::string(),
						'description' => esc_html__( 'Whether to add the post type to the GraphQL 
						Schema.', 'wp-graphql' ),
					],
					'graphql_single_name' => [
						'type' => Types::string(),
						'description' => esc_html__( 'The singular name of the post type within the GraphQL 
						Schema.', 'wp-graphql' ),
					],
					'graphql_plural_name' => [
						'type' => Types::string(),
						'description' => esc_html__( 'The plural name of the post type within the GraphQL 
						Schema.', 'wp-graphql' ),
					],
					'items' => Connections::post_objects_connection( get_post_type_object( 'post' ) ),
				];

				/**
				 * Pass the fields through a filter
				 *
				 * @param array $fields
				 *
				 * @since 0.0.5
				 */
				$fields = apply_filters( 'graphql_post_type_type_fields', $fields );

				/**
				 * Sort the fields alphabetically by key. This makes reading through docs much easier
				 * @since 0.0.2
				 */
				ksort( $fields );

				return $fields;
			},
			'interfaces' => [
				$node_definition['nodeInterface'],
			],
		];

		parent::__construct( $config );

	}

}