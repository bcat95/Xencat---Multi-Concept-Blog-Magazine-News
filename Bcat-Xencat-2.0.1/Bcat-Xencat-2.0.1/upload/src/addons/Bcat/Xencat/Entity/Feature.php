<?php

namespace Bcat\Xencat\Entity;

use XF\Mvc\Entity\Structure;

class Feature extends \XF\Mvc\Entity\Entity
{
	public function canEdit()
	{
		$thread = $this->Thread;
		$visitor = \XF::visitor();

		if ($visitor->hasPermission('BCxencat', 'modFeatures'))
		{
			return true;
		}

		if ($visitor->hasPermission('BCxencat', 'submitFeatures')
			&& $visitor->user_id == $thread->user_id)
		{
			return true;
		}

		return false;
	}

	public function getImage()
	{
		$image = \XF::getRootDirectory() . '/data/features/' . $this->thread_id . '.jpg';

		if (file_exists($image))
		{
			return 'data/features/' . $this->thread_id . '.jpg?' . $this->feature_time;
		}

		return "styles/Bcat/Xencat/_feature.jpg";
	}

	protected function _preSave()
	{
		$this->feature_time = \XF::$time;
	}

	protected function _postDelete()
	{
		$image = \XF::getRootDirectory() . '/data/features/' . $this->thread_id . '.jpg';
		if (file_exists($image)) { unlink($image); }
	}

	public static function getStructure(Structure $structure)
	{
		$structure->table = 'bc_xencat_features';
		$structure->shortName = 'Bcat\Xencat:Feature';
		$structure->primaryKey = 'thread_id';
		$structure->columns = [
			'thread_id' =>				['type' => self::UINT, 'required' => true],
			'feature_date' =>			['type' => self::UINT, 'required' => true],
			'feature_time' =>			['type' => self::UINT, 'required' => true],
			'feature_title' =>			['type' => self::STR, 'required' => false, 'default' => ''],
			'feature_excerpt' =>		['type' => self::STR, 'required' => false, 'default' => ''],
			'feature_imgurl' =>			['type' => self::STR, 'required' => false, 'default' => ''],
			'feature_icon' =>			['type' => self::SERIALIZED_ARRAY, 'required' => false, 'default' => []],
		];
		$structure->getters = [
			'image' => true,
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
			'Node' => [
				'entity' => 'XF:Node',
				'type' => self::TO_ONE,
				'conditions' => 'node_id',
				'primary' => true,
			],
		];

		return $structure;
	}
}
