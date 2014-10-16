<?php

/**
 * HB Lead Generation
 *
 * Copyright (C) 2014 HB Agency
 *
 * @package    HBLeadGen
 * @link       http://www.hbagency.com
 * @license    LGPL
 */

/**
 * Table tl_leadgen_campaign_log
 */
$GLOBALS['TL_DCA']['tl_leadgen_campaign_log'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'closed'                      => true,
		'notEditable'                 => true,
		'sql' => array
		(
			'keys' => array
			(
				'id'        => 'primary',
				'pid'       => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('tstamp DESC', 'id DESC'),
			'panelLayout'             => 'filter;sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('tstamp', 'pid'),
			'format'                  => '<span style="color:#b3b3b3;padding-right:3px">[%s]</span> %s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_log']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_log']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),


	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'pid' => array
		(
			'foreignKey'              => 'tl_leadgen_campaign_tag.shorturl',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'referrer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_log']['referrer'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'ip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_log']['ip'],
			'sorting'                 => true,
			'filter'                  => true,
			'search'                  => true,
			'sql'                     => "varchar(64) NOT NULL default ''"
		),
		'browser' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_log']['browser'],
			'sorting'                 => true,
			'search'                  => true,
			'sql'                     => "varchar(255) NOT NULL default ''"
		)
	)
);

