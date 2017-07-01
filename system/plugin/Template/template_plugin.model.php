<?php 

class template_plugin_model extends model {


    public function edit_template_config($key='',$data=array(),$temp_key=''){
        $templates=system::load_sys_config("view");
        if($temp_key!=''){
            unset($templates['templates'][$temp_key]);
            $templates['templates'][$temp_key]=$data;
            $res=$this->write_template_config($templates,$temp_key,$data);
        }else{
            $res=$this->write_template_config($templates);
        }
        return $res;
    }
     function write_template_config($data,$key_old='',$key=''){
        $old_templates=system::load_sys_config("view");
        $html="<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.');";
        $html.="\n return ".var_export($data,true).";";
        $html.="\n ?>";
        if($key!=''){
            $old_temp=$old_templates['templates'][$key_old];
            $new_temp=$key;
            if($old_temp['html'] != $new_temp['html']){
                $rename_html = @rename(G_TEMPLATES.$old_temp['dir'].DIRECTORY_SEPARATOR.$old_temp['html'],G_TEMPLATES.$old_temp['dir'].DIRECTORY_SEPARATOR.$new_temp['html']);
                if(!$rename_html){
                    _message("没有权限重命名:".$old_temp['html']);exit;
                }
            }

        }
        if(is_writeable(G_CONFIG.'view.inc.php')){
            $ok=file_put_contents(G_CONFIG.'view.inc.php',$html);
        }
        return $ok;
    }
    

}