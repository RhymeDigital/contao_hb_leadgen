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
 
 
namespace HBAgency\Backend\LeadGen\Campaign;

/**
 * Class Callbacks 
 *
 * Runs callbacks for the tl_leadgen DCA
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
	 * Check permissions to edit table tl_leadgen_campaign
	 */
	public function checkPermission()
	{

		if ($this->User->isAdmin)
		{
			return;
		}

		// Set root IDs
		if (!is_array($this->User->leadgentag) || empty($this->User->leadgentag))
		{
			$root = array(0);
		}
		else
		{
			$root = $this->User->leadgentag;
		}

		$GLOBALS['TL_DCA']['tl_leadgen_campaign']['list']['sorting']['root'] = $root;

		// Check permissions to add campaigns
		if (!$this->User->hasAccess('create', 'leadgentagp'))
		{
			$GLOBALS['TL_DCA']['tl_leadgen_campaign']['config']['closed'] = true;
		}

		// Check current action
		switch (\Input::get('act'))
		{
			case 'create':
			case 'select':
				// Allow
				break;

			case 'edit':
				// Dynamically add the record to the user profile
				if (!in_array(\Input::get('id'), $root))
				{
					$arrNew = $this->Session->get('new_records');

					if (is_array($arrNew['tl_leadgen_campaign']) && in_array(\Input::get('id'), $arrNew['tl_leadgen_campaign']))
					{
						// Add permissions on user level
						if ($this->User->inherit == 'custom' || !$this->User->groups[0])
						{
							$objUser = $this->Database->prepare("SELECT leadgentag, leadgentagp FROM tl_user WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->id);

							$arrNewp = deserialize($objUser->leadgentagp);

							if (is_array($arrNewp) && in_array('create', $arrNewp))
							{
								$arrNews = deserialize($objUser->leadgentag);
								$arrNews[] = \Input::get('id');

								$this->Database->prepare("UPDATE tl_user SET leadgentag=? WHERE id=?")
											   ->execute(serialize($arrNews), $this->User->id);
							}
						}

						// Add permissions on group level
						elseif ($this->User->groups[0] > 0)
						{
							$objGroup = $this->Database->prepare("SELECT leadgentag, leadgentagp FROM tl_user_group WHERE id=?")
													   ->limit(1)
													   ->execute($this->User->groups[0]);

							$arrNewp = deserialize($objGroup->leadgentagp);

							if (is_array($arrNewp) && in_array('create', $arrNewp))
							{
								$arrNews = deserialize($objGroup->leadgentag);
								$arrNews[] = \Input::get('id');

								$this->Database->prepare("UPDATE tl_user_group SET leadgentag=? WHERE id=?")
											   ->execute(serialize($arrNews), $this->User->groups[0]);
							}
						}

						// Add new element to the user object
						$root[] = \Input::get('id');
						$this->User->leadgentag = $root;
					}
				}
				// No break;

			case 'copy':
			case 'delete':
			case 'show':
				if (!in_array(\Input::get('id'), $root) || (\Input::get('act') == 'delete' && !$this->User->hasAccess('delete', 'leadgentagp')))
				{
					$this->log('Not enough permissions to '.\Input::get('act').' campaign ID "'.\Input::get('id').'"', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;

			case 'editAll':
			case 'deleteAll':
			case 'overrideAll':
				$session = $this->Session->getData();
				if (\Input::get('act') == 'deleteAll' && !$this->User->hasAccess('delete', 'leadgentagp'))
				{
					$session['CURRENT']['IDS'] = array();
				}
				else
				{
					$session['CURRENT']['IDS'] = array_intersect($session['CURRENT']['IDS'], $root);
				}
				$this->Session->setData($session);
				break;

			default:
				if (strlen(\Input::get('act')))
				{
					$this->log('Not enough permissions to '.\Input::get('act').' campaigns', __METHOD__, TL_ERROR);
					$this->redirect('contao/main.php?act=error');
				}
				break;
		}
	}
    
    /**
	 * Return the edit header button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function editHeader($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->canEditFieldsOf('tl_leadgen_campaign') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the copy archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyCampaign($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('create', 'leadgentagp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}


	/**
	 * Return the delete archive button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function deleteCampaign($row, $href, $label, $title, $icon, $attributes)
	{
		return $this->User->hasAccess('delete', 'leadgentagp') ? '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.\Image::getHtml($icon, $label).'</a> ' : \Image::getHtml(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
	}

} 