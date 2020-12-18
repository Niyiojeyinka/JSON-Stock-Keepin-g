<?php
/** handle CRUD Operation on json file
 * 
 */
class Database{
	public $file;
	public $all_records;

    /** constructor function
     * @param string filename
     */
	function __construct($file){
        $this->file = $file;

        $db = fopen($this->file, "r") or die("Unable to open file!");
          
       
              $data = fread($db,filesize($this->file));
        fclose($db);
        //got the initial content of the file in text/json format and covert to array
        $this->all_records =json_decode($data,true);
          
    }
    
   /** overwrite the json file
    * @param array of records
    * @return  void
    */
    public function save_all($all_records){
        $db = fopen($this->file, "w") or die("Unable to open file!");
       
        fwrite($db, json_encode($all_records));
        fclose($db);
    }
    /** append record json file
    * @param array of record
    * @return  void
    */
    public function insert($newRecordArray){
        

      array_push($this->all_records, $newRecordArray);
        
       $this->save_all($this->all_records);

    }

      /** only insert if not exists
    * @param string unique column
    * @param array new entry
    * @return  array|boolean or false
    */
    public function uniqueInsert($uniqueColumn,$data){

        foreach ($this->all_records as $record) {
        if ($record[$uniqueColumn] == $data[$uniqueColumn]) {
            return false;
        }
    }
        $this->insert($data);
    }

    /** delete a record
    * @param array of records
    * @return  void
    */
    public function deleteRecord($cond){
        $all_records = $this->all_records;

        foreach($cond as $key=>$value){
        $index = 0;
        foreach ($all_records as $record) {
        if ($record[$key] == $value) {

        unset($all_records[$index]);
        }
        $index++;
        }

    }
    $this->save_all($all_records);

    }
    /** delete a record by index 
    * @param integer index to delete
    * @return  void
    */
    public function deleteByIndex($index)
    {
        unset($this->all_records[$index]);
        $this->all_records  = array_values($this->all_records);//reindex
        $this->save_all($this->all_records);

    }
    /** update record by index 
    * @param integer index to update
    * @param array of update data
    * @return  void
    */
    public function updateByIndex($index,$update)
    {
        $this->all_records[$index]=$update;
        $this->save_all($this->all_records);

    }

     /** get record by index 
    * @param integer index of entry to get
    * @return  array
    */
    public function getEntryByIndex($index)
    {
       return $this->all_records[$index];

    }
}