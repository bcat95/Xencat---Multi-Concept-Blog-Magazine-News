<?php

namespace Bcat\Xencat\Entity;

use XF\Mvc\Entity\Structure;

class Category extends \XF\Mvc\Entity\Entity
{
	protected function _postDelete()
	{
		$this->db()->delete('bc_xencat_catlinks', 'category_id = ?', $this->category_id);
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'bc_xencat_categories';
		$structure->shortName = 'Bcat\Xencat:Category';
		$structure->primaryKey = 'category_id';
		$structure->columns = [
			'style_id' =>				['type' => self::UINT, 'required' => true],
			'category_id' =>			['type' => self::UINT, 'autoIncrement' => true],
			'category_name' =>			['type' => self::STR, 'required' => true],
			'category_description' =>	['type' => self::STR, 'required' => false, 'default' => ''],
		];
		$structure->getters = [];
		$structure->relations = [
			'CatLink' => [
				'entity' => 'Bcat\Xencat:CatLink',
				'type' => self::TO_ONE,
				'conditions' => 'category_id',
				'primary' => true,
			],
		];

		return $structure;
	}
}
