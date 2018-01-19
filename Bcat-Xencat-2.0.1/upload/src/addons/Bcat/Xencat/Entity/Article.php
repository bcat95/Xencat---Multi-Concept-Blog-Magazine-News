<?php

namespace Bcat\Xencat\Entity;

use XF\Mvc\Entity\Structure;

class Article extends \XF\Mvc\Entity\Entity
{
	public function canEdit()
	{
		$thread = $this->Thread;
		$visitor = \XF::visitor();

		if ($visitor->hasPermission('BCxencat', 'modArticles'))
		{
			return true;
		}

		if ($visitor->hasPermission('BCxencat', 'submitArticles')
			&& $visitor->user_id == $thread->user_id)
		{
			return true;
		}

		return false;
	}

	protected function _postDelete()
	{
		$this->db()->delete('bc_xencat_catlinks', 'thread_id = ?', $this->thread_id);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'bc_xencat_articles';
		$structure->shortName = 'Bcat\Xencat:Article';
		$structure->primaryKey = 'thread_id';
		$structure->columns = [
			'thread_id' =>				['type' => self::UINT, 'required' => true],
			'article_date' =>			['type' => self::UINT, 'required' => true],
			'article_break' =>			['type' => self::STR, 'required' => false, 'default' => ''],
			'article_title' =>			['type' => self::STR, 'required' => false, 'default' => ''],
			'article_excerpt' =>		['type' => self::STR, 'required' => false, 'default' => ''],
			'article_format' =>			['type' => self::UINT, 'required' => false, 'default' => 1],
			'article_exclude' =>		['type' => self::UINT, 'required' => false, 'default' => 0],
			'article_sticky' =>			['type' => self::UINT, 'required' => false, 'default' => 0],
			'article_icon' =>			['type' => self::SERIALIZED_ARRAY, 'required' => false, 'default' => []],
		];
		$structure->relations = [
			'CatLink' => [
				'entity' => 'Bcat\Xencat:CatLink',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
			],
			'Thread' => [
				'entity' => 'XF:Thread',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true,
			],
		];

		return $structure;
	}
}
