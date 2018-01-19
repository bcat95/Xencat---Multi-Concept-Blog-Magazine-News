<?php

namespace Bcat\Xencat\Pub\Controller;
use XF\Mvc\ParameterBag;
class Thread extends XFCP_Thread
{

	public function actionArticleEdit(ParameterBag $params)
	{

    if (!\XF::visitor()->hasPermission('BCxencat', 'submitArticles'))
		{
			return $this->noPermission();
		}


		$thread = $this->assertViewableThread($params->thread_id);
		$articleRepo = $this->repository('Bcat\Xencat:Article');
		$article = $articleRepo->fetchArticleByThread($thread);


    if ($article && !$article->canEdit())
		{
			return $this->noPermission();
		}


		if ($this->isPost())
		{
			if (!$article)
			{
				$article = $this->em()->create('Bcat\Xencat:Article');
			}

			$input = $this->filter('article', 'array');
			$input['thread_id'] = $thread->thread_id;
			$input['article_format'] = !empty($input['article_format']) ? 1 : 0;
			$input['article_sticky'] = !empty($input['article_sticky']) ? 1 : 0;
			$input['article_exclude'] = !empty($input['article_exclude']) ? 1 : 0;
			$input['article_title'] = !empty($input['article_title']) ? $input['article_title'] : '';
			$input['article_excerpt'] = !empty($input['article_excerpt']) ? $input['article_excerpt'] : '';

			$date = $this->filter('date', 'datetime');
			$time = $this->filter('time', 'str');
			list ($hour, $min) = explode(':', $time);

			$dateTime = new \DateTime('@'.$date);
			$dateTime->setTimeZone(\XF::language()->getTimeZone());
			$dateTime->setTime($hour, $min);
			$input['article_date'] = $dateTime->getTimestamp();

			$form = $this->formAction();
			$form->basicEntitySave($article, $input);
			$form->run();

			$this->repository('Bcat\Xencat:CatLink')->updateCatlinksByThreadId($thread->thread_id, $this->filter('catlinks', 'array'));

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$categoryRepo = $this->repository('Bcat\Xencat:Category');

		if ($article)
		{
			$categories = $categoryRepo->findCategory()
				->with('CatLink', true)
				->where('CatLink.thread_id', $thread->thread_id)
				->fetch();
			$nonCategories = $categoryRepo->findCategory()
				->where('category_id', '<>', array_keys($categories->toArray()))
				->fetch();
		}
		else
		{
			$categories = array();
			$nonCategories = $categoryRepo->findCategory()->fetch();
		}

		$attachData = $this->repository('XF:Attachment')->getEditorData('post', $thread->FirstPost);
		$images = $articleRepo->getArticleImages($thread);

		$viewParams = [
			'thread' => $thread,
			'article' => $article,
			'images' => $images,
			'attachData' => $attachData,
			'categories' => $categories,
			'nonCategories' => $nonCategories,
		];

		return $this->view('Bcat\Xencat:Thread\ArticleEdit', 'BCxencat_article_edit', $viewParams);
	}

	public function actionArticleDelete(ParameterBag $params)
	{
		$article = $this->assertArticleExists($params->thread_id, 'Thread');

		if (!$article->canEdit())
		{
			return $this->noPermission();
		}

		if (!$article->preDelete())
		{
			return $this->error($article->getErrors());
		}

		if ($this->isPost())
		{
			$article->delete();
			return $this->redirect($this->buildLink('threads', $article));
		}
		else
		{
			$viewParams = [
				'article' => $article
			];
			return $this->view('Bcat\Xencat:Article\Delete', 'BCxencat_article_delete', $viewParams);
		}
	}


	public function actionFeatureEdit(ParameterBag $params)
	{
		if (!\XF::visitor()->hasPermission('BCxencat', 'submitFeatures'))
		{
			return $this->noPermission();
		}

		$thread = $this->assertViewableThread($params->thread_id);
		$featureRepo = $this->repository('Bcat\Xencat:Feature');
		$feature = $featureRepo->fetchFeatureByThread($thread);

		if ($feature && !$feature->canEdit())
		{
			return $this->noPermission();
		}

		if ($this->isPost())
		{
			if (!$feature)
			{
				$feature = $this->em()->create('Bcat\Xencat:Feature');
			}

			if ($upload = $this->request->getFile('upload', false, false))
			{
				$featureRepo->setFeatureFromUpload($thread, $upload);
			}

			$input = $this->filter('feature', 'array');
			$input['thread_id'] = $thread->thread_id;
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

			return $this->redirect($this->buildLink('threads', $thread));
		}

		$viewParams = [
			'thread' => $thread,
			'feature' => $feature,
		];

		return $this->view('Bcat\Xencat:Thread\FeatureEdit', 'BCxencat_feature_edit', $viewParams);
	}

	public function actionFeatureDelete(ParameterBag $params)
	{
		$feature = $this->assertFeatureExists($params->thread_id, 'Thread');

		if (!$feature->canEdit())
		{
			return $this->noPermission();
		}

		if (!$feature->preDelete())
		{
			return $this->error($feature->getErrors());
		}

		if ($this->isPost())
		{
			$feature->delete();
			return $this->redirect($this->buildLink('threads', $feature));
		}
		else
		{
			$viewParams = [
				'feature' => $feature
			];
			return $this->view('Bcat\Xencat:Feature\Delete', 'BCxencat_feature_delete', $viewParams);
		}
	}

	protected function assertArticleExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Bcat\Xencat:Article', $id, $with, $phraseKey);
	}

	protected function assertFeatureExists($id, $with = null, $phraseKey = null)
	{
		return $this->assertRecordExists('Bcat\Xencat:Feature', $id, $with, $phraseKey);
	}

}
