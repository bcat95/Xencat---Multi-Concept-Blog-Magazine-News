<?php

namespace Bcat\Xencat\Pub\Controller;

use XF\Mvc\ParameterBag;

class Category extends \XF\Pub\Controller\AbstractController
{
	public function actionIndex(ParameterBag $params)
	{
		$category = $this->assertCategoryExists($params->category_id);
		$articleRepo = $this->getArticleRepo();

		$page = max(1, $params->page);
		$perPage = $this->options()->BCxencat_articles_perpage;
		$entries = $articleRepo->findArticle()->limitByPage($page, $perPage)
			->with('CatLink', true)
			->where('CatLink.category_id', $category->category_id)
			->where('Thread.discussion_state', 'visible')
			->where('article_date', '<', \XF::$time);
		$total = $entries->total();
		$maxPage = ceil($total / $perPage);

		$this->assertCanonicalUrl($this->buildLink('bc-xencat/categories', $category, ['page' => $page]));
		$this->assertValidPage($page, $perPage, $total, 'bc-xencat/categories', $category);

		if ($category->style_id)
		{
			$this->setViewOption('style_id', $category->style_id);
		}

		$viewParams = $articleRepo->prepareViewParams($entries->fetch()) + [
			'category' => $category,
			'total' => $total,
			'page' => $page,
			'perPage' => $perPage
		];

			return $this->view('Bcat\Xencat:Articles\List', 'BCxencat_articles_category', $viewParams);
	}

	protected function assertCategoryExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Bcat\Xencat:Category', $id, $with, $phraseKey);
	}

	protected function getArticleRepo()
	{
		return $this->repository('Bcat\Xencat:Article');
	}

	protected function getCategoryRepo()
	{
		return $this->repository('Bcat\Xencat:Category');
	}

	public static function getActivityDetails(array $activities)
	{
		return self::getActivityDetailsForContent(
			$activities,
			\XF::phrase('BCxencat_viewing_article_category'),
			'category_id',
			function(array $ids)
			{
				$categories = \XF::em()->findByIds(
					'Bcat\Xencat:Category',
					$ids
				);

				$router = \XF::app()->router('public');
				$data = [];

				foreach ($categories AS $id => $category)
				{
					$data[$id] = [
						'title' => $category->category_name,
						'url' => $router->buildLink('bc-xencat/categories', $category)
					];
				}

				return $data;
			}
		);
	}
}
