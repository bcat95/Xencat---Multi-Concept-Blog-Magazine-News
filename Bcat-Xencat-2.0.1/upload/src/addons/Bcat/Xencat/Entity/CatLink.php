<?php

namespace Bcat\Xencat\Entity;

use XF\Mvc\Entity\Structure;

class CatLink extends \XF\Mvc\Entity\Entity
{
	protected function _preSave()
	{
		$exists = $this->finder('Bcat\Xencat:CatLink')
			->where('category_id', $this->category_id)
			->where('thread_id', $this->thread_id)
			->fetchOne();

		if ($exists && $exists != $this)
		{
			$this->error(\XF::phrase('BCxencat_thread_already_exists_for_category'));
		}
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'bc_xencat_catlinks';
		$structure->shortName = 'Bcat\Xencat:CatLink';
		$structure->primaryKey = ['category_id', 'thread_id'];
		$structure->columns = [
			'category_id'	=> ['type' => self::UINT, 'required' => true],
			'thread_id'		=> ['type' => self::UINT, 'required' => true],
		];
		$structure->getters = [];
		$structure->relations = [
			'Article' => [
				'entity' => 'Bcat\Xencat:Article',
				'type' => self::TO_ONE,
				'conditions' => 'thread_id',
				'primary' => true,
			],
			'Category' => [
				'entity' => 'Bcat\Xencat:Category',
				'type' => self::TO_ONE,
				'conditions' => 'category_id',
				'primary' => true,
			],
		];

		return $structure;
	}
}
