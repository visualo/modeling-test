<?php

class Input {
  private $link;
  private $schema;
  private $table;

  const SELECT = "SELECT ";

  function __construct($link,$schema,$table) {
      $this->link = $link;
      $this->schema = $schema;
      $this->table = $table;
  }

  function set_table()
  {
      $data = ['link' => $this->link, 'schema' => $this->schema, 'table' => $this->table];
      $rules = [
          'link' => ['required', 'isAlpha'],
          'table' => ['required', 'isString'],
          'schema' => ['required', 'isArray']
      ];

      $validator = new Validator();
      $validator->validate($data, $rules);
      
      if($validator->error())
      {
          return json_encode($validator->error());
      }

      $fields = array_values($this->schema['fields']);
      for($i=0;$i<count($fields);$i++)
      {
        if($i==0)
        {
            $sql = self::SELECT.$fields[$i]; 
        }else{                
            $sql = $sql. ", ".$fields[$i]; 
        }
      }
      return $sql; 
  }

  function set_query()
  {
      return 'WITH '.$this->link.' as ('.$this->set_table(). " FROM ".$this->table.'),'; 
  }

}