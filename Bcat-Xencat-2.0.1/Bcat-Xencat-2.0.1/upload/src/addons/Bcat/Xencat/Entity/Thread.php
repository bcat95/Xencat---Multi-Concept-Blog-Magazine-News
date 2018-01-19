<?php

namespace Bcat\Xencat\Entity;

class Thread extends XFCP_Thread
{
	protected function _postDelete()
	{
		$this->db()->delete('bc_xencat_articles', 'thread_id = ?', $this->thread_id);
		$this->db()->delete('bc_xencat_catlinks', 'thread_id = ?', $this->thread_id);
		$this->db()->delete('bc_xencat_features', 'thread_id = ?', $this->thread_id);

		$image = \XF::getRootDirectory() . '/data/features/' . $this->thread_id . '.jpg';
		if (file_exists($image)) { unlink($image); }

		return parent::_postDelete();
	}
}
