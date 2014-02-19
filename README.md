#Codeigniter Base Model

Read [documentation](https://github.com/WebFikirleri/WF-CI-Base-Model/wiki/Welcome) for more information: 
[Documentation](https://github.com/WebFikirleri/WF-CI-Base-Model/wiki/Welcome)

##Usage

###Creating Model

When you create a model for your table, it has to contains table name with \_mdl or  \_model suffix or mdl_ prefix.

    tablename_mdl.php
    tablename_model.php
    mdl_tablename.php
    
Under "application/core" folder you have to create "MY_Model.php" and add this lines in it:

    <?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class MY_Model extends CI_Model { }
    
    class WF_Base_Model extends CI_Model {
      // insert wf base model here...
    }
    
Now you can use "WF\_Base\_Model" or "CI\_Model" for extending your model.

Under "application/models" folder you have to create your table model as described. Example mdl_tablename.php

    <?php if (!defined('BASEPATH')) exit('No direct script access allowed');
    
    class mdl_tablename extends WF_Base_Model {
    
    }
  
That's all...

###Using Like A CodeIgniter DB Class

You can use this model like CodeIgniter's Model. For example:

    $this->load->model('mdl_tablename');
    $result = $this->mdl_tablename->where(array('datetime <' => date('Y-m-d H:i:s')))->order_by('datetime','DESC')->get_result();
    foreach ($result as $item) {
      echo $item->datetime;
    }
    
    //or
    $row = $this->mdl_tablename->where(array('id'=>'1')->get_row();
    echo $row->datetime;
    
###Custom Queries

You can return result or row from custom queries:

    $result = $this->mdl_tablename->query_result("SELECT * FROM tablename WHERE `datetime` > '2000-01-01 00:00:00'");

or

    $row = $this->mdl_tablename->query_row("SELECT * FROM tablename WHERE id=1");
    
To use table prefixes you have to get prefix.

    $pfx = $this->db->_dbprefix;
    // or
    $tablename = $this->mdl_table->get_table_name(true); // true will add prefix to tablename, default = false
    $row = $this->mdl_tablename->query_row("SELECT * FROM {$tablename} WHERE id=1");

