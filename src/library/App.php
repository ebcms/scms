<?php

declare(strict_types=1);

namespace App\Ebcms\Scms;

use DiggPHP\Framework\AppInterface;
use PDO;

class App implements AppInterface
{

    public static function onInstall()
    {
        $sql = self::getInstallSql();
        fwrite(STDOUT, "是否安装演示数据？y [y,n]：");
        switch (trim((string) fgets(STDIN))) {
            case '':
            case 'y':
            case 'yes':
                fwrite(STDOUT, "安装演示数据\n");
                $sql .= PHP_EOL . self::getDemoSql();
                break;

            default:
                fwrite(STDOUT, "不安装演示数据\n");
                break;
        }
        self::execSql($sql);
    }

    public static function onUninstall()
    {
        $sql = '';
        fwrite(STDOUT, "是否删除数据库？y [y,n]：");
        switch (trim((string) fgets(STDIN))) {
            case '':
            case 'y':
            case 'yes':
                fwrite(STDOUT, "删除数据库\n");
                $sql .= PHP_EOL . self::getUninstallSql();
                break;
            default:
                break;
        }
        self::execSql($sql);
    }

    private static function execSql(string $sql)
    {
        $sqls = array_filter(explode(";" . PHP_EOL, $sql));

        $prefix = 'prefix_';
        $cfg_file = getcwd() . '/config/database.php';
        $cfg = (array)include $cfg_file;
        if (isset($cfg['master']['prefix'])) {
            $prefix = $cfg['master']['prefix'];
        }

        $dbh = new PDO("{$cfg['master']['database_type']}:host={$cfg['master']['server']};dbname={$cfg['master']['database_name']}", $cfg['master']['username'], $cfg['master']['password']);

        foreach ($sqls as $sql) {
            $dbh->exec(str_replace('prefix_', $prefix, $sql . ';'));
        }
    }

    private static function getInstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_ebcms_scms_model`;
CREATE TABLE `prefix_ebcms_scms_model` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='模型表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_field`;
CREATE TABLE `prefix_ebcms_scms_field` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
    `is_filter` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否是筛选字段',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '字段',
    `field` varchar(255) NOT NULL DEFAULT '' COMMENT '字段名称',
    `type` varchar(255) NOT NULL DEFAULT '' COMMENT '类型',
    `help` varchar(255) NOT NULL DEFAULT '' COMMENT '提醒',
    `rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    `source_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '数据源id',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='字段表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_source`;
CREATE TABLE `prefix_ebcms_scms_source` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='数据源表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_data`;
CREATE TABLE `prefix_ebcms_scms_data` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `source_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT 'source_id',
    `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
    `value` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '值',
    `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '小图标',
    `rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='数据源数据表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_site`;
CREATE TABLE `prefix_ebcms_scms_site` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `siteurl` varchar(255) NOT NULL DEFAULT '' COMMENT '站点地址，结尾不要"/"',
    `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '小图标',
    `redirect` varchar(255) NOT NULL DEFAULT '' COMMENT '跳转地址',
    `attr` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性',
    `rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1启用 2停用',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='城市表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_area`;
CREATE TABLE `prefix_ebcms_scms_area` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级id',
    `site_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '站点id',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
    `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '小图标',
    `attr` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性',
    `rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1启用 2停用',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='地区表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_column`;
CREATE TABLE `prefix_ebcms_scms_column` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `site_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '站点id',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
    `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '小图标',
    `rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    `attr` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性',
    `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1启用 2停用',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='栏目表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_cate`;
CREATE TABLE `prefix_ebcms_scms_cate` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `column_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目id',
    `model_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '模型id',
    `pid` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '上级分类',
    `title` varchar(255) NOT NULL DEFAULT '' COMMENT '标题',
    `name` varchar(255) NOT NULL DEFAULT '' COMMENT '名称',
    `logo` varchar(255) NOT NULL DEFAULT '' COMMENT '小图标',
    `rank` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '排序',
    `attr` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '属性',
    `state` tinyint(3) unsigned NOT NULL DEFAULT '1' COMMENT '状态 1启用 2停用',
    PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='分类表';
DROP TABLE IF EXISTS `prefix_ebcms_scms_content`;
CREATE TABLE `prefix_ebcms_scms_content` (
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
    `content_id` char(13) NOT NULL COMMENT '内容ID',
    `site_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '站点ID',
    `column_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '栏目ID',
    `cate_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '分类ID',
    `area_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '地区ID',
    `filter0` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段0',
    `filter1` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段1',
    `filter2` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段2',
    `filter3` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段3',
    `filter4` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段4',
    `filter5` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段5',
    `filter6` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段6',
    `filter7` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段7',
    `filter8` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段8',
    `filter9` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段9',
    `filter10` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段10',
    `filter11` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段11',
    `filter12` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段12',
    `filter13` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段13',
    `filter14` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段14',
    `filter15` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段15',
    `filter16` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段16',
    `filter17` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段17',
    `filter18` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段18',
    `filter19` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '过滤字段19',
    `user_id` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
    `create_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '发布时间',
    `update_time` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '刷新时间',
    `title` varchar(80) NOT NULL DEFAULT '' COMMENT '标题',
    `pics` text NOT NULL COMMENT '图片',
    `detail` text NOT NULL COMMENT '信息详情',
    `extra` text COMMENT '其他信息',
    PRIMARY KEY (`id`) USING BTREE,
    KEY `filter` (`site_id`,`column_id`,`cate_id`,`area_id`,`filter0`,`filter1`,`filter2`,`filter3`,`filter4`,`filter5`,`filter6`,`filter7`,`filter8`,`filter9`,`filter10`,`filter11`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 ROW_FORMAT=COMPACT COMMENT='内容表';
str;
    }

    private static function getDemoSql(): string
    {
        return <<<'str'
str;
    }

    private static function getUninstallSql(): string
    {
        return <<<'str'
DROP TABLE IF EXISTS `prefix_ebcms_scms_model`;
DROP TABLE IF EXISTS `prefix_ebcms_scms_field`;
DROP TABLE IF EXISTS `prefix_ebcms_scms_cate`;
DROP TABLE IF EXISTS `prefix_ebcms_scms_data`;
DROP TABLE IF EXISTS `prefix_ebcms_scms_content`;
DROP TABLE IF EXISTS `prefix_ebcms_scms_site`;
DROP TABLE IF EXISTS `prefix_ebcms_scms_area`;
DROP TABLE IF EXISTS `prefix_ebcms_scms_column`;
str;
    }
}
