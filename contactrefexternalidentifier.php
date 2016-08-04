<?php

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */

function contactrefexternalidentifier_civicrm_enable() {

	$cont_ref = "contact_reference_options";
	$cont_auto = "contact_autocomplete_options";
	$ext = "external_identifier";

	if( contactrefexternalidentifier_options_group( $cont_ref, $ext, 'get' ) )
		contactrefexternalidentifier_options_group( $cont_ref, $ext, 'create', true );

	if( contactrefexternalidentifier_options_group( $cont_auto, $ext, 'get' ) )
		contactrefexternalidentifier_options_group( $cont_auto, $ext, 'create', true );

}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function contactrefexternalidentifier_civicrm_disable() {

    $result = civicrm_api3('OptionGroup', 'get', array(
        'sequential' => 1,
        'name' => array('IN' => array("contact_autocomplete_options", "contact_reference_options")),
        'api.OptionValue.get' => array('option_group_id' => "\$value.id", 'name' => "external_identifier"),
    ));

    foreach( $result['values'] as $key=>$option_group){
        $delete_option = civicrm_api3('OptionValue', 'delete', array(
            'sequential' => 1,
            'id' => $option_group['api.OptionValue.get']['id'],
        ));
    }

}


function contactrefexternalidentifier_options_group( $option_group, $option_name, $chain_action, $label = false ){

	if( $label == true )
		$option_label = "External Identifier";

	$options = civicrm_api3('OptionGroup', 'get', array(
		'sequential' => 1,
		'name' => $option_group,
		'api.OptionValue.'.$chain_action => array("option_group_id" => "\$value.id", "label" => $option_label, "name" => $option_name),
	));

	if($chain_action == 'get' && $options['values'][0]['api.OptionValue.get']['count'] === 0)
		return true;

}
