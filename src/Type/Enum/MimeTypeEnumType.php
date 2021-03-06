<?php
namespace WPGraphQL\Type\Enum;

use WPGraphQL\Type\WPEnumType;

/**
 * Class MimeTypeEnumType
 *
 * This defines an EnumType with allowed mime types that are registered to WordPress.
 *
 * @package WPGraphQL\Type\Enum
 * @since 0.0.5
 */
class MimeTypeEnumType extends WPEnumType {

	/**
	 * This holds the enum values array
	 * @var array $values
	 */
	private static $values;

	/**
	 * MimeTypeEnumType constructor.
	 * @since 0.0.5
	 */
	public function __construct() {
		$description = __( 'The MimeType of the object', 'wp-graphql' );
		parent::__construct( 'mimeType', self::values(), $description );
	}

	/**
	 * values
	 * Returns the values to be used in the Enum
	 * @return array|null
	 */
	private static function values() {

		if ( null === self::$values ) {

			/**
			 * Establish a default MimeType value to ensure we don't
			 * return null values
			 * @since 0.0.5
			 */
			self::$values = [
				'name' => 'IMAGE_JPEG',
				'value' => 'image/jpeg',
			];

			$allowed_mime_types = get_allowed_mime_types();

			if ( ! empty( $allowed_mime_types ) ) {
				self::$values = [];
				foreach ( $allowed_mime_types as $mime_type ) {
					self::$values[ $mime_type ] = [
						'name' => strtoupper( preg_replace( '/[^A-Za-z0-9]/i', '_', $mime_type ) ),
						'value' => $mime_type,
					];
				}
			}
		}

		return self::$values;

	}

}
