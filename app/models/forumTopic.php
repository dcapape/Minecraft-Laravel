<?php

class forumTopic extends Eloquent{
    protected $table = "forumTopics";
    //protected $hidden = ["id"];
    public static $rules = array(
        'subject' => 'required|string',
        'content' => 'required|between:2,2000',
        'categoryId' => 'required|alpha_num|exists:forumCategories,id',
        'userId' => 'alpha_num',
        'date' => 'date'
    );


}
