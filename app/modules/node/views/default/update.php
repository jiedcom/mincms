<?php
/* @var $this DefaultController */

$this->breadcrumbs=array(
	__('content type'),
);
$this->menu=array(
	array('label'=>__('manage'), 'url'=>array('index')),
	array('label'=>__('create'), 'url'=>array('create')),
);
?>
<?php  $this->widget('BuilderView',array(
	'id'=>(int)$_GET['id'],
	'config'=>'application.modules.node.config.node_content',
));?>