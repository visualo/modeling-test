<?php

class Output {
  private $link;
  private $outputs;
  private $from;

  function __construct($from,$outputs,$link) {
    $this->from = $from;
    $this->link = $link;
    $this->outputs = $outputs;
  }

  function set_output()
  {
      $data = ['from' => $this->from,'outputs' => $this->outputs,'link' => $this->link];
      $rules = [
          'from' => ['required','min' => 1, 'max' => 1,'isAlpha'],
          'outputs' => ['required', 'isArray'],
          'link' => ['required','isArray']                 
      ];

      $validator = new Validator();
      $validator->validate($data, $rules);
      
      if($validator->error())
      {
          return json_encode($validator->error());
      }

      $actions=[];
      $id=0;      
        foreach ($this->outputs as $key => $output) 
        {
            $actions[$id]= $key;  
            $id++;
            $actions[$id]= $output;                                 
            $id++;
        }

      $b_position= array_search($this->from, array_column($this->link, 'to'));
      $b_position= $this->link[$b_position]['from'];

      $output= Input::SELECT.' * FROM '.$b_position.' '.implode(" ", $actions);

      return $this->from.' as ('.$output.') '.Input::SELECT.' * FROM '.$this->from.';';
  }

}