<?php
/**
 * Title:新浪博客编辑器PHP版封装类
 * Date:2007年11月9日

原来的编辑器代码类似于这样：
<textarea name="news_body"><??></textarea>

初始化编辑器实例
include_once('sinaEditor.class.php');
$editor=new sinaEditor('news_body'); //字段名称
$editor->Value = '';	// 设置初始内容
$editor->BasePath='.';	//	设置目录
$editor->Height=500;	//	高度
$editor->Width=700;		//	宽度
$editor->AutoSave=false;	//自动保存

调用编辑器（替换原来的Text Area）
<?$editor->Create();?>
 */
namespace Org\SinaEditor;
class SinaEditor{
	var $BasePath;
	var $Width;
	var $Height;
	var $eName;
	var $Value;
	var $AutoSave;
	function sinaEditor($eName){
		$this->eName=$eName;
		$this->BasePath='.';
		$this->AutoSave=false;
		$this->Height=460;
		$this->Width=630;
	}
	function __construct($eName){
		$this->sinaEditor($eName);
	}
	function create(){
		$ReadCookie=$this->AutoSave?1:0;
		print <<<eot
		<textarea name="{$this->eName}" id="{$this->eName}" style="display:none;">{$this->Value}</textarea>
		<iframe src="{$this->BasePath}/Edit/editor.htm?id={$this->eName}&ReadCookie={$ReadCookie}" frameBorder="0" marginHeight="0" marginWidth="0" scrolling="No" width="{$this->Width}" height="{$this->Height}"></iframe>
eot;
	}
}