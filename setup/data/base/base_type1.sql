DELETE FROM !PREFIX!_type WHERE idtype < 10000;
INSERT INTO !PREFIX!_type VALUES (1, 'CMS_HTMLHEAD', '/**\r\n * CMS_HTMLHEAD\r\n */ \r\n$tmp = $a_content[''CMS_HTMLHEAD''][$val];\r\n$tmp = urldecode($tmp); \r\n\r\n$tmp = AddSlashes(AddSlashes($tmp)); \r\n$tmp = str_replace("\\\\\\''","''",$tmp); \r\n$tmp = str_replace("\\$",''\\\\\\$'',$tmp); \r\n\r\ncInclude("includes", "functions.lang.php"); \r\n\r\nif ($edit) {\r\n    if ($tmp == "") { \r\n        $tmp = "&nbsp;"; \r\n    } \r\n    $insiteEditingDIV = new cHTMLDiv; \r\n    $insiteEditingDIV->setId("HTMLHEAD_".$db->f("idtype")."_".$val);\r\n    $insiteEditingDIV->setEvent("Focus", "this.style.border=''1px solid #bb5577'';"); \r\n    $insiteEditingDIV->setEvent("Blur", "this.style.border=''1px dashed #bfbfbf'';"); \r\n    $insiteEditingDIV->setStyleDefinition("border", "1px dashed #bfbfbf"); \r\n    $insiteEditingDIV->setStyleDefinition("direction", langGetTextDirection($lang)); \r\n    \r\n    $insiteEditingDIV->updateAttributes(array("contentEditable" => "true")); \r\n    \r\n    $insiteEditingDIV->setContent("_REPLACEMENT_"); \r\n    \r\n\r\n    /* Edit anchor and image */ \r\n    $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HTMLHEAD&typenr=$val");\r\n    $editAnchor = new cHTMLLink; \r\n   $editAnchor->setClass(''CMS_HTMLHEAD_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n    $editAnchor->setLink("javascript:setcontent(''$idartlang'',''" . $editLink . "'');"); \r\n    \r\n    $editButton = new cHTMLImage; \r\n    $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithead.gif");\r\n    $editButton->setBorder(0); \r\n    $editButton->setStyleDefinition("margin-right", "2px"); \r\n        \r\n    $editAnchor->setContent($editButton); \r\n    \r\n    \r\n    /* Save anchor and image */ \r\n    $saveAnchor = new cHTMLLink; \r\n    $saveAnchor->setClass(''CMS_HTMLHEAD_''.$val.''_SAVE CMS_LINK_SAVE'');\r\n    $saveAnchor->setLink("javascript:setcontent(''$idartlang'',''0'')"); \r\n    \r\n    $saveButton = new cHTMLImage; \r\n    $saveButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_ok.gif"); \r\n    $saveButton->setBorder(0); \r\n    \r\n    $saveAnchor->setContent($saveButton); \r\n\r\n    /* Process for output with echo */ \r\n    $finalEditButton = $editAnchor->render(); \r\n    $finalEditButton = AddSlashes(AddSlashes($finalEditButton)); \r\n    $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton); \r\n    \r\n    $finalEditingDiv = $insiteEditingDIV->render(); \r\n    $finalEditingDiv = AddSlashes(AddSlashes($finalEditingDiv)); \r\n    $finalEditingDiv = str_replace("\\\\\\''","''",$finalEditingDiv); \r\n    \r\n    $finalEditingDiv = str_replace("_REPLACEMENT_", $tmp, $finalEditingDiv); \r\n    \r\n    $finalSaveButton = $saveAnchor->render(); \r\n    $finalSaveButton = AddSlashes(AddSlashes($finalSaveButton)); \r\n    $finalSaveButton = str_replace("\\\\\\''","''",$finalSaveButton); \r\n    \r\n    $tmp =  $finalEditingDiv . $finalEditButton . $finalSaveButton;\r\n}', 'Headline / HTML', 0, '', '0000-00-00 00:00:00', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (2, 'CMS_HTML', '/** \r\n * CMS_HTML \r\n */ \r\n$tmp = $a_content[''CMS_HTML''][$val]; \r\n$tmp = urldecode($tmp); \r\n\r\n$tmp = AddSlashes(AddSlashes($tmp)); \r\n$tmp = str_replace("\\\\\\''","''",$tmp); \r\n$tmp = str_replace("\\$",''\\\\\\$'',$tmp); \r\n\r\ncInclude("includes", "functions.lang.php"); \r\n\r\nif ($edit) { \r\n    if ($tmp == "") { \r\n        $tmp = "&nbsp;"; \r\n    } \r\n    $insiteEditingDIV = new cHTMLDiv; \r\n    $insiteEditingDIV->setId("HTML_".$db->f("idtype")."_".$val); \r\n    $insiteEditingDIV->setEvent("Focus", "this.style.border=''1px solid #bb5577'';"); \r\n    $insiteEditingDIV->setEvent("Blur", "this.style.border=''1px dashed #bfbfbf'';"); \r\n    $insiteEditingDIV->setStyleDefinition("border", "1px dashed #bfbfbf"); \r\n    $insiteEditingDIV->setStyleDefinition("direction", langGetTextDirection($lang)); \r\n    \r\n    $insiteEditingDIV->updateAttributes(array("contentEditable" => "true")); \r\n    \r\n    $insiteEditingDIV->setContent("_REPLACEMENT_"); \r\n    \r\n\r\n    /* Edit anchor and image */ \r\n    $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HTML&typenr=$val"); \r\n    $editAnchor = new cHTMLLink; \r\n	$editAnchor->setClass(''CMS_HTML_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n    $editAnchor->setLink("javascript:setcontent(''$idartlang'',''" . $editLink . "'');"); \r\n    \r\n    $editButton = new cHTMLImage; \r\n    $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithtml.gif"); \r\n    $editButton->setBorder(0); \r\n    $editButton->setStyleDefinition("margin-right", "2px"); \r\n        \r\n    $editAnchor->setContent($editButton); \r\n    \r\n    \r\n    /* Save anchor and image */ \r\n    $saveAnchor = new cHTMLLink; \r\n	$saveAnchor->setClass(''CMS_HTML_''.$val.''_SAVE CMS_LINK_SAVE'');\r\n    $saveAnchor->setLink("javascript:setcontent(''$idartlang'',''0'')"); \r\n    \r\n    $saveButton = new cHTMLImage; \r\n    $saveButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_ok.gif"); \r\n    $saveButton->setBorder(0); \r\n    \r\n    $saveAnchor->setContent($saveButton); \r\n\r\n    /* Process for output with echo */ \r\n    $finalEditButton = $editAnchor->render(); \r\n    $finalEditButton = AddSlashes(AddSlashes($finalEditButton)); \r\n    $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton); \r\n    \r\n    $finalEditingDiv = $insiteEditingDIV->render(); \r\n    $finalEditingDiv = AddSlashes(AddSlashes($finalEditingDiv)); \r\n    $finalEditingDiv = str_replace("\\\\\\''","''",$finalEditingDiv); \r\n    \r\n    $finalEditingDiv = str_replace("_REPLACEMENT_", $tmp, $finalEditingDiv); \r\n    \r\n    $finalSaveButton = $saveAnchor->render(); \r\n    $finalSaveButton = AddSlashes(AddSlashes($finalSaveButton)); \r\n    $finalSaveButton = str_replace("\\\\\\''","''",$finalSaveButton); \r\n    \r\n    $tmp =  $finalEditingDiv . $finalEditButton . $finalSaveButton;\r\n}', 'Text / HTML', 0, '', '2002-05-13 19:04:13', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (3, 'CMS_TEXT', '/**\r\n * CMS_TEXT\r\n */\r\ncInclude("includes", "functions.lang.php");\r\n\r\n$tmp = $a_content["CMS_TEXT"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = nl2br($tmp);\r\n$tmp = str_replace("''", "\\''", $tmp);\r\n$tmp = str_replace("\\$",''\\\\\\$'',$tmp);\r\n\r\n$tmp = str_replace("<br />","<br>", $tmp);\r\nif ($edit) {\r\n\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_TEXT&typenr=$val&lang=$lang");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_TEXT_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edittext.gif");\r\n   $editButton->setBorder(0);\r\n   $editButton->setStyleDefinition("margin-right", "2px");\r\n       \r\n   $editAnchor->setContent($editButton);\r\n   \r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $tmp.$finalEditButton;\r\n}', 'Text / Standard', 0, '', '2002-05-13 19:04:13', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (4, 'CMS_IMG', '/**\r\n * CMS_IMG\r\n */\r\n$tmp = urldecode($a_content[''CMS_IMG''][$val]);\r\n\r\nif ($tmp == '''' || $tmp == ''0'') {\r\n    $tmp = '''';\r\n} else {\r\n    if (is_numeric($tmp)) {\r\n        $oUplItem = new UploadItem((int) $tmp);\r\n        if (false !== $oUplItem->get(''dirname'')) {\r\n            if (is_dbfs($oUplItem->get(''dirname''))) {\r\n                $tmp = $cfgClient[$client][''path''][''htmlpath''] . ''dbfs.php?file='' . urlencode($oUplItem->get(''dirname'') . $oUplItem->get(''filename''));\r\n            } else {\r\n                $tmp = $cfgClient[$client][''path''][''htmlpath''] . $cfgClient[$client][''upload''] . $oUplItem->get(''dirname'') . $oUplItem->get(''filename'');\r\n            }\r\n        }\r\n    }\r\n\r\n    $tmp = htmlspecialchars($tmp);\r\n    $tmp = urldecode($tmp);\r\n    $tmp = str_replace("''", "\\''", $tmp);\r\n}', 'Image', 0, '', '2002-05-13 19:04:21', '2002-05-13 19:04:21');
INSERT INTO !PREFIX!_type VALUES (5, 'CMS_IMGDESCR', '/**\r\n * CMS_IMGDESCR\r\n */\r\n$tmp = $a_content["CMS_IMGDESCR"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlentities($tmp,ENT_QUOTES);\r\n\r\n\r\nif ($edit) {\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_IMG&typenr=$val&lang=$lang");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_IMGDESCR_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editimage.gif");\r\n   $editButton->setBorder(0);\r\n       \r\n   $editAnchor->setContent($editButton);\r\n\r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $tmp.$finalEditButton;\r\n}', 'Description', 0, '', '2002-05-13 19:04:28', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (6, 'CMS_LINK', '/**\r\n* CMS_LINK\r\n*/\r\n\r\n$tmp = urldecode($a_content["CMS_LINK"][$val]);\r\n\r\n/* internal link */\r\nif ( is_numeric($tmp) ) {\r\n   $tmp = "front_content.php?idcatart=". $tmp."&client=".$client."&lang=".$lang;\r\n   if ($edit) $tmp = $sess->url("$tmp");\r\n\r\n} else {\r\n\r\n   if (!preg_match(''/^(http|https|ftp|telnet|gopher):\\/\\/((?:[a-zA-Z0-9_-]+\\.?)+):?(\\d*)/'', $tmp)) {\r\n      // it''s a relative link, or an absolute link with unsupported protocol\r\n      if (substr($tmp,0,4) == "www." || $tmp == "") { // only check if it could be a domainname\r\n         $tmp = "http://".$tmp;\r\n      }\r\n   }\r\n\r\n}', 'Link', 0, '', '2002-05-13 19:04:36', '2002-05-13 19:04:36');
INSERT INTO !PREFIX!_type VALUES (7, 'CMS_LINKTARGET', '/**\r\n * CMS_LINKTARGET\r\n */\r\n$tmp = $a_content["CMS_LINKTARGET"][$val];\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("''", "\\''", $tmp);\r\n$tmp = urldecode($tmp);', 'Frame', 0, '', '2002-05-13 19:04:43', '2002-05-13 19:04:43');
INSERT INTO !PREFIX!_type VALUES (8, 'CMS_LINKDESCR', '/**\r\n * CMS_LINKDESCR\r\n */\r\n$tmp = $a_content["CMS_LINKDESCR"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("''", "\\''", $tmp);\r\nif ($edit) {\r\n\r\n\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_LINK&typenr=$val");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_LINKDESCR_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editlink.gif");\r\n   $editButton->setBorder(0);\r\n       \r\n   $editAnchor->setContent($editButton);\r\n\r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $tmp.$finalEditButton;\r\n}', 'Description', 0, '', '2002-05-13 19:05:00', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (9, 'CMS_HEAD', '/**\r\n * CMS_HEAD\r\n */\r\n$tmp = $a_content["CMS_HEAD"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = str_replace("''", "\\''", $tmp);\r\n$tmp = str_replace("\\$",''\\\\\\$'',$tmp); \r\n\r\nif ($edit) {\r\n\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HEAD&typenr=$val&lang=$lang");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_HEAD_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edithead.gif");\r\n   $editButton->setBorder(0);\r\n   $editButton->setStyleDefinition("margin-right", "2px");\r\n       \r\n   $editAnchor->setContent($editButton);\r\n   \r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $tmp.$finalEditButton;\r\n}', 'Headline / Standard', 0, '', '2002-05-13 19:02:34', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (10, 'CMS_SWF', '/**\r\n * CMS_SWF\r\n */\r\n\r\nif ( !is_object($db2) ) $db2 = new DB_Contenido;\r\n\r\n$tmp_id = $a_content[''CMS_SWF''][$val];\r\n\r\n$sql = "SELECT * FROM ".$cfg["tab"]["upl"]." WHERE idclient=''".$client."'' AND idupl=''".$tmp_id."'' AND filetype = ''swf''";\r\n\r\n$db2->query($sql);\r\n\r\nif ( $db2->next_record() ) {\r\n\r\n	$tmp_swf = $cfgClient[$client]["upload"] . $db2->f("dirname") . $db2->f("filename");\r\n	\r\n	$aImgSize = @getimagesize($tmp_swf);\r\n\r\n	$width  = $aImgSize[0];\r\n	$height = $aImgSize[1];\r\n\r\n	$tmp = ''<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"\r\n				   codebase="http://download.macromedia.com\r\n				   /pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"\r\n				   width="''.$width.''" height="''.$height.''" id="movie" align="">\r\n				   <param name="movie" value="''.$tmp_swf.''">\r\n				   <embed src="''.$tmp_swf.''" quality="high" width="''.$width.''"\r\n					  height="''.$height.''" name="movie" align="" type="application/x-shockwave-flash"\r\n					  plug inspage="http://www.macromedia.com/go/getflashplayer">\r\n				</object>'';\r\n} else {\r\n	$tmp = '''';\r\n}\r\n\r\n\r\nif( $edit ) {\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_SWF&typenr=$val");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_SWF_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editswf.gif");\r\n   $editButton->setBorder(0);\r\n       \r\n   $editAnchor->setContent($editButton);\r\n\r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   \r\n   $tmp = ''<table cellspacing="0" cellpadding="0" border="0"><tr><td>''.$tmp.''</td></tr><tr><td>''.$finalEditButton.''</td></tr></table>'';\r\n}\r\n\r\n$tmp = addslashes( addslashes($tmp) ); \r\n$tmp = str_replace( "\\\\\\''", "''", $tmp ); ', 'Flash Movie', 0, '', '0000-00-00 00:00:00', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (11, 'CMS_LINKTITLE', '/**\r\n * CMS_LINKTITLE\r\n */\r\n$tmp = $a_content["CMS_LINKDESCR"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = addslashes($tmp);\r\n\r\n', 'Title of a Link', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO !PREFIX!_type VALUES (12, 'CMS_LINKEDIT', '/**\r\n * CMS_LINKEDIT\r\n */\r\n$tmp = "";\r\n\r\nif ($edit) {\r\n	\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_LINK&typenr=$val");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_LINKEDIT_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editlink.gif");\r\n   $editButton->setBorder(0);\r\n       \r\n   $editAnchor->setContent($editButton);\r\n\r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $finalEditButton;\r\n	\r\n}', 'Link edit button', 0, '', '0000-00-00 00:00:00', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (13, 'CMS_RAWLINK', '/**\r\n* CMS_RAWLINK\r\n*/\r\nglobal $cfgClient;\r\nglobal $client;\r\n\r\n$tmp = urldecode($a_content["CMS_LINK"][$val]);\r\n\r\n/* internal link */\r\nif ( is_numeric($tmp) ) {\r\n   $tmp = "front_content.php?idcatart=". $tmp."&client=".$client."&lang=".$lang;\r\n   if ($edit) $tmp = $sess->url("$tmp");\r\n\r\n}', 'Raw Link without transformation', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO !PREFIX!_type VALUES (14, 'CMS_IMGEDIT', '/**\r\n * CMS_IMGEDIT\r\n */\r\n$tmp = '''';\r\n\r\nif ($edit) {\r\n	\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_IMG&typenr=$val&lang=$lang");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_IMGEDIT_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editimage.gif");\r\n   $editButton->setBorder(0);\r\n       \r\n   $editAnchor->setContent($editButton);\r\n\r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $tmp.$finalEditButton;\r\n}', 'Edit button for an image', 0, '', '0000-00-00 00:00:00', '2009-04-14 13:58:44');
INSERT INTO !PREFIX!_type VALUES (15, 'CMS_IMGTITLE', '/**\r\n * CMS_IMGTITLE\r\n */\r\n$tmp = $a_content["CMS_IMGDESCR"][$val];\r\n$tmp = urldecode($tmp);\r\n$tmp = htmlspecialchars($tmp);\r\n$tmp = addslashes($tmp);', 'Title of an image', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO !PREFIX!_type VALUES (16, 'CMS_SIMPLELINKEDIT', '/**\r\n * CMS_LINKEDIT\r\n */\r\n$tmp = "";\r\nif ($edit) {\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_SIMPLELINK&typenr=$val");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_SIMPLELINKEDIT_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editlink.gif");\r\n   $editButton->setBorder(0);\r\n       \r\n   $editAnchor->setContent($editButton);\r\n\r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $tmp.$finalEditButton;\r\n}', '', 0, '', '0000-00-00 00:00:00', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (17, 'CMS_HTMLTEXT', '/**\r\n * CMS_HTMLTEXT\r\n */\r\ncInclude("includes", "functions.lang.php");\r\n\r\n$content = $a_content[''CMS_HTMLTEXT''][$val];\r\n$content = urldecode($content);\r\n$content = htmldecode($content);\r\n$content = strip_tags($content);\r\n\r\n$content = str_replace("&nbsp;", " ", $content);\r\n\r\n$content = htmlspecialchars($content);\r\nif ($content == "")\r\n{\r\n  $content = "&nbsp;";\r\n}\r\n\r\n$content = nl2br($content);\r\n\r\nif ($edit) {\r\n\r\n	$div = new cHTMLDiv;\r\n	$div->setID("HTMLTEXT_".$db->f("idtype")."_".$val);\r\n	$div->setEvent("focus", "this.style.border=''1px solid #bb5577''");\r\n	$div->setEvent("blur", "this.style.border=''1px dashed #bfbfbf''");\r\n	$div->setStyleDefinition("border", "1px dashed #bfbfbf");\r\n	$div->updateAttributes(array("contentEditable" => "true"));\r\n	$div->setStyleDefinition("direction", langGetTextDirection($lang));\r\n	\r\n	$editlink = new cHTMLLink;\r\n	$editlink->setClass(''CMS_HTMLTEXT_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n	$editlink->setLink($sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_HTMLTEXT&typenr=$val&lang=$lang"));\r\n	\r\n	$editimg = new cHTMLImage;\r\n	$editimg->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_edittext.gif");\r\n	\r\n	$savelink = new cHTMLLink;\r\n	$savelink->setClass(''CMS_HTMLTEXT_''.$val.''_SAVE  CMS_LINK_SAVE'');\r\n	$savelink->setLink("javascript:setcontent(''$idartlang'',''0'')");\r\n	\r\n	$saveimg = new cHTMLImage;\r\n	$saveimg->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_ok.gif");\r\n	\r\n	$savelink->setContent($saveimg);\r\n	\r\n	$editlink->setContent($editimg);\r\n	\r\n	$div->setContent($content);\r\n\r\n  $tmp = implode("", array($div->render(), $editlink->render(), " ", $savelink->render()));\r\n  $tmp = str_replace(''"'', ''\\"'', $tmp);\r\n} else {\r\n  $tmp = $content;\r\n  $tmp = str_replace(''"'', ''\\"'', $tmp);\r\n}\r\n\r\n\r\n$tmp = addslashes($tmp);\r\n$tmp = str_replace(''$'', ''\\\\\\$'', $tmp);', 'Text / Standard', 0, '', '2002-05-13 19:04:13', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (18, 'CMS_EASYIMGEDIT', '/**\r\n * CMS_EASYIMGEDIT\r\n */\r\n$tmp = "";\r\nif ($edit) {\r\n\r\n   /* Edit anchor and image */\r\n   $editLink = $sess->url("front_content.php?action=10&idcat=$idcat&idart=$idart&idartlang=$idartlang&type=CMS_EASYIMG&typenr=$val&lang=$lang");\r\n   $editAnchor = new cHTMLLink;\r\n   $editAnchor->setClass(''CMS_EASYIMGEDIT_''.$val.''_EDIT CMS_LINK_EDIT'');\r\n   $editAnchor->setLink("javascript:setcontent(''$idartlang'',''".$editLink."'');");\r\n   //Save all content\r\n   \r\n   $editButton = new cHTMLImage;\r\n   $editButton->setSrc($cfg["path"]["contenido_fullhtml"].$cfg["path"]["images"]."but_editimage.gif");\r\n   $editButton->setBorder(0);\r\n       \r\n   $editAnchor->setContent($editButton);\r\n\r\n   /* Process for output with echo */\r\n   $finalEditButton = $editAnchor->render();\r\n   $finalEditButton = AddSlashes(AddSlashes($finalEditButton));\r\n   $finalEditButton = str_replace("\\\\\\''","''",$finalEditButton);\r\n\r\n   $tmp = $tmp.$finalEditButton;\r\n}', '', 0, '', '0000-00-00 00:00:00', '2009-04-14 13:56:58');
INSERT INTO !PREFIX!_type VALUES (19, 'CMS_DATE', '$tmp = $a_content["CMS_DATE"][$val];\r\n\r\n$oCmsDate = new Cms_Date($tmp, $val, $idartlang, $editLink, $cfg, $db, count($a_content["CMS_DATE"]), $belang);\r\n\r\nif($edit){\r\n\r\n$tmp = $oCmsDate->getAllWidgetEdit();\r\n\r\n}else{\r\n\r\n$tmp = $oCmsDate->getAllWidgetView();\r\n\r\n}', 'Date', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
INSERT INTO !PREFIX!_type VALUES (20, 'CMS_TEASER', '$tmp = $a_content["CMS_TEASER"][$val];\r\n\r\n$oCmsTeaser = new Cms_Teaser($tmp, $val, $idartlang, $editLink, $cfg, $db, $belang, $client, $lang, $cfgClient, $sess);\r\n\r\nif($edit){\r\n\r\n$tmp = $oCmsTeaser->getAllWidgetEdit();\r\n\r\n}else{\r\n\r\n$tmp = $oCmsTeaser->getAllWidgetView();\r\n\r\n}', 'Teaser', 0, '', '2009-04-20 13:12:14', '0000-00-00 00:00:00');
INSERT INTO !PREFIX!_type VALUES (21, 'CMS_FILELIST', '$tmp = $a_content["CMS_FILELIST"][$val];\r\n\r\n$oCmsFileList = new Cms_FileList($tmp, $val, $idartlang, $editLink, $cfg, $db, $belang, $client, $lang, $cfgClient, $sess);\r\n\r\nif($edit){\r\n\r\n$tmp = $oCmsFileList->getAllWidgetEdit();\r\n\r\n}else{\r\n\r\n$tmp = $oCmsFileList->getAllWidgetView();\r\n\r\n}', '', 0, '', '0000-00-00 00:00:00', '0000-00-00 00:00:00');
