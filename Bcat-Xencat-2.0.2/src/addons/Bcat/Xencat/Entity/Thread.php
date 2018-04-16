<?php

namespace Bcat\Xencat\Entity;

use XF\Mvc\Entity\Structure;

class Thread extends XFCP_Thread
{
	
	protected function _postDelete()
	{
		if ($this->Article)
		{
			$this->db()->delete('bc_xencat_articles', 'thread_id = ?', $this->thread_id);
			$this->db()->delete('bc_xencat_catlinks', 'thread_id = ?', $this->thread_id);
		}
		
		if ($this->Feature)
		{
			$this->db()->delete('bc_xencat_features', 'thread_id = ?', $this->thread_id);
			
			$image = \XF::getRootDirectory() . '/data/features/' . $this->thread_id . '.jpg';
			if (file_exists($image)) { unlink($image); }
		}
		
		return parent::_postDelete();
	}
	
	public static function getStructure(Structure $structure)
	{
		$parent = parent::getStructure($structure);
		
		$structure->relations['Article'] = [
			'entity' => 'Bcat\Xencat:Article',
			'type' => self::TO_ONE,
			'conditions' => 'thread_id',
			'key' => 'article_id',
		];
		$structure->relations['Feature'] = [
			'entity' => 'Bcat\Xencat:Feature',
			'type' => self::TO_ONE,
			'conditions' => 'thread_id',
			'key' => 'feature_id',
		];
		
		return $parent;
	}
}
