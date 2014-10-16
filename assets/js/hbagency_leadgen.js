/**
 * HB Tracking
 *
 * Copyright (C) 2014 HB Agency
 *
 * @package    HB.Tracking
 * @link       http://www.hbagency.com
 * @license    LGPL
 */

//Namespace
var HB = HB || {};

//Encapsulate
(function ($) {

HB.Tracking = {
	

    /*
     * Initialization
     */
    init: function( settings ) 
    {
        //Default configuration
        HB.Tracking.config = {
            
        };
 
        // allow overriding the default config
        jQuery.extend( HB.Tracking.config, settings );
        HB.Tracking.setup();
    },
    
    /*
     * Setup any necessary initial event listeners/callbacks
     */
    setup: function() {

    }
};
    
jQuery(document).ready(HB.Tracking.init);

}(jQuery));
    
    