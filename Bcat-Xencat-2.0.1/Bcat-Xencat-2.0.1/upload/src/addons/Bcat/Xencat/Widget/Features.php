<?php

namespace Bcat\Xencat\Widget;

class Features extends \XF\Widget\AbstractWidget
{
	protected $defaultOptions = [
    'style' => '1',
		'speed' => 800,
		'auto' => 4000,
		'controls' => true,
    'autoControls' => false,
		'limit' => 6,
		'trim' => 200,
		'container' => true,
	];

	public function render()
	{
		$options = $this->options;
		$featureRepo = $this->app->repository('Bcat\Xencat:Feature');
		$entries = $featureRepo->findFeature()->limit($options['limit'])
			->where('Thread.discussion_state', 'visible');

		if (!empty($this->contextParams['category']))
		{
			if ($options['category'])
			{
				$entries->with('CatLink')->where('CatLink.category_id', $this->contextParams['category']->category_id);
			}
			else
			{
				return false;
			}
		}
		if (!$features = $entries->fetch())
		{
			return false;
		}

		$viewParams = [
			'features' => $featureRepo->parseFeatures($features, $options['trim']),
		];

		return $this->renderer('widget_BCxencat_features', $viewParams);
	}

	public function verifyOptions(\XF\Http\Request $request, array &$options, &$error = null)
	{
		$options = $request->filter([
      'style' => 'uint',
			'speed' => 'uint',
			'auto' => 'uint',
			'controls' => 'bool',
      'autoControls' => 'bool',
			'limit' => 'uint',
			'trim' => 'uint',
			'container' => 'bool',
		]);

		if ($options['limit'] < 1)
		{
			$options['limit'] = 1;
		}

		return true;
	}
}
