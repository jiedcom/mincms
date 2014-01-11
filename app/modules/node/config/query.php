<?php
// +----------------------------------------------------------------------
// | ���Զ����ɵı�field_{name} ���������ļ� 
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://mincms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed (http://mincms.com/licenses)
// +----------------------------------------------------------------------
// | Author: Kang Sun <fleaphp@msn.com>
// +----------------------------------------------------------------------
$fid = (int)$_GET['fid'];
$model = NodeContent::model()->findByPk($fid);
/**
* ȡ�÷���
*/
class Module_Node_Query{
	static function category(){
		$db = Yii::app()->db->createCommand();
		$db->setFetchMode(PDO::FETCH_OBJ);
		$rows = $db 
				->from('category') 
				->queryAll(); 
		if($rows){ 
			foreach($rows as $v){  
				$tree[$v->id] = $v; 
			}   
			$category = ArrHelper::tree($tree);
		}
		return array($category,$tree);
	}
	
	/**
	* ����
	*/
	static function language(){
		$rows = Yii::app()->db->createCommand() 
				->from('languages') 
				->queryAll();
		$language = array();
		if($rows){
			foreach($rows as $v){
				$language[$v['id']] = $v['name'];
			}
		}
		return $language;
	} 
	static function  name($v){   
		return "<input type='hidden' value='".$v['id']."' name='ids[]'>".$v['title'];
	}
}
$categoryAll = Module_Node_Query::category();
$category = $categoryAll[0];
$category[""] = __('please select'); 
  
$language = Module_Node_Query::language();
$language[""] = __('please select'); 

/**
* �б�����ʾ��Ӧ���������
*/
function node_config_cateogry($v){
	$id = $v['category_id'];
	$categoryAll = Module_Node_Query::category();
	$category = $categoryAll[1];
	$v = $category[$id]; 
	return $v->name;
}
/**
* ����
*/
global $global_field_table;
$global_field_table = "field_".$model->name;
function node_config_language($v){
	global $global_field_table;
	$id = $v['language_id']; 
	$str = LanguageHelper::img($global_field_table,$v['vid'] ,array('node/query/update',array('id'=>'{id}','fid'=>(int)$_GET['fid'])));
	return  $str;
}



$fields = $model->fields;
$rules = array();
$i = 0;
foreach($fields as $v){  
	$name = $v->name;
	$search = false;
	if($v->search==1) $search = true;
	$indexes = false;
	if($v->indexes==1) $indexes = true;
	$arr[$v->name]['label'] = $v->label;
	$arr[$v->name]['type'] = $v->type;
	$arr[$v->name]['index'] = $indexes?:false;
	$arr[$v->name]['search'] = $search?:false;
	//����Node_Field�ľ�����Ϣ
	$arr[$v->name]['model'] = $v;
//	$arr[$v->name]['datas'] = $v->search; 
	//��ǰ��ֵ
	$arr[$v->name]['_value'] = 4;
	//�Ƿ񱣴浽������
	if($v->relation && $v->mvalue==1){
		$arr[$v->name]['_relation_table'] = true;
		$relation_table[$v->name] = "field_".$model->name.'_'.$v->name;
		$arr[$v->name]['insert'] = false;
	}
	$r = $v->_rules;
 
	if($r){ 
		
		foreach($r as $_k=>$_v){
			$rules[$i][] = $v->name;
			if(!is_array($_v) && is_bool($_v))
				$rules[$i][] = $_k;
			else{
				$rules[$i][] = $_k;
				foreach($_v as $key=>$vo){
					$rules[$i][$key] = $vo;
				}
			}
			
		}
	}
	$i++;
} 


$arr['language_id']=array( 
		'type'=>'select',
		'datas'=>$language,
		'index'=>true,	
		'search'=>true,
		'_value'=>"php:node_config_language"
);
		
$arr['_error'] = 1;
$arr['_rules'] = $rules;
//�Ƿ������ݿ������
$arr['_multiLanguage'] = true;
/**
* ���������Ϣ�� ����keyΪ�ֶ�����ֵΪ��������
*/
$arr['_relation_table'] = $relation_table;

 
//ģ��
$arr['_AutoModel'] = array(
		'AdminUid'	,'Time','Vid','Uuid'
);
//dump($arr);
return $arr;