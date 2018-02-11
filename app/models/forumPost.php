<?php

class forumPost extends Eloquent{
    protected $table = "forumPosts";
    //protected $hidden = ["id"];
    public static $rules = array(
        'content' => 'required|between:2,2000',
        'topicId' => 'required|alpha_num',
        'userId' => 'alpha_num',
        'date' => 'date'
    );

    public static $rulesCreate = array(
        'content' => 'required|between:2,2000',
        'topicId' => 'alpha_num',
        'userId' => 'alpha_num',
        'date' => 'date'
    );

}
