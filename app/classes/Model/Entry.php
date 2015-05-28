<?php

namespace Model;

class Entry extends \Obullo\Database\Model
{
    public $title;
    public $content;
    public $date;

    /**
     * Get one entry
     *
     * @param integer $id user id
     * 
     * @return array
     */
    public function findOne($id = 1)
    {
        return $this->db->prepare("SELECT * FROM entries WHERE id = ?")
            ->bindParam(1, $id, \PDO::PARAM_INT)
            ->execute()->rowArray();
    }

    /**
     * Get all entries
     *
     * @param integer $limit number
     * 
     * @return array
     */
    public function findAll($limit = 10)
    {
        return $this->db->prepare("SELECT * FROM users LIMIT ?")
            ->bindParam(1, $limit, \PDO::PARAM_INT)
            ->execute()->resultArray();
    }

    /**
     * Insert entry
     * 
     * @return void
     */
    public function insert()
    {
        return $this->db->exec(
            sprintf(
                "INSERT INTO entries (title, content, date) VALUES (%s, %s, %d)",
                $this->db->escape($this->title),
                $this->db->escape($this->content),
                $this->date
            )
        );

    }

    /**
     * Update entry
     * 
     * @param integer $id id
     * 
     * @return void
     */
    public function update($id)
    {
        return $this->db->exec(
            sprintf(
                "UPDATE entries SET title = %s, content = %s, date = %d WHERE id = %d",
                $this->db->escape($this->title),
                $this->db->escape($this->content),
                $this->date,
                $id
            )
        );
    }

    /**
     * Example transaction support
     * 
     * @param integer $id id
     * 
     * @return void
     */
    public function delete($id)
    {
        return $this->db->transaction(
            function () use ($id) {

                return $this->db->exec(
                    sprintf(
                        "DELETE FROM entries WHERE id = %d",
                        $id
                    )
                );
            }
        );
    }

    /**
     * Loader
     * 
     * @return void
     */
    public function load()
    {
        $this->c['db'];
    }

}