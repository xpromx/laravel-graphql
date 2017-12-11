<?php
namespace Xpromx\GraphQL;


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