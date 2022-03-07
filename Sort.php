<?php

class Sort {
  private $link;
  private $sorts;
  private $from;
  private $sql;

  function __construct($from,$sorts,$link,$sql) {
    $this->from = $from;
    $this->link = $link;
    $this->sorts = $sorts;
    $this->sql = $sql;
  }

  function set_sort()
  {
      $data = ['from' => $this->from, 'sorts' => $this->sorts, 'link' => $this->link];
      $rules = [
          'from' => ['required','min' => 1, 'max' => 1,'isAlpha'],
          'link' => ['required','isArray'],          
          'sorts' => ['required', 'isArray']
      ];

      $validator = new Validator();
      $validator->validate($data, $rules);
      
      if($validator->error())
      {
          return json_encode($validator->error());
      }

      $order='';  
      $target=[];
      $ranking=[];

      foreach ($this->sorts as $key => $sort) 
      {

        if($key==0)
        {
            $target[$key]= ' Order by '.$sort['target'];                 
        }else{
            $target[$key]= ', '.$sort['target'];                 
        }
            $ranking[$key]= $sort['order'];                  
            $order= $order.$target[$key].' '.$ranking[$key];
      }
        $b_position= array_search($this->from, array_column($this->link, 'to'));
        $b_position= $this->link[$b_position]['from'];
        
        $order= $this->sql.' FROM '.$b_position.' '.$order;

      return $this->from.' as ('.$order.'),';
  }

}