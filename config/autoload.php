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
 * Register namespace
 */
NamespaceClassLoader::add('HBAgency', 'system/modules/hb_leadgen/library');

/**
 * Register classes outside the namespace folder
 */
NamespaceClassLoader::addClassMap(array
(
    
));

/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(	
	//Module
	//'mod_hbajax_articlebutton'			=> 'system/modules/hb_leadgen/templates/module',
	
));
