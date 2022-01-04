<?php
/**
 * Osom contact list
 *
 * @package       OSOMCONTACT
 * @author        Miłosz Michałkiewicz
 * @version       1.0.0
*/

if (!class_exists('WP_List_Table')) {
  require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}


class OsomContactList_Table extends WP_List_Table
{

  private $table_name;

  function __construct(){

      global $status, $page,$wpdb;
      $this->table_name = $wpdb->prefix . 'osom_contact';

      parent::__construct( array(
          'singular' => __( 'OSOM kontakt', '' ),
          'plural' => __( 'OSOM kontakt', '' ),
          'ajax' => false
      ));

      add_action( 'admin_head', array( &$this, 'admin_header' ) );

  }


  public function prepare_items()
  {

      global $wpdb;

      $this->process_bulk_action();

      $per_page = 20;

      $columns = $this->get_columns();
      $hidden = array();
      $sortable = $this->get_sortable_columns();

      $this->_column_headers = array($columns, $hidden, $sortable);

      $total_items = $wpdb->get_var("SELECT COUNT(id) FROM $this->table_name");

      $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;

      $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : 'id';
      $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : 'desc';

      $this->items = $wpdb->get_results($wpdb->prepare("SELECT * FROM $this->table_name ORDER BY $orderby $order LIMIT %d OFFSET %d", $per_page, $paged*$per_page), ARRAY_A);

      $this->set_pagination_args(array(
          'total_items' => $total_items, 
          'per_page' => $per_page, 
          'total_pages' => ceil($total_items / $per_page) 
      ));


  }


  public function osom_contact_form($id){
    global $wpdb;

    $wpdb->delete(
        $this->table_name, array('ID' => $id)
    );
  }

  public function get_columns()
  {
      $columns = array(
          'cb'    => '<input type="checkbox" />',
          'ID' => 'ID',
          'first_name' => 'Imię',
          'last_name' => 'Nazwisko',
          'login' => 'Login',
          'email' => 'Adres email',
          'city' => 'Miasto',
          'date_add' => 'Data przesłania'
      );

      return $columns;
  }

  public function get_hidden_columns()
  {
      return array();
  }

  public function get_bulk_actions() {

      return array(
          'delete' => 'Usuń'
      );

  }
  function process_bulk_action() {

    $action = $this->current_action();

    switch ( $action ) {

        case 'delete':
            $this->delete_compare_exclude($item);
        break;

        default:
            // do nothing or something else
        return;
        break;
    }

    return;
}



  function column_cb($item) {
      return sprintf(
          '<input type="checkbox" name="compare_exclude[]" value="%s" />', $item['ID']
      );
  }


  public function column_default($item, $column_name)
  {
      switch ($column_name) {
          case 'ID':
          case 'first_name':
          case 'last_name':
          case 'login':
          case 'email':
          case 'city':
          case 'date_add':
            return $item[$column_name];
      }
  }



  function delete_compare_exclude( $id ) {
    
    global $wpdb;

    $wpdb->delete(
        $this->table_name, array('ID' => $id)
    );

  }

  private function sort_data($a, $b)
  {
      $orderby = 'id';
      $order = 'asc';

      if (!empty($_GET['orderby'])) {
          $orderby = $_GET['orderby'];
      }

      if (!empty($_GET['order'])) {
          $order = $_GET['order'];
      }

      $result = strcmp($a[$orderby], $b[$orderby]);

      if ($order === 'asc') {
          return $result;
      }

      return -$result;
  }
}

?>