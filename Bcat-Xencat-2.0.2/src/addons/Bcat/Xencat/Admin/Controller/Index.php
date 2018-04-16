<?php

namespace Bcat\Xencat\Admin\Controller;

use XF\Mvc\ParameterBag;

class Index extends \XF\Admin\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('BCxencat');
	}

	public function actionIndex(ParameterBag $params)
	{
		return $this->view('Bcat\Xencat:Index', 'BCxencat_index');
	}
}
