<?php
/**
 * AvanamBuilder - Website Builder
 *
 * NOTICE OF LICENSE
 *
 * @author    avanam.org
 * @copyright avanam.org
 * @license   You can not resell or redistribute this software.
 *
 * https://www.gnu.org/licenses/gpl-3.0.html
 */


class AvanamBuilderRelated extends ObjectModel
{
    public $id_avanam_builder_related;
    public $id_post;
    public $post_type;
    public $key_related;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'avanam_builder_related',
        'primary' => 'id_avanam_builder_related',
        'fields' => array(
            'id_post' 		=>	array('type' => self::TYPE_INT, 	'validate' => 'isUnsignedId'),
			'post_type' 	=>  array('type' => self::TYPE_STRING, 	'required' => true),
            'key_related' 	=>  array('type' => self::TYPE_STRING, 	'required' => true),
        ),
    );
	
    public function __construct( $id = null, $id_lang = null, $id_shop = null )
    {		
        parent::__construct( $id, $id_lang, $id_shop );
		
		Shop::addTableAssociation( 'avanam_builder_related', array('type' => 'shop') );
    }		
}
