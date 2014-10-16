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
 
namespace HBAgency\Hooks\InitializeSystem;

use HBAgency\Model\LeadGen\CampaignTag;
use HBAgency\LeadGen\Tracker;


/**
 * Class ShortUrls 
 *
 * Redirect shortURL strings from a unique URL
 * @copyright  2014 HB Agency
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    HBLeadGen
 */
class ShortUrls extends \Controller
{
    /**
	 * Redirect a short URL and log its use, referrer, etc
	 */
	public function run()
	{
	    //Check for restricted domains
	    if(!empty($GLOBALS['TL_SHORTURL_DOMAINS']) && !in_array(\Environment::get('host'), $GLOBALS['TL_SHORTURL_DOMAINS']))
	    {
    	    return;
	    }
	
	    //Analyze the URL for evidence of a short URL
	    $strRequest = \Environment::get('request');
	    $arrParts = explode('/', $strRequest);
	    
	    //Skip anything with the config URL suffix right away
	    if((!empty($GLOBALS['TL_CONFIG']['urlSuffix']) && stripos($GLOBALS['TL_CONFIG']['urlSuffix'], $strRequest) !== false) || count($arrParts) > 1)
	    {
    	    return;
	    }
	    
	    //Lookup, Log and Redirect if we find a code
	    if(($objTag = CampaignTag::findOneByShorturl($strRequest)) !== null)
	    {
            Tracker::trackCampaignTag($objTag, true);
	    }
	    
	}

}