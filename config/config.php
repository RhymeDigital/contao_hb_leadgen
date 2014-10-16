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
 * Back end modules
 */
array_insert($GLOBALS['BE_MOD'], 1, array
(
    'leadgen' => array
    (
    	'campaigns' => array
    	(
    		'tables'      => array('tl_leadgen_campaign', 'tl_leadgen_campaign_tag'),
    	)
    )
));



/**
 * Add in custom JS
 */
if(TL_MODE=='FE'){
    array_insert($GLOBALS['TL_JAVASCRIPT'], 9999999, array(
        'system/modules/hb_leadgen/assets/js/hbagency_leadgen.js|static'
    ));
}

/**
 * Models
 */
$GLOBALS['TL_MODELS'][\HBAgency\Model\LeadGen\Campaign::getTable()]     = '\HBAgency\Model\LeadGen\Campaign';
$GLOBALS['TL_MODELS'][\HBAgency\Model\LeadGen\CampaignTag::getTable()]  = '\HBAgency\Model\LeadGen\CampaignTag';
$GLOBALS['TL_MODELS'][\HBAgency\Model\LeadGen\CampaignLog::getTable()]  = '\HBAgency\Model\LeadGen\CampaignLog';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['initializeSystem'][] = array('\HBAgency\Hooks\InitializeSystem\ShortUrls','run');


/**
 * Add permissions
 */
$GLOBALS['TL_PERMISSIONS'][] = 'leadgentag';
$GLOBALS['TL_PERMISSIONS'][] = 'leadgentagp';

/**
 * Short URL length
 */
$GLOBALS['TL_SHORTURL_LENGTH'] = 6;

/**
 * Restrict Short URLs to certain base domains only
 */
$GLOBALS['TL_SHORTURL_DOMAINS'] = array();