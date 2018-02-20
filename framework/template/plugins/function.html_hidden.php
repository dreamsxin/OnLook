<?php
/**
 * Smarty-Light {html_hidden} function plugin
 *
 * Type:     function
 * Name:     html_hidden
 * Purpose:  Creates a hidden box
 * Input:
 *         - name = the name of the hidden field
 *         - value = the value of the hidden field
 * Author:   Paul Lockaby <paul@paullockaby.com>
 */
function tpl_function_html_hidden($params, &$tpl) {
	extract($params);

	if (!isset($name) || empty($name)) {
		$tpl->trigger_error("html_input: missing 'name' parameter");
		return;
	}

	$html_result = "";

	$html_result = "<INPUT TYPE=\"HIDDEN\"";
	
	if (isset($value))
		$html_result .= " VALUE=\"".htmlspecialchars($value)."\"";
	else
		$html_result .= " VALUE=\"\"";
	$html_result .= " NAME=\"$name\">";
	
	return $html_result;
}
?>