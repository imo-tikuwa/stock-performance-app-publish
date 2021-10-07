<?php
/**
 * @var \App\View\AppView $this
 */
$footer_config = _code('FooterConfig');
if (!empty($footer_config)) {
	$html = "";
	if (isset($footer_config['buttons']) && count($footer_config['buttons']) > 0) {
		$html .= "<div class=\"btn-group mr-2\" role=\"group\">";
		foreach ($footer_config['buttons'] as $button) {

			if (empty($button['button_text']) || empty($button['button_link'])) {
				continue;
			}

			$html .= "<a class=\"btn btn-sm btn-flat btn-outline-secondary\" href=\"{$button['button_link']}\" target=\"_blank\" role=\"button\">{$button['button_text']}";
			if (!empty($button['button_icon'])) {
				$html .= "<i class=\"{$button['button_icon']} ml-2\"></i>";
			}
			$html .= "</a>";
		}
		$html .= "</div>";
		$html .= "<br class=\"d-md-none\" />";
	}
	if (isset($footer_config['copylight']) && count($footer_config['copylight']) === 3) {
		$html .= "<strong>© {$footer_config['copylight']['from']} Copyright: <a href=\"{$footer_config['copylight']['link']}\" target=\"_blank\"> {$footer_config['copylight']['text']} </a></strong>";
	}
	echo $html;
}