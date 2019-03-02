<?php
namespace app\Index\controller;

class Index extends Base
{

    public function index(){
            $carousels = model('Carousel')->getCarouselByStatus();
            $first = model('City')->SearchFirst2();
            return $this->fetch('',
                ['carousels' => $carousels,
                 'citys' => $first
                ]
            );


    }



}
