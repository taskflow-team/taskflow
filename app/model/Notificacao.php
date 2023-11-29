<?php

class Notificacao
{
    private $id_notification;
    private $type;
    private $message;
    private $date_created;
    private $id_user;
    private $id_group;
    private $is_read;

    /**
     * Get the value of id_notification
     */
    public function getId_notification()
    {
        return $this->id_notification;
    }

    /**
     * Set the value of id_notification
     *
     * @return  self
     */
    public function setId_notification($id_notification)
    {
        $this->id_notification = $id_notification;

        return $this;
    }

    /**
     * Get the value of type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set the value of type
     *
     * @return  self
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get the value of message
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set the value of message
     *
     * @return  self
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get the value of date_created
     */
    public function getDate_created()
    {
        return $this->date_created;
    }

    /**
     * Set the value of date_created
     *
     * @return  self
     */
    public function setDate_created($date_created)
    {
        $this->date_created = $date_created;

        return $this;
    }

    /**
     * Get the value of id_user
     */
    public function getId_user()
    {
        return $this->id_user;
    }

    /**
     * Set the value of id_user
     *
     * @return  self
     */
    public function setId_user($id_user)
    {
        $this->id_user = $id_user;

        return $this;
    }

    /**
     * Get the value of id_group
     */
    public function getId_group()
    {
        return $this->id_group;
    }

    /**
     * Set the value of id_group
     *
     * @return  self
     */
    public function setId_group($id_group)
    {
        $this->id_group = $id_group;

        return $this;
    }

    /**
     * Get the value of is_read
     */
    public function getIs_read()
    {
        return $this->is_read;
    }

    /**
     * Set the value of is_read
     *
     * @return  self
     */
    public function setIs_read($is_read)
    {
        $this->is_read = $is_read;

        return $this;
    }
}
