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
 
namespace HBAgency\LeadGen;

use HBAgency\Model\LeadGen\CampaignLog;

/**
 * Class Tracker 
 *
 * Redirect shortURL strings from a unique URL
 * @copyright  2014 HB Agency
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    HBLeadGen
 */
class Tracker extends \Controller
{
    /**
	 * Log the campaign tag, add tracking cookie, and optionally redirect
	 * 
	 * @param 
	 */
	public static function trackCampaignTag($objTag, $blnRedirect=false)
	{
        //Log the record
        $strUa = 'N/A';
		$strIp = '127.0.0.1';

		if (\Environment::get('httpUserAgent'))
		{
			$strUa = \Environment::get('httpUserAgent');
		}
		if (\Environment::get('remoteAddr'))
		{
			$strIp = static::anonymizeIp(\Environment::get('ip'));
		}
		
		//Create tracking model and save log
		$objTracker  = new CampaignLog();
		$objTracker->pid = $objTag->id;
		$objTracker->tstamp = time();
		$objTracker->referrer = \System::getReferer();
		$objTracker->ip = $strIp;
		$objTracker->browser = $strUa;
		$objTracker->save();
		
		// HOOK: allow to load custom tracking
		if (isset($GLOBALS['TL_HOOKS']['trackCampaignTag']) && is_array($GLOBALS['TL_HOOKS']['trackCampaignTag']))
		{
			foreach ($GLOBALS['TL_HOOKS']['trackCampaignTag'] as $callback)
			{
				static::importStatic($callback[0])->$callback[1]($objTag, $objTracker);
			}
		}
        
	    if($blnRedirect)
	    {
            //Check for internal link
            if(preg_match('/\{\{link_url::\d+\}\}/', $objTag->destination))
            {
                //Build the parameters
                $arrParams = array(
                    'utm_campaign' => $objTag->getRelated('pid')->title,
                    'utm_medium'   => $objTag->medium,
                    'utm_source'   => $objTag->source,
                    'utm_content'  => $objTag->content,
                );
            
                //Get page ID
                preg_match('/\d+/', $objTag->destination, $arrIds);
                $objPage = \PageModel::findByIdOrAlias($arrIds[0]);
                if($objPage)
                {
                    $strUrl = \Controller::generateFrontendUrl($objPage->row(), '', $objPage->language, true) . '?' . http_build_query($arrParams);
                }
            }
            //Otherwise we output the external URL base domain
            else
            {
                $strUrl = $objTag->destination;
            }
            
            //Redirect
            \Controller::redirect($strUrl);
        }
    	    
    }
    
}