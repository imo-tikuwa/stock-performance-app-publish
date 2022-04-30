<?php
use App\Utils\AuthUtils;

/**
 * @var \App\View\AppView $this
 */
$functions = _code('LeftSideMenu');
$html = "";
if (!empty($functions) && count($functions) > 0) {
	foreach ($functions as $alias => $function) {
		if (AuthUtils::hasRole($this->getRequest(), ['controller' => $function['controller'], 'action' => ACTION_INDEX])) {
			$html .= "<li class=\"nav-item\">";
			if (!method_exists("App\\Controller\\Admin\\{$function['controller']}Controller", 'index')) {
				$url = $this->Url->build(['controller' => $function['controller'], 'action' => ACTION_EDIT]);
			} else {
				$url = $this->Url->build(['controller' => $function['controller'], 'action' => ACTION_INDEX, '?' => _code("InitialOrders.{$alias}")], ['escape' => false]);
			}
			$html .= $this->Html->link(
				"<i class=\"{$function['icon_class']} fa-fw me-2\"></i><p>{$function['label']}</p>",
				$url,
				['class' => ($this->name == $function['controller']) ? 'nav-link text-white active' : 'nav-link text-white-50', 'escapeTitle' => false]
			);
			$html .= "</li>";
		}
	}
}

// 分析画面
$html .= "<li class=\"nav-item\">";
$html .= $this->Html->link(
	"<i class=\"fas fa-chart-line fa-fw me-2\"></i><p>分析</p>",
	$this->Url->build(['controller' => 'Display', 'action' => ACTION_INDEX]),
	['class' => ($this->name == 'Display') ? 'nav-link text-white active' : 'nav-link text-white-50', 'escapeTitle' => false]
);
$html .= "</li>";
// 管理者のみ権限管理可能
if (AuthUtils::isSuperUser($this->getRequest())) {
	$active_class = ($this->name == 'Account') ? ' text-white active' : ' text-white-50';
	$html .= "<li class=\"nav-item mt-3\"><a href=\"/admin/account\" class=\"nav-link{$active_class}\" ><i class=\"fas fa-user-shield me-2\"></i><span class=\"ms-2\">アカウント/権限管理</span></a></li>";
}
$html .= "<li class=\"nav-item mt-3\"><a href=\"/admin/auth/logout\" class=\"nav-link text-white-50\"><i class=\"fas fa-sign-out-alt me-2\"></i><span class=\"ms-2\">ログアウト</span></a></li>";
echo $html;
