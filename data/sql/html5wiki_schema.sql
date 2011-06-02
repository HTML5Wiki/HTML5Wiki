SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `ArticleVersion` (
  `mediaVersionId` int(11) NOT NULL,
  `mediaVersionTimestamp` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `content` text NOT NULL,
  PRIMARY KEY (`mediaVersionId`,`mediaVersionTimestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `ArticleVersion` VALUES(1, 1307023153, 'Welcome', 'Welcome to your HTML5Wiki installation\n======================================\nWorking with articles\n---------------------\nYou\\''re currently reading an article which was shipped with HTML5Wiki by default.\nYou can look at an article in different perspectives. The _mode bar_ on the upper right helps you switching between these views. Just give it a try now and explore what each mode provides.\n\n\nEditing & formatting content\n----------------------------\nYou can format your articles content with _Markdown_ when staying in _edit_-mode.\n\nDon\\''t mind if you have never worked with this simple, but quite powerful, formatting markup language before. HTML5Wiki has an integrated editor which will help you formatting your text.\n\nIf you\\''re interested in more indepth information regarding _Markdown_, we suggest you to have a look at the nice guide over at [daringfireball.net](http://daringfireball.net/projects/markdown/basics \\"Basic syntax\\")\n\n\nCreating a new article\n----------------------\nIf you want to create a complete new article, you have two different ways how to achieve this:\n\n- Simply click _New article_ in the tab bar at the top. This will open a fresh form where you\\''ll be able to fill in your articles content. Confirm with _Save_ at the pages bottom and you\\''re there.\n\n- If you like the geeky way, type the url of the article you\\''d like to create in your browsers adressbar (i.e. _http://mywiki.ch/wiki/my-new-cool-article_). This will automaticaly open the same form as above, but with _my-new-cool-article_ already filled in as title\n\n\nSearch articles\n---------------\nYou\\''ll probably create myriads of articles. HTML5Wiki supplies a powerful search function just at the top right corner of every page. Just type in what you\\''re looking for and HTML5Wiki will start searching.\n\nUse the arrows and return key on your keyboard or the mouse to select one specific search result. By pressing _Return_ directly you\\''ll get directed to a distinct page showing you all search results in more detail.\n\n_Hint:_ While editing an article, you have the possibility to _tag_ it with specific keywords. Use this great feature to simplify your searches in future. HTML5Wiki will also look into tags when doing a search.');

CREATE TABLE IF NOT EXISTS `MediaVersion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `timestamp` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `permalink` varchar(255) NOT NULL,
  `state` enum('PUBLISHED','DRAFT','TRASH') NOT NULL DEFAULT 'PUBLISHED',
  `versionComment` varchar(140) DEFAULT NULL,
  `mediaVersionType` enum('ARTICLE','FILE') NOT NULL DEFAULT 'ARTICLE',
  PRIMARY KEY (`id`,`timestamp`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `MediaVersion` VALUES(1, 1307023153, 1, 'willkommen', 'PUBLISHED', '', 'ARTICLE');

CREATE TABLE IF NOT EXISTS `MediaVersionTag` (
  `tagTag` varchar(50) NOT NULL,
  `mediaVersionId` int(11) NOT NULL,
  `mediaVersionTimestamp` int(11) NOT NULL,
  PRIMARY KEY (`tagTag`,`mediaVersionId`,`mediaVersionTimestamp`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `MediaVersionTag` VALUES('help', 1, 1307023153);
INSERT INTO `MediaVersionTag` VALUES('html5wiki', 1, 1307023153);
INSERT INTO `MediaVersionTag` VALUES('introduction', 1, 1307023153);

CREATE TABLE IF NOT EXISTS `Tag` (
  `tag` varchar(50) NOT NULL,
  PRIMARY KEY (`tag`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

INSERT INTO `Tag` VALUES('help');
INSERT INTO `Tag` VALUES('html5wiki');
INSERT INTO `Tag` VALUES('introduction');

CREATE TABLE IF NOT EXISTS `User` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

INSERT INTO `User` VALUES(1, '', 'HTML5Wiki Team');
