<?php
	/**
	 * Trac tags for elgg
	 * This is an internal mod which will map issue numbers (#xxxx) to a ticket in the trac repo.
	 * @author Marcus Povey
	 */

	function elggtractags_init()
	{
		global $CONFIG;
		
		register_plugin_hook('display', 'view', 'elggtractags_rewrite');
		
		// define views we want to rewrite codes on (means we don't have to process *everything*)
		$CONFIG->wiretractags_views = array(
			'object/thewire',
			'object/blog'
		);
		 
		$CONFIG->trac_baseurl = "https://trac.elgg.org/elgg/";
	}
	
	function elggtractags_rewrite($hook, $entity_type, $returnvalue, $params)
	{
		global $CONFIG;
		
		$view = $params['view'];
		
		if (($view) && (in_array($view, $CONFIG->wiretractags_views)))
		{
			// Search and replace file codes
			$returnvalue =  preg_replace_callback('/(#)([0-9]+)/i', 
		       	create_function(
		            '$matches',
		            '
		       			global $CONFIG; 
		       			
		       			return "<a href=\"{$CONFIG->trac_baseurl}ticket/{$matches[2]}\">{$matches[0]}</a>";
		       		'
		    ), $returnvalue);
			
		    $returnvalue =  preg_replace_callback('/(\[)([0-9]+)(\])/i', 
		       	create_function(
		            '$matches',
		            '
		       			global $CONFIG; 
		       			
		       			return "<a href=\"{$CONFIG->trac_baseurl}changeset/{$matches[2]}\">{$matches[0]}</a>";
		       		'
		    ), $returnvalue);
		    
		    return $returnvalue;
		}
	}
	
	register_elgg_event_handler('init','system','elggtractags_init');
?>