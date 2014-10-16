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
 * Table tl_leadgen_campaign_tag
 */
$GLOBALS['TL_DCA']['tl_leadgen_campaign_tag'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_leadgen_campaign',
		'switchToEdit'                => true,
		'enableVersioning'            => true,
		'onload_callback' => array
		(
			array('\HBAgency\Backend\LeadGen\CampaignTag\Callbacks', 'checkPermission'),
		),
		'onsubmit_callback' => array
		(
			array('\HBAgency\Backend\LeadGen\CampaignTag\Callbacks', 'addShortUrl'),
		),
		'sql' => array
		(
			'keys' => array
			(
				'id'        => 'primary',
				'pid'       => 'index',
				'shorturl'  => 'index',
			)
		)
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('medium'),
			'flag'                    => 11,
			'panelLayout'             => 'filter;sort,search,limit',
		),
		'label' => array
		(
			'fields'                  => array('medium', 'source', 'content', 'destination', 'shorturl'),
			'showColumns'             => true,
			'label_callback'   => array('\HBAgency\Backend\LeadGen\CampaignTag\Callbacks', 'listCampaignTags'),
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
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['editmeta'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array(),
		'default'                     => '{tag_legend},medium,source,content,destination'
	),

	// Subpalettes
	'subpalettes' => array
	(
	
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
			'foreignKey'              => 'tl_leadgen_campaign.title',
			'sql'                     => "int(10) unsigned NOT NULL default '0'",
			'relation'                => array('type'=>'belongsTo', 'load'=>'eager')
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'shorturl' => array
		(
		    'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['shorturl'],
		    'search'                  => true,
			'sql'                     => "varbinary(6) NOT NULL",
			'eval'                    => array('doNotCopy'=>true)
		),
		'medium' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['medium'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['source'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'content' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['content'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
		'destination' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_leadgen_campaign_tag']['destination'],
			'exclude'                 => true,
			'search'                  => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('\HBAgency\Backend\LeadGen\CampaignTag\Callbacks', 'pagePicker')
			),
			'sql'                     => "varchar(255) NOT NULL default ''"
		),
	)
);

