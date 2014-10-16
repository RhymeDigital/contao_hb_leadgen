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
 
namespace HBAgency\Backend\LeadGen\CampaignTag;

use HBAgency\LeadGen\ShortUrls;


/**
 * Class Callbacks 
 *
 * Runs callbacks for the tl_leadgen_campaign_tag DCA
 * @copyright  2014 HB Agency
 * @author     Blair Winans <bwinans@hbagency.com>
 * @package    HBLeadGen
 */
class Callbacks extends \Backend
{
   	/**
	 * Import the back end user object
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('BackendUser', 'User');
	}

	/**
	 * Check permissions to edit table tl_leadgen_campaign_tag
	 */
	public function checkPermission()
	{
		if ($this->User->isAdmin)
		{
			return;
		}

		// Set the root IDs
		if (!is_array($this->User->leadgentag) || empty($this->User->leadgentag))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->leadgentag;
		}

		$id = strlen(\Input::get('id')) ? \Input::get('id') : CURRENT_ID;

		// Check current action
		switch (Input::get('act'))
		{
			case 'paste':
				// Allow
				break;

			case 'create':
				if (!strlen(\Input::get('pid')) || !in_array(\Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to create tag in campaign ID "'.Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'cut':
			case 'copy':
				if (!in_array(\Input::get('pid'), $root))
				{
					$this->log('Not enough permissions to '.\Input::get('act').' tag ID "'.$id.'" to campaign ID "'.\Input::get('pid').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				// NO BREAK STATEMENT HERE

			case 'edit':
			case 'show':
			case 'delete':
			case 'toggle':
			case 'feature':
				$objArchive = $this->Database->prepare("SELECT pid FROM tl_leadgen_campaign_tag WHERE id=?")
											 ->limit(1)
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid leadgen item ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				if (!in_array($objArchive->pid, $root))
				{
					$this->log('Not enough permissions to '.\Input::get('act').' tag ID "'.$id.'" of campaign ID "'.$objArchive->pid.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'select':
			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
			case 'cutAll':
			case 'copyAll':
				if (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access tag ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$objArchive = $this->Database->prepare("SELECT id FROM tl_leadgen_campaign_tag WHERE pid=?")
											 ->execute($id);

				if ($objArchive->numRows < 1)
				{
					$this->log('Invalid campaign ID "'.$id.'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}

				$session = $this->Session->getData();
				$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $objArchive->fetchEach('id'));
				$this->Session->setData($session);
				break;

			default:
				if (strlen(\Input::get('act')))
				{
					$this->log('Invalid command "'.Input::get('act').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				elseif (!in_array($id, $root))
				{
					$this->log('Not enough permissions to access campaign ID ' . $id, __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}
	
	/**
	 * Store the shortUrl when the tag is created
	 * @param object
	 */
	public function addShortUrl($dc)
	{
	    // Front end call
		if (!$dc instanceof \DataContainer)
		{
			return;
		}
		
		// Return if there is no active record (override all)
		if (!$dc->activeRecord || !empty($dc->activeRecord->shorturl))
		{
			return;
		}
		
		//Create URL with length from config
		$strUrl = ShortUrls::createFromId($dc->id, $GLOBALS['TL_SHORTURL_LENGTH']);
		
		$this->Database->prepare("UPDATE tl_leadgen_campaign_tag SET shorturl=? WHERE id=?")
					   ->execute($strUrl, $dc->id);
	
	}

    /**
	 * Add destination link to each record
	 * @param array
	 * @param string
	 * @param \DataContainer
	 * @param array
	 * @return array
	 */
	public function listCampaignTags($row, $label, \DataContainer $dc, $args)
	{
	    //Add in Destination URL at end of args array
	    $strRawDest = $row['destination'];
	    $args[3] = $strRawDest;
	    
	    //Now we want to add in a meaningful link/visual
	    //Check for internal link
	    if(preg_match('/\{\{link_url::\d+\}\}/', $strRawDest))
	    {
	        //Get page ID
	        preg_match('/\d+/', $strRawDest, $arrIds);
	        $objPage = \PageModel::findByIdOrAlias($arrIds[0]);
    	    if($objPage)
    	    {
        	    $strLink = '<a href="contao/main.php?do=feRedirect&amp;page=' . $objPage->id . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['view']) . '" target="_blank">';
        	    $strLink .= $objPage->alias .  \Config::get('urlSuffix');
        	    $strLink .= '</a>';
        	    $args[3] = $strLink;
    	    }
	    }
	    //Otherwise we output the external URL base domain
	    else
	    {
	        $arrURL = parse_url($strRawDest);
    	    $strLink = '<a href="' . $strRawDest . '" title="' . specialchars($GLOBALS['TL_LANG']['MSC']['view']) . '" target="_blank">';
            $strLink .= $arrURL['scheme'] . "://" . $arrURL['host'];
        	$strLink .= '</a>';
            $args[3] = $strLink;
	    }
        
        return $args;
	}
	
	/**
	 * Return the link picker wizard
	 * @param \DataContainer
	 * @return string
	 */
	public function pagePicker(\DataContainer $dc)
	{
		return ' <a href="contao/page.php?do='.\Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.str_replace(array('{{link_url::', '}}'), '', $dc->value).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':768,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((\Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . \Image::getHtml('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
	}

} 