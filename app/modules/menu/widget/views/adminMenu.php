 <?php $menus = menu_function::admin();
        foreach($menus as $v){    
        	if($v['name']=='node'){
        		$d = cache('node__contentfull'); 
        		if($d){
        			$out[0]['name'] = __('node');
        			$out[0]['url'] = __('node/default/index');
        			$i = 1;
	        		foreach($d as $_k=>$_d){   
	        			$out[$i]['name'] = __($_d[1]);
	        			$out[$i]['url'] = 'node/query/index';
	        			$out[$i]['params'] = array('fid'=>$_k); 
	        			$i++;
	        		} 
	        		$v['datas'] = $out;
        		}
        		
        	}  
        	
        ?>
        	<?php if($v['datas']){?>
	        	<li class="dropdown <?php if(Helper::activeMenu(substr($v['url'],0,strrpos($v['url'],'/')))){?>
		        		active<?php }?>" >
				              <a   class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-dashboard"></i><?php echo __($v['name']);?> <b class="caret"></b></a>
				              <ul class="dropdown-menu">
				              <?php foreach($v['datas'] as $vo){
				              	if(!$vo['params']) $vo['params'] = array();	  
				              ?>	  
				               <li <?php if(Helper::activeMenu(substr($vo['url'],0,strrpos($vo['url'],'/')))){?>
						        		class="active"<?php }?> >
						        		<a href="<?php echo url($vo['url'],$vo['params']);?>">
						        			<i class="fa fa-dashboard"></i><?php echo __($vo['name']);?>
						        		</a>
						        </li>	
				               <?php }?> 
				              </ul>
				            </li> 
	        <?php }else{?>
	        	<li <?php if(Helper::activeMenu(substr($v['url'],0,strrpos($v['url'],'/')))){?>
		        		class="active"<?php }?> >
		        		<a href="<?php echo url($v['url']);?>">
		        			<i class="fa fa-dashboard"></i><?php echo __($v['name']);?>
		        		</a>
		        </li>	
	        <?php }?>
        <?php }?>