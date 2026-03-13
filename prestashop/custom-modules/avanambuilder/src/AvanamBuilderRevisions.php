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

class AvanamBuilderRevisions extends ObjectModel
{
    public $id_avanam_builder_revisions;
    public $id_post;
    public $id_lang;
    public $id_employee;
    public $content;
	public $page_settings;
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'avanam_builder_revisions',
        'primary' => 'id_avanam_builder_revisions',
        'fields' => array(
            'id_post' 			=> 	array('type' => self::TYPE_INT, 	'validate' => 'isUnsignedId'),
            'id_lang' 			=> 	array('type' => self::TYPE_INT, 	'validate' => 'isUnsignedId'),
            'id_employee' 		=> 	array('type' => self::TYPE_INT, 	'validate' => 'isUnsignedId'),
            'content' 			=>  array('type' => self::TYPE_HTML, 	'validate' => 'isJson'),
            'page_settings' 	=>  array('type' => self::TYPE_HTML, 	'validate' => 'isJson'),
            'date_add' 			=> 	array('type' => self::TYPE_DATE,	'validate' => 'isDate'),
        ),
    );
}
