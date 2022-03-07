<?php
    require_once 'Input.php';
    require_once 'Filter.php';
    require_once 'Sort.php';
    require_once 'Transformation.php';
    require_once 'Output.php';
    require_once 'Group.php';
    require_once 'Operation.php';
    require_once 'Validator.php';


    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["jsonUpload"]["name"]);

    $jsonFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    if(isset($_POST["submit"])) {

      if($jsonFileType == 'json') 
      {
        $strJsonFile = file_get_contents($target_file);    
        $sql_data = modeling($strJsonFile);

        generat_sql($sql_data);
      
      }else{
        echo "File is not an json!";
      }
    }

    function generat_sql($sql_file)
    {
        $mySQL = fopen("request.sql", "w") or die("Unable to open file!");
        fwrite($mySQL, $sql_file);
        fclose($mySQL);
    }

    function modeling($json)
    {
        $obj = json_decode($json, true);
        $result='';

        $nodes = $obj['nodes'];
        $edges = $obj['edges'];

        foreach($nodes as $data) 
        {   
        // KEY INPUT
            if($data['type']=='INPUT')
            {
              $from_a= $data['key']; 
              $schema = $data['transformObject'];
              $table = $schema['tableName'];

              $input = new Input($from_a,$schema,$table); 
              $result = $input->set_query();
//              echo $result;
            }

        // KEY FILTER
            if($data['type']=='FILTER')
            {
              $from_b= $data['key']; 
              $conditions = $data['transformObject'];        

              $input = new Input($from_a,$schema,$table);
              $sql=$input->set_table();

              $filter = new Filter($from_b,$conditions,$edges,$sql);
              $result = $result. $filter->set_filter();
//              echo $result;
            }

        // SELECT SORT    
            if($data['type']=='SORT')
            {
              $from_c= $data['key']; 
              $sorts = $data['transformObject'];        
                
              $input = new Input($from_a,$schema,$table);
              $sql=$input->set_table();

              $sort = new Sort($from_c,$sorts,$edges,$sql);
              $result = $result. $sort->set_sort();
//              echo $result;
            }

        // SELECT TRANSFORMATION
            if($data['type']=='TEXT_TRANSFORMATION')
            {
              $from_d= $data['key']; 
              $transformations = $data['transformObject'];        
              $transform = array_values($schema['fields']);         

              $transform = new Transformation($from_d,$transformations,$edges,$transform);
              $result = $result. $transform->set_transformation();
//              echo $result;
            }

        // SELECT OUTPUT
            if($data['type']=='OUTPUT')
            {
              $from_e= $data['key']; 

                  $last_position= array_column($edges, 'to');

                  if(end($last_position)!=$from_e)
                  {
                    echo 'Error, the output of SQL is incorrect!'; 
                    return false;
                  }

                  $outputs = $data['transformObject'];        

                  $output = new Output($from_e,$outputs,$edges);
                  $result = $result. $output->set_output();
                  echo $result;
                 return $result;
            }

        // SELECT GROUP    
            if($data['type']=='GROUP')
            {
              $G= $data['key']; 
              $groups = $data['transformObject'];        
              $transform = array_values($schema['fields']);         

              $group = new Group($G,$groups,$edges,$transform);
              $result = $result. $group->set_group();
//              echo $result;
            }

        // SELECT FUNCTION    
            if($data['type']=='FUNCTION')
            {
              $F= $data['key']; 
              $functions = $data['transformObject'];        
              $fields = array_values($schema['fields']);         

              $function = new Operation($F,$functions,$edges,$fields);
              $result = $result. $function->set_operation();
//              echo $result;                           
            }

    }

}