<?php
class Question
{
    private $_db, $_data, $table = "questions";
    public $id, $question, $image, $is_multiple_answer, $subject, $createdAt, $updatedAt;

    public function __construct()
    {
        $this->_db = Db::getInstance();
    }

    public function create($fields = array())
    {
        $query = 'INSERT INTO ' . $this->table . '
        SET question = ?,subject = ?';
        $fields = array(
            'question' => $this->question,
            'subject' => $this->subject
        );
        if (isset($this->image)) {
            $query  .= ',image=?';
            $fields['image'] = $this->image;
        }
        if ($this->is_multiple_answer) {
            $query  .= ',is_multiple_answer=?';
            $fields['is_multiple_answer'] = $this->is_multiple_answer;
        }
        $this->_db->query($query, $fields);
    }

    public function update($fields = array(), $id = null)
    {
        if (!$this->_db->update($this->table, $id, $fields)) {
            throw new Exception('There were some error while updating the Question');
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

    public function getAllAnswersById($id = null)
    {
        $db = new Db();
        $database = $db->connect();
        $stmt = $database->prepare("SELECT a.* FROM answers a LEFT JOIN questions q ON (q.id=a.question) WHERE q.id=:questionIds");
        $stmt->bindValue(':questionIds', (int)$id);
        $stmt->execute();
        $records = $stmt->fetchAll();
        return $records;
    }
}
