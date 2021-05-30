<?php
class Answer
{
    private $_db, $_data, $table = "answers";
    public $id, $answer, $isCorrect, $question, $createdAt, $updatedAt;

    public function __construct()
    {
        $this->_db = Db::getInstance();
    }

    public function create($fields = array())
    {
        $query = 'INSERT INTO ' . $this->table . '
        SET answer = ?,is_correct = ?,question = ?';
        $fields = array(
            'answer' => $this->answer,
            'is_correct' => $this->isCorrect,
            'question' => $this->question
        );
        $this->_db->query($query, $fields);
    }

    public function update($fields = array(), $id = null)
    {
        if (!$this->_db->update($this->table, $id, $fields)) {
            throw new Exception('There were some error while updating the Answer');
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
}
