<?php

namespace Bcat\Xencat\Repository;

use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Repository;

class Feature extends Repository
{
	public function findFeature()
	{
		return $this->finder('Bcat\Xencat:Feature')
			->with('Thread', true)
			->with('Thread.FirstPost', true)
			->order('feature_date', 'DESC');
	}

	public function fetchFeatureByThread($thread)
	{
		return $this->finder('Bcat\Xencat:Feature')
			->where('thread_id', $thread->thread_id)
			->fetchOne();
	}

	public function parseFeatures($features, $trim = 0)
	{
		foreach ($features AS &$feature)
		{
			$feature = $this->parseFeature($feature, $trim);
		}

		return $features;
	}

	public function parseFeature($feature, $trim = 0)
	{
		$options = \XF::options();
		$trim = !empty($trim) ? $trim : $options->EWRporta_articles_trim;
		
		if (empty($feature->feature_excerpt))
		{
			$feature->feature_excerpt = $feature->Thread->FirstPost->message;
		}
		
		$feature->feature_excerpt = str_replace(["\r","\n"], ' ', $feature->feature_excerpt);
		
		$formatter = \XF::app()->stringFormatter();
		$feature->feature_excerpt = $formatter->snippetString($feature->feature_excerpt, $trim, ['stripBbCode' => true]);
		
		return $feature;
	}

	public function setFeatureFromUpload($thread, $upload)
	{
		$upload->requireImage();

		if (!$upload->isValid($errors))
		{
			throw new \XF\PrintableException(reset($errors));
		}

		$target = 'data://features/'.$thread->thread_id.'.jpg';
		try
		{
			$image = \XF::app()->imageManager->imageFromFile($upload->getTempFile());

			$tempFile = \XF\Util\File::getTempFile();
			if ($tempFile && $image->save($tempFile))
			{
				$output = $tempFile;
			}
			unset($image);

			\XF\Util\File::copyFileToAbstractedPath($output, $target);
		}
		catch (Exception $e)
		{
			throw new \XF\PrintableException(\XF::phrase('unexpected_error_occurred'));
		}
	}
}
