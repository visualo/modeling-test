<?php

class Filter {
  private $link;
  private $conditions;
  private $from;
  private $sql;

  function __construct($from,$conditions,$link,$sql) {
    $this->from = $from;
    $this->link = $link;
    $this->conditions = $conditions;
    $this->sql = $sql;
  }

  function set_filter()
  {
      $data = ['from' => $this->from, 'conditions' => $this->conditions, 'link' => $this->link, 'sql' => $this->sql];
      $rules = [
          'from' => ['required','min' => 1, 'max' => 1,'isAlpha'],
          'link' => ['required','isArray'],
          'conditions' => ['required', 'isArray'],
          'sql' => ['required', 'isString'],
      ];

      $validator = new Validator();
      $validator->validate($data, $rules);
      
      if($validator->error())
      {
          return json_encode($validator->error());
      }

      $field_filter=[];
      $operator_filter=[];

      $operators=[];
      $values=[];
      $filtre='';  

      foreach ($this->conditions as $key => $condition) 
      {
        if($key==0)
        {
            $field_filter[$key]= " where ".$condition['variable_field_name'];                 
            if(count($this->conditions)>1)
            {
                $operator_filter[$key]= $condition['joinOperator'].' ';                 
            }else{
                $operator_filter[$key]= ' ';                               
            }

            if(is_array($condition['operations']))
            {
                $operators[0]= $condition['operations'][0]['operator'];                 
                $values[0]= $condition['operations'][0]['value'];                                         

                $filtre= $field_filter[0].' '.$operators[0].' '.$values[0].' '.$operator_filter[0];
            }
            
        }else{

            if(count($this->conditions)-1==$key)
            {
                $field_filter[$key]= $condition['variable_field_name'];                 
                $operator_filter[$key]= '';                   
            }else{
                $field_filter[$key]= $condition['variable_field_name'];                 
                $operator_filter[$key]= $condition['joinOperator'].' ';                   
            }

            if(is_array($condition['operations']))
            {
                if($condition['operations'][0]['operator']=='like')
                {
                    $values[0]= "'".$condition['operations'][0]['value']."'";
                }else{
                    if(is_array($condition['operations'][0]['value']))
                    {
                        $list_in='';
                        for($i=0;$i<count($condition['operations'][0]['value']);$i++)
                        {
                            if($i==count($condition['operations'][0]['value'])-1)
                            {
                                $list_in= $list_in."'".$condition['operations'][0]['value'][$i]."'";
                            }else{
                                $list_in= $list_in."'".$condition['operations'][0]['value'][$i]."',";
                            }
                        }
                            $values[0]= "(".$list_in.")";
                    }else{
                        $values[0]= $condition['operations'][0]['value'];                        
                    }                                         
                }
                $operators[0]= $condition['operations'][0]['operator'];                 
            }
            $filtre= $filtre.$field_filter[$key].' '.$operators[0].' '.$values[0].' '.$operator_filter[$key];
        }
      }  
        $b_position= array_search($this->from, array_column($this->link, 'to'));
        $b_position= $this->link[$b_position]['from'];
        
        $filtre=$this->sql.' FROM '.$b_position.' '.$filtre;
        return $this->from.' as ('.$filtre.'),';
  }

}