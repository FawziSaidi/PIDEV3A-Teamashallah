<?php

namespace App\Entity;



class Category
{

    public  const Math = 1;
    public  const programming = 2;
    public  const science = 3;



    public static function all():array
    {
        return[
            
            self::Math,
            self::programming,
            self::science

        ];
    }

}


