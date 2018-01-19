<?php

namespace Bcat\Xencat\Admin\Controller;

use XF\Mvc\ParameterBag;

class Feature extends \XF\Admin\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('BCxencat');
	}

	public function actionIndex(ParameterBag $params)
	{
		$page = $this->filterPage();
		$perPage = 100;

		$featureRepo = $this->getFeatureRepo();

		$entries = $featureRepo->findFeature();
		$entries->limitByPage($page, $perPage);

		$filter = $this->filter('_xfFilter', [
			'text' => 'str',
			'prefix' => 'bool'
		]);
		if (strlen($filter['text']))
		{
			$entries->searchTitle($filter['text'], $filter['prefix']);
		}

		$viewParams = [
			'features' => $entries->fetch(),
			'total' => $entries->total(),
			'page' => $page,
			'perPage' => $perPage,
			'filter' => $filter['text'],
		];
		return $this->view('Bcat\Xencat:Feature\List', 'BCxencat_feature_list', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$thread = $this->assertThreadExists($params->thread_id);
		$feature = $this->assertFeatureExists($params->thread_id);

		$viewParams = [
			'thread' => $thread,
			'feature' => $feature,
		];

		return $this->view('Bcat\Xencat:Feature\Edit', 'BCxencat_feature_edit', $viewParams);
	}

	public function actionSave(ParameterBag $params)
	{
		$feature = $this->assertFeatureExists($params->thread_id, 'Thread');

		if ($upload = $this->request->getFile('upload', false, false))
		{
			$this->getFeatureRepo()->setFeatureFromUpload($article, $upload);
		}

		$input = $this->filter('feature', 'array');
		$input['feature_title'] = !empty($input['feature_title']) ? $input['feature_title'] : '';
		$input['feature_excerpt'] = !empty($input['feature_excerpt']) ? $input['feature_excerpt'] : '';

		$date = $this->filter('date', 'datetime');
		$time = $this->filter('time', 'str');
		list ($hour, $min) = explode(':', $time);

		$dateTime = new \DateTime('@'.$date);
		$dateTime->setTimeZone(\XF::language()->getTimeZone());
		$dateTime->setTime($hour, $min);
		$input['feature_date'] = $dateTime->getTimestamp();

		$form = $this->formAction();
		$form->basicEntitySave($feature, $input);
		$form->run();

		return $this->redirect($this->buildLink('bc-xencat/features'));
	}

	public function actionDelete(ParameterBag $params)
	{
		$feature = $this->assertFeatureExists($params->thread_id, 'Thread');

		if (!$feature->preDelete())
		{
			return $this->error($feature->getErrors());
		}

		if ($this->isPost())
		{
			$feature->delete();
			return $this->redirect($this->buildLink('bc-xencat/features'));
		}
		else
		{
			$viewParams = [
				'feature' => $feature
			];
			return $this->view('Bcat\Xencat:Feature\Delete', 'BCxencat_feature_delete', $viewParams);
		}
	}

	protected function assertFeatureExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Bcat\Xencat:Feature', $id, $with, $phraseKey);
	}

	protected function assertThreadExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('XF:Thread', $id, $with, $phraseKey);
	}

	protected function getFeatureRepo()
	{
		return $this->repository('Bcat\Xencat:Feature');
	}
}
