<?php

namespace Bcat\Xencat\Admin\Controller;

use XF\Mvc\ParameterBag;

class Category extends \XF\Admin\Controller\AbstractController
{
	protected function preDispatchController($action, ParameterBag $params)
	{
		$this->assertAdminPermission('BCxencat');
	}

	public function actionIndex(ParameterBag $params)
	{
		$categoryRepo = $this->getCategoryRepo();
		$entries = $categoryRepo->findCategory();

		$viewParams = [
			'categories' => $entries->fetch(),
			'total' => $entries->total(),
		];
		return $this->view('Bcat\Xencat:Category\List', 'BCxencat_category_list', $viewParams);
	}

	public function actionEdit(ParameterBag $params)
	{
		$category = $this->assertCategoryExists($params->category_id);

		$styleRepo = $this->repository('XF:Style');
		$styleTree = $styleRepo->getStyleTree(false);

		$viewParams = [
			'category' => $category,
			'styleTree' => $styleTree,
		];

		return $this->view('Bcat\Xencat:Category\Edit', 'BCxencat_category_edit', $viewParams);
	}

	public function actionAdd()
	{
		$styleRepo = $this->repository('XF:Style');
		$styleTree = $styleRepo->getStyleTree(false);

		$viewParams = [
			'styleTree' => $styleTree,
		];

		return $this->view('Bcat\Xencat:Category\Edit', 'BCxencat_category_edit', $viewParams);
	}

	public function actionSave(ParameterBag $params)
	{
		$this->assertPostOnly();

		if ($params->category_id)
		{
			$category = $this->assertCategoryExists($params->category_id);
		}
		else
		{
			$category = $this->em()->create('Bcat\Xencat:Category');
		}

		$input = $this->filter('category', 'array');
		$input['style_id'] = !empty($input['style_id']) ? $input['style_id'] : 0;

		$form = $this->formAction();
		$form->basicEntitySave($category, $input);
		$form->run();

		return $this->redirect($this->buildLink('bc-xencat/categories'));
	}

	public function actionDelete(ParameterBag $params)
	{
		$category = $this->assertCategoryExists($params->category_id);

		if (!$category->preDelete())
		{
			return $this->error($category->getErrors());
		}

		if ($this->isPost())
		{
			$category->delete();
			return $this->redirect($this->buildLink('bc-xencat/categories'));
		}
		else
		{
			$viewParams = [
				'category' => $category
			];
			return $this->view('Bcat\Xencat:Category\Delete', 'BCxencat_category_delete', $viewParams);
		}
	}

	protected function assertCategoryExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Bcat\Xencat:Category', $id, $with, $phraseKey);
	}

	protected function getCategoryRepo()
	{
		return $this->repository('Bcat\Xencat:Category');
	}
}
