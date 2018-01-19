<?php

namespace Bcat\Xencat\Widget;

class Articles extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
		'limit' => 5,
		'category' => 0,
		'layout' => 'grid-col2',
	];

	protected function getDefaultTemplateParams($context)
	{
		$params = parent::getDefaultTemplateParams($context);
		if ($context == 'options')
		{
			$categoryRepo = $this->app->repository('Bcat\Xencat:Category');
			$params['categories'] = $categoryRepo->findCategory()->fetch();
		}
		return $params;
	}

	public function render()
	{
		$options = $this->options;
		$articleRepo = $this->app->repository('Bcat\Xencat:Article');
		$entries = $articleRepo->findArticle()->limit($options['limit'])
			->where('Thread.discussion_state', 'visible');

		if ($options['category'])
		{
			$entries->with('CatLink')
				->where('CatLink.category_id', $options['category']);
		}
		if (!$articles = $entries->fetch())
		{
			return false;
		}

		$viewParams = $articleRepo->prepareViewParams($articles);

		return $this->renderer('widget_BCxencat_articles', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
			'limit' => 'uint',
			'category' => 'uint',
			'layout' => 'str',
		]);

		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
}
