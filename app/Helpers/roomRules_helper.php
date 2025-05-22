<?php 


function get_room_rules(){
    return [
        'name'        => 'required|min_length[3]|max_length[100]',
        'description' => 'permit_empty|max_length[500]',
        'capacity'    => 'permit_empty|integer|greater_than[0]',
        'floor'       => 'permit_empty|max_length[20]',
        'price_hour'  => 'permit_empty|decimal',
        'is_active'   => 'permit_empty|in_list[0,1]',
    ];
}

?>