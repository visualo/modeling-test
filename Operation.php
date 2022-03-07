<?php

class Operation {
  private $link;
  private $functions;
  private $from;
  private $fields;

  function __construct($from,$functions,$link,$fields) {
    $this->from = $from;
    $this->link = $link;
    $this->functions = $functions;
    $this->fields = $fields;
  }

  function set_operation()
  {
      $data = ['from' => $this->from,'functions' => $this->functions,'fields' => $this->fields,'link' => $this->link];
      $rules = [
          'from' => ['required','min' => 1, 'max' => 1,'isAlpha'],
          'functions' => ['required', 'isArray'],
          'link' => ['required','isArray'],                 
          'fields' => ['required', 'isArray']
      ];

      $validator = new Validator();
      $validator->validate($data, $rules);
      
      if($validator->error())
      {
          return json_encode($validator->error());
      }

      foreach ($this->functions as $key => $function) 
      {
          $position = array_search($function['target'], $this->fields);
          $new_array = array($position => $function['function'].'('.$function['target'].') AS '.$function['target']);
          $this->fields=array_replace($this->fields, $new_array);
      }

      $b_position= array_search($this->from, array_column($this->link, 'to'));
      $b_position= $this->link[$b_position]['from'];

      $function= Input::SELECT.implode(", ", $this->fields).' FROM '.$b_position;

      return $this->from.' as ('.$function.'),';
  }

}