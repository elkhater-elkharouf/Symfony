<?php

namespace App\Data;

use App\Entity\Hotel;

class SearchData
{

    /**
     * @var int
     */
    public $page=1;
    /**
     * @var string
     */
    public  $q ='';

    /**
     * @var string
     */

    public $a='';

    /**
     * @var null|integer
     */
    public $max;


    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var boolean
     */
    public $promo =false ;

//--------------------------------------------
    /**
     * @var string
     */
    public  $marque ='';
    /**
     * @var string
     */
    public  $modele ='';

    /**
     * @var string
     */
    public  $couleur ='';


}