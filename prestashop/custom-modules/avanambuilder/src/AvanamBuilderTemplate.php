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

class AvanamBuilderTemplate extends ObjectModel
{
    public $id_avanam_builder_template;
    public $id_employee;
    public $title;
    public $type;
    public $content;
	public $page_settings;
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'avanam_builder_template',
        'primary' => 'id_avanam_builder_template',
        'fields' => array(
            'id_employee' 		=> 	array('type' => self::TYPE_INT, 	'validate' => 'isUnsignedId'),
            'title' 			=>  array('type' => self::TYPE_STRING, 	'required' => true),
			'type' 				=>  array('type' => self::TYPE_STRING),
            'content' 			=>  array('type' => self::TYPE_HTML, 	'validate' => 'isJson'),
            'page_settings' 	=>  array('type' => self::TYPE_HTML, 	'validate' => 'isJson'),
            'date_add' 			=> 	array('type' => self::TYPE_DATE,	'validate' => 'isDate'),
        ),
    );
}
