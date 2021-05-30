<?php
class Subject
{
    private $_db, $_data, $table = "subjects";
    public $id, $name, $slug, $examFee, $createdAt, $updatedAt;

    public function __construct()
    {
        $this->_db = Db::getInstance();
    }

    public function create($fields = array())
    {
        $query = 'INSERT INTO ' . $this->table . '
        SET name = ?,exam_fee = ?,slug = ?';
        $fields = array(
            'name' => $this->name,
            'exam_fee' => $this->examFee,
            'slug' => $this->slug
        );
        $this->_db->query($query, $fields);
    }

    public function update($fields = array(), $id = null)
    {
        if (!$this->_db->update($this->table, $id, $fields)) {
            throw new Exception('There were some error while updating the Subject');
        }
    }

    public function find($id = null)
    {
        $data = $this->_db->get($this->table, array('id', '=', $id));
        if ($data->count()) {
            $this->_data = $data->first();
            return true;
        }
        return false;
    }

    public function get($id = null)
    {
        return $this->_db->get($this->table, array('id', '=', $id));
    }

    public function data()
    {
        return $this->_data;
    }

    public function getAll()
    {
        return $this->_db->all($this->table)->results();
    }

    public function delete($id)
    {
        return $this->_db->delete($this->table, array('id', '=', $id));
    }

    public function getAllQuestionsById($id = null)
    {
        $db = new Db();
        $database = $db->connect();
        $stmt = $database->prepare("SELECT q.* FROM questions q LEFT JOIN subjects s ON (s.id=q.subject) WHERE s.id=:subjectId");
        $stmt->bindValue(':subjectId', (int)$id);
        $stmt->execute();
        $records = $stmt->fetchAll();
        return $records;
    }
}
