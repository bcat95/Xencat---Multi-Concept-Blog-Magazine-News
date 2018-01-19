<?php

namespace Bcat\Xencat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Attachment extends \XF\Pub\Controller\AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$attachment = $this->em()->find('XF:Attachment', $params->attachment_id);

		if (!$attachment)
		{
			throw $this->exception($this->notFound());
		}

		return $this->plugin('XF:Attachment')->displayAttachment($attachment);
	}
}
