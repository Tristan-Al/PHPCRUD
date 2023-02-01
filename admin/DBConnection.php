<?php

class DBConnection{
    
    protected $_config;     //Array con los settings de acceso a la BD
    private $dbc;            //Contiene la conexion a la BD
    
    
//estructura de comentarios para las funciones

    /*
     * funcion contructora de la clase  (Breve descripcion de la funcion)
     * Abre una conexion con la BD
     * @param $_config array Parametros de conexion con la BD (Breve descrip de cada parametro que empieza por @)
     */
     
    public function __construct( array $config ){
        $this->_config = $config;
        $this->getPDOConnection();
    }
    
    private function getPDOConnection(){
        //Comprobar si ya tenemos una conexion previa
        if ($this->dbc == NULL) { //NO tenemos una conexion previa, hay que crearla
            //Creamos el DSN
            $dsn = "".
                    $this->_config['driver'].
                    ":host=".$this->_config['host'].
                    ";dbname=".$this->_config['dbname'];        
            //Hacemos la conexion persistente y activar el lanzamiento de excepciones
            $options = array(
                PDO::ATTR_PERSISTENT  => true,
                PDO::ATTR_ERRMODE     => PDO::ERRMODE_EXCEPTION,
            );

            try {
                $this->dbc = new PDO($dsn, $this->_config['username'], $this->_config['password'], $options);
            } catch (PDOException $ex) {
                echo __LINE__.$ex->getMessage();
            }
        }
    }

    /*
     * Funcion que devuelve un resultset de una consulta
     * Ejecuta una consulta SELECT
     * @param sql string Sentencia de la consulta
     * @returns resulset
     */
    public function getQuery( $sql ){
        try {
            $resultset = $this->dbc->query($sql);       //Hacemos la consulta
            $resultset->setFetchMode( PDO::FETCH_ASSOC ); //Nos devuelve el resultset como un array asociativo
        } catch (PDOException $ex) {
            echo __LINE__.$ex->getMessage();
        }
        return $resultset;
    }

    /*
     * Funcion que devuelve numero de tuplas afectadas por
     * una consulta del tipo INSERT, DELETE, UPDATE
     * @param sql string Sentencia de la consulta
     * @returns int numero de tuplas afectadas
     */
    public function runQuery( $sql ){
        try {
            $count = $this->dbc->exec($sql);    //Hacemos la consulta que nos devuelve un N de tuplas

        } catch (PDOException $ex) {
            echo __LINE__.$ex->getMessage();
        }
        return $count;
    }

    /*
     * Function that retrieves the instance itself
     * @$dbc
     */
    public function getCon(){
        return $this->dbc;
    }
}

?>