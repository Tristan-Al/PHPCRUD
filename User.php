<?php
class User{
    protected $user_username;
    protected $user_pass;
    protected $first_name;
    protected $last_name;
    protected $email;
    protected $address;
    protected $postcode;
    protected $city;
    protected $edulevel;
    protected $degree;
    protected $year;
    protected $langlevel;
    protected $status;
    protected $trash;
    protected $member_from;

    public function __construct($data){
        foreach ($this as $key => $value) {
            if (property_exists($this,$key)) {
                $this->$key = $data[$key];
            }
        }
    }

    public function getFields(){
        $fields = array();
        foreach ($this as $field => $value) { 
            $fields[$field] = "'".$value."'";
        }
        return $fields;
    }

}

?>