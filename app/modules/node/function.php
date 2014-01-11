<?php
// +----------------------------------------------------------------------
// | NODE ���ٲ�Ѱ����
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://mincms.com All rights reserved.
// +----------------------------------------------------------------------
// | Licensed (http://mincms.com/licenses)
// +----------------------------------------------------------------------
// | Author: Kang Sun <fleaphp@msn.com>
// +----------------------------------------------------------------------
 
class Node{ 
 
	 
	static function find($table,$id){
		 //�����ֶ�key�Լ���Ӧ��model
		 $cache = cache('node__field_table');
		 $now = $cache[$table];
		 $table = "field_".$table;
		 $row = CDB()->from($table)->where('id=:id',array(':id'=>$id))->queryRow();
		 
		 //û�л������������
		 if(!$cache) { 
		 	return $row;
		 } 
		 foreach($now as $k=>$v){
		 	$name = trim($v->name);
 			$_relation_table = $table."_".$name;
 			//��ʵ�ĵ������������
 			$deep = $v->_relation_table; 
		 	//�Ƕ��ֵ�ģ������ǹ�������������
		 	if($v->relation && $v->mvalue==1){  
					$allR = CDB()->from($_relation_table)->where('nid=:nid',array(':nid'=>$id))->queryAll();
					if($allR){
						foreach($allR as $key=>$al){ 
							$value = $al['value'];
							$values = CDB()->from($deep)->where('id=:id',array(':id'=>$value))->queryRow();
							$all[$name][$value] = $values;
						} 
					}
					foreach($all as $key=>$value){
						$row[$key] = $value;
					}
			}else if($v->relation){
				$value = CDB()->from($deep)->where('id=:id',array(':id'=>$row[$name]))->queryRow();
				$row[$name] = $value;
			}
		 }
		 
		 return $row;
	}
		 
		 
 
	
}
/**
* HOOK �������������ֶεĽṹ
*/
Hook::init('init[].node_content',function(){
	if(!cache('node__field_table')){
		Yii::import("application.modules.node.models.NodeContent");
		Yii::import("application.modules.node.models.NodeField");
		$rows = NodeContent::model()->findAll(); 
		if($rows){
			foreach($rows as $v){
				$data[$v->id] = $v->name;
				$id = $v->id;
				foreach($v->fields as $f){
					$field[$id]['id'] = $f->id;
					$field[$id]['name'] = $f->name;
					$field[$id]['type'] = $f->type;
					$field[$id]['widget'] = $f->widget;
					
					$field_table[$v->name][$f->name] = $f;
					
				}
			} 
			cache('node__content',$data); 
			cache('node__field',$field);
			cache('node__field_table',$field_table);
		}
	} 
});	
