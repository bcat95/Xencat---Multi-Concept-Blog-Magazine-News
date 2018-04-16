<?php

namespace Bcat\Xencat;

use XF\Mvc\Entity\Entity;

class Listener
{
	public static function homePageUrl(&$homePageUrl, \XF\Mvc\Router $router)
	{
		$homePageUrl = $router->buildLink('canonical:bc-xencat');
	}
}
