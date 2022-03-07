<?php

class Group {
  private $link;
  private $groups;
  private $from;
  private $fields;

  function __construct($from,$groups,$link,$fields) {
    $this->from = $from;
    $this->link = $link;
    $this->groups = $groups;
    $this->fields = $fields;
  }

  function set_group()
  {
      $data = ['from' => $this->from,'groups' => $this->groups,'fields' => $this->fields,'link' => $this->link];
      $rules = [
          'from' => ['required','min' => 1, 'max' => 1,'isAlpha'],
          'groups' => ['required', 'isArray'],
          'link' => ['required','isArray'],                 
          'fields' => ['required', 'isArray']
      ];

      $validator = new Validator();
      $validator->validate($data, $rules);
      
      if($validator->error())
      {
          return json_encode($validator->error());
      }

      $target=[];
      foreach ($this->groups as $key => $group) 
      {
          $position = array_search($group['target'], $this->fields);
          $new_array = array($position => $group['function'].'('.$group['target'].') AS '.$group['target']);
          $this->fields=array_replace($this->fields, $new_array);

          if($key==0)
          {
              $target[$key]= ' Group by '.$group['target'];                 
              $rang= $target[$key];
          }else{
              $target[$key]= ', '.$group['target'];                 
              $rang=$rang. $target[$key];
          }
      }

      $b_position= array_search($this->from, array_column($this->link, 'to'));
      $b_position= $this->link[$b_position]['from'];

      $output= Input::SELECT.implode(", ", $this->fields).' FROM '.$b_position.' '.$rang;

      return $this->from.' as ('.$output.'),';
  }

}