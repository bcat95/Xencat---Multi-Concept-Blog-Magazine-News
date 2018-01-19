<?php

namespace Bcat\Xencat;

use XF\Db\Schema\Alter;
use XF\Db\Schema\Create;

use XF\AddOn\AbstractSetup;
use XF\AddOn\StepRunnerInstallTrait;
use XF\AddOn\StepRunnerUninstallTrait;
use XF\AddOn\StepRunnerUpgradeTrait;

class Setup extends AbstractSetup
{
	use StepRunnerInstallTrait;
	use StepRunnerUpgradeTrait;
	use StepRunnerUninstallTrait;

	public function installStep1()
	{
		$this->schemaManager()->createTable('bc_xencat_articles', function(Create $table)
		{
			$table->checkExists(true);
			$table->addColumn('thread_id', 				'int', 10);
			$table->addColumn('article_date',			'int', 10);
			$table->addColumn('article_break',			'text');
			$table->addColumn('article_title',			'varchar', 255);
			$table->addColumn('article_excerpt',		'text');
			$table->addColumn('article_format',			'int', 1);
			$table->addColumn('article_exclude',		'int', 1);
			$table->addColumn('article_sticky',			'int', 1);
			$table->addColumn('article_icon',			'blob');
			$table->addPrimaryKey('thread_id');
		});

		$this->schemaManager()->createTable('bc_xencat_categories', function(Create $table)
		{
			$table->checkExists(true);
			$table->addColumn('style_id', 'int', 10);
			$table->addColumn('category_id', 'int', 10)->autoIncrement();
			$table->addColumn('category_name', 'varchar', 255);
			$table->addColumn('category_description',	'text');
			$table->addPrimaryKey('category_id');
		});

		$this->schemaManager()->createTable('bc_xencat_catlinks', function(Create $table)
		{
			$table->checkExists(true);
			$table->addColumn('category_id', 'int', 10);
			$table->addColumn('thread_id', 'int', 10);
			$table->addPrimaryKey(['category_id', 'thread_id']);
		});

		$this->schemaManager()->createTable('bc_xencat_features', function(Create $table)
		{
			$table->checkExists(true);
			$table->addColumn('thread_id', 'int', 10);
			$table->addColumn('feature_date', 'int', 10);
			$table->addColumn('feature_time', 'int', 10);
			$table->addColumn('feature_title', 'varchar', 255);
			$table->addColumn('feature_excerpt', 'text');
			$table->addColumn('feature_imgurl', 'varchar', 255);
			$table->addColumn('feature_icon', 'blob');
			$table->addPrimaryKey('thread_id');
		});
	}

	public function installStep2()
	{
		$target = \XF::getRootDirectory().'/data/authors';
		if (!is_dir($target)) { mkdir($target, 0777); }

		$target = \XF::getRootDirectory().'/data/features';
		if (!is_dir($target)) { mkdir($target, 0777); }

	}
	public function installStep3()
	{
		$this->createWidget('xencat_view_members_online', 'members_online', [
			'positions' => ['xencat_view_sidebar' => 10]
		]);

		$this->createWidget('xencat_view_new_posts', 'new_posts', [
			'positions' => ['xencat_view_sidebar' => 20]
		]);

		$this->createWidget('xencat_view_new_profile_posts', 'new_profile_posts', [
			'positions' => ['xencat_view_sidebar' => 30]
		]);

		$this->createWidget('xencat_view_forum_statistics', 'forum_statistics', [
			'positions' => ['xencat_view_sidebar' => 40]
		]);

		$this->createWidget('xencat_view_share_page', 'share_page', [
			'positions' => ['xencat_view_sidebar' => 50]
		]);
	}

	public function uninstallStep1()
	{
		$this->schemaManager()->dropTable('bc_xencat_articles');
		$this->schemaManager()->dropTable('bc_xencat_categories');
		$this->schemaManager()->dropTable('bc_xencat_catlinks');
		$this->schemaManager()->dropTable('bc_xencat_features');
	}

	public function uninstallStep2()
	{

		$target = glob(\XF::getRootDirectory().'/data/authors/*.jpg');
		foreach ($target AS $file) { unlink($file); }

		$target = \XF::getRootDirectory().'/data/authors';
		if (is_dir($target)) { rmdir($target); }

		$target = glob(\XF::getRootDirectory().'/data/features/*.jpg');
		foreach ($target AS $file) { unlink($file); }

		$target = \XF::getRootDirectory().'/data/features';
		if (is_dir($target)) { rmdir($target); }
	}
}
