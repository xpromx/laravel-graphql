<?php
namespace App\GraphQL\Support;


trait Authorize {

    public function authorize($root, $args)
    {
        if( request()->user() ) 
        {
            return true;
        }
        
        return false;
    }

}