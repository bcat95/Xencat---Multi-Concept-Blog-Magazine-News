<?php

namespace Bcat\Xencat\Repository;

use XF\Mvc\Entity\AbstractCollection;
use XF\Mvc\Entity\Repository;

class Article extends Repository
{
	public function findArticle()
	{
		return $this->finder('Bcat\Xencat:Article')
			->with('Thread', true)
			->with('Thread.FirstPost', true)
			->with('Thread.Forum', true)
			->with('Thread.User')
			->order('article_sticky', 'DESC')
			->order('article_date', 'DESC');
	}

	public function fetchArticleByThread($thread)
	{
		return $this->finder('Bcat\Xencat:Article')
			->where('thread_id', $thread->thread_id)
			->fetchOne();
	}
	public function getArticleImages($thread)
	{
		$images = [];

		$message = $thread->FirstPost->message;

		if (preg_match_all('#\[IMG](.+?)\[/IMG]#i', $message, $matches))
		{
			foreach ($matches[1] AS $match)
			{
				$url = explode('/', preg_replace('#https?://#i', '', $match));

				$images[] = array(
					'host' => reset($url),
					'file' => end($url),
					'url' => $match,
				);
			}
		}

		return $images;
	}


	public function parseArticles($articles, $trim = 0)
	{
		foreach ($articles AS &$article)
		{
			$article = $this->parseArticle($article, $trim);
		}

		return $articles;
	}


	public function parseArticle($article, $trim = 0)
	{
		$options = \XF::options();
		$trim = !empty($trim) ? $trim : $options->BCxencat_articles_trim;

		if (empty($article->article_excerpt))
		{
			$article->article_excerpt = $article->Thread->FirstPost->message;
		}


    if ($options->BCxencat_articles_stripnl)
  	{
  		$article->article_excerpt = str_replace(["\r","\n"], ' ', $article->article_excerpt);
  	}


	$message = preg_split('#\[pre?break\]#i', $article->article_excerpt);

	$article->article_excerpt = $message[0];

	if (!empty($message[1]))
	{
		$trim = 0;
	}

	if (!empty($article->article_icon['type']))
	{
		switch ($article->article_icon['type'])
		{
			case 'attach':
				$article->article_excerpt = preg_replace('#\[ATTACH.*?]'.$article->article_icon['data'].'\[/ATTACH]#i', '', $article->article_excerpt);
				break;
			case 'image':
				$article->article_excerpt = preg_replace('#\[IMG.*?]'.$article->article_icon['data'].'\[/IMG]#i', '', $article->article_excerpt);
				break;
		}
	}

		$article->article_excerpt = preg_replace('#\n{3,}#', "\n\n", trim($article->article_excerpt));

		$formatter = \XF::app()->stringFormatter();

    $article->article_excerpt = $formatter->snippetString($article->article_excerpt, $trim, [
			'stripBbCode' => $options->BCxencat_articles_stripbb,
		]);


		return $article;
	}

	public function prepareViewParams($articles)
	{
		$entriesC = $this->repository('Bcat\Xencat:CatLink')->findCatLink()
			->where('thread_id', array_keys($articles->toArray()))
			->fetch();

		$catlinks = [];
		foreach ($entriesC AS $catlink)
		{
			$catlinks[$catlink->thread_id][$catlink->category_id] = $catlink;
		}

		$posts = [];
		foreach ($articles AS &$article)
		{
			$posts[] = $article->Thread->first_post_id;
		}

		$images = [];
		foreach ($articles AS &$article)
		{
			$images[$article->thread_id] = $this->getArticleImages($article->Thread);
		}

		$attachments = $this->repository('XF:Attachment')->findAttachmentsForList()
			->where('content_type', 'post')
			->where('content_id', $posts)
			->fetch();

		return [
			'articles' => $this->parseArticles($articles),
			'catlinks' => $catlinks,
			'attachments' => $attachments,
			'images' => $images
		];
	}
	public function renderTagImage($validUrl)
	{
		$formatter = \XF::app()->stringFormatter();

		$linkInfo = $formatter->getLinkClassTarget($validUrl);
		if ($linkInfo['local'])
		{
			$imageUrl = $validUrl;
		}
		else
		{
			$imageUrl = $formatter->getProxiedUrlIfActive('image', $validUrl);
			if (!$imageUrl)
			{
				$imageUrl = $validUrl;
			}
		}
		
		return $imageUrl;
	}
}
