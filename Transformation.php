<?php

class Transformation {
  private $link;
  private $transformations;
  private $from;
  private $fields;

  function __construct($from,$transformations,$link,$fields) {
    $this->from = $from;
    $this->link = $link;
    $this->transformations = $transformations;
    $this->fields = $fields;
  }

  function set_transformation()
  {
      $data = ['from' => $this->from,'transformations' => $this->transformations,'fields' => $this->fields,'link' => $this->link];
      $rules = [
          'from' => ['required','min' => 1, 'max' => 1,'isAlpha'],
          'transformations' => ['required', 'isArray'],
          'link' => ['required','isArray'],                    
          'fields' => ['required', 'isArray']
      ];

      $validator = new Validator();
      $validator->validate($data, $rules);
      
      if($validator->error())
      {
          return json_encode($validator->error());
      }

      $transform='';  
      foreach ($this->transformations as $key => $transformation) 
      {
          $position = array_search($transformation['column'], $this->fields);
          $new_array = array($position => $transformation['transformation'].'('.$transformation['column'].') AS '.$transformation['column']);
          $this->fields=array_replace($this->fields, $new_array);
      }

      $b_position= array_search($this->from, array_column($this->link, 'to'));
      $b_position= $this->link[$b_position]['from'];

      $transform= Input::SELECT.implode(", ", $this->fields).' FROM '.$b_position;

      return $this->from.' as ('.$transform.'),';
  }

}