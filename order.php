<?php
    $code = 200; // 接口状态码 

    $name = trim($_INPUT['name']);
    $age  = trim($_INPUT['age']);
    if (empty($name))
    {
        $code = 401;
        throw new Exception('名字不能为空');
    }
    if (!is_numeric($age))
    {
        $code = 402;
        throw new Exception('年龄必须由数字组成');
    }
    
    $database_obj = new database_class();
    $res = $database_obj->save($name, $age);
    if ( !$res )
    {
        $code = 403;
        throw new Exception('保存数据失败');
    }    
    $msg = 'ok';
} catch ( Exception $e ) {
    $msg = $e->getMessage();
}
output_json($code,$data,$msg); // $data可以放置需要返回的数据
// output_json函数在大function里面有~如果不想引入大function的话可以复制一份到自己项目
// ========END=======