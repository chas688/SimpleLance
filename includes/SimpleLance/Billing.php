<?php

namespace SimpleLance;

use PDOException;

/**
 * Class Billing
 * @package SimpleLance
 */
class Billing extends Mailer
{
    /**
     * @var
     */
    private $db;

    /**
     * @param $database
     */
    public function __construct($database)
    {
        $this->db = $database;
    }

    /**
     * @return mixed
     */
    public function listInvoices()
    {
        $query = $this->db->prepare("SELECT * FROM invoices");

        try {
            $query->execute();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll();
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function userInvoices($user_id)
    {
        $query = $this->db->prepare("SELECT * FROM invoices WHERE owner = ?");

        $query->bindValue(1, $user_id);

        try {
            $query->execute();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetchAll();
    }

    /**
     * @param $id
     * @return string
     */
    public function getInvoice($id)
    {
        $query = $this->db->prepare("SELECT * FROM invoices WHERE id = ? LIMIT 1");

        $query->bindValue(1, $id);

        try {
            $query->execute();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

        if ($query->rowCount() != '1') {
            return "Error";
        } else {
            return $query->fetch();
        }
    }

    /**
     * @param $id
     * @return mixed
     */
    public function invoiceItems($id)
    {
        $query = $this->db->prepare("SELECT * FROM invoice_items WHERE invoice_id = ?");

        $query->bindValue(1, $id);

        try {
            $query->execute();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

        return $query->fetchAll();
    }

    /**
     * @param $owner
     * @param $created_date
     * @param $due_date
     */
    public function createInvoice($owner, $created_date, $due_date)
    {
        $query = $this->db->prepare("INSERT INTO invoices (owner, created_date, due_date, status, total) VALUES (?, ?, ?, ?, ?)");

        $query->bindValue(1, $owner);
        $query->bindValue(2, $created_date);
        $query->bindValue(3, $due_date);
        $query->bindValue(4, 'Draft');
        $query->bindValue(5, '0');

        try {
            $query->execute();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

        header("Location: /billing/add_details.php?id=" . $this->db->lastInsertId() . "");
    }

    /**
     * @param $invoice_id
     * @param $item
     * @param $price
     * @param $quantity
     * @param $total
     */
    public function addInvoiceItem($invoice_id, $item, $price, $quantity, $total)
    {
        $query = $this->db->prepare("INSERT INTO invoice_items (invoice_id, item, price, quantity, total) VALUES (?, ?, ?, ?, ?)");

        $query->bindValue(1, $invoice_id);
        $query->bindValue(2, $item);
        $query->bindValue(3, $price);
        $query->bindValue(4, $quantity);
        $query->bindValue(5, $total);

        try {
            $query->execute();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

        $query2 = $this->db->prepare("UPDATE invoices SET total = total + ? WHERE id = ?");

        $query2->bindValue(1, $total);
        $query2->bindValue(2, $invoice_id);

        try {
            $query2->execute();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

        header("Location: /billing/add_details.php?id=" . $invoice_id . "");
    }

    /**
     * @param $invoice_id
     */
    public function sendInvoice($invoice_id)
    {
        $query = $this->db->prepare("UPDATE invoices SET status = ? WHERE id = ?");

        $query->bindValue(1, 'Unpaid');
        $query->bindValue(2, $invoice_id);

        try {
            $query->execute();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

        $user = $this->getUser($this->getInvoice($invoice_id)['owner']);

        $this->sendMail($params = array(
            'email' => $user['email'],
            'name' => $user['first_name']." ".$user['last_name'],
            'subject' => 'New invoice from '.SITE_NAME,
            'body' => 'Hi '.$user['first_name'].' '.$user['last_name'].',<br><br>A new invoice has been generated for
            you at '.SITE_NAME.' for '.CURRSYM.$this->getInvoice($invoice_id)['total'].' and is due on '.date('d/m/Y',
                    strtotime($this->getInvoice($invoice_id)['due_date'])).'.<br><br>You can view the invoice at
                    http://'.SITE_URL.'/billing/invoice?id='.$this->lastInvoiceId().'<br><br>Regards,<br>'. SITE_NAME
        ));

        header("Location: /billing/");
    }

    /**
     * @param $invoice_id
     */
    public function setInvoicePaid($invoice_id)
    {
        $query = $this->db->prepare("UPDATE invoices SET status = ?, date_paid = ? WHERE id = ?");

        $query->bindValue(1, 'Paid');
        $query->bindValue(2, date('Y-m-d'));
        $query->bindValue(3, $invoice_id);

        try {
            $query->execute();

        } catch (PDOException $e) {

            die($e->getMessage());
        }

        $user = $this->getUser($this->getInvoice($invoice_id)['owner']);

        $this->sendMail($params = array(
            'email' => $user['email'],
            'name' => $user['first_name']." ".$user['last_name'],
            'subject' => 'Invoice payment receipt from '.SITE_NAME,
            'body' => ''
        ));

        header("Location: /billing/");
    }

    /**
     * @return mixed
     */
    public function lastInvoiceId()
    {
        $query = $this->db->prepare("SELECT id FROM invoices ORDER BY id DESC LIMIT 1");

        try {
            $query->execute();

        } catch (PDOException $e) {
            die($e->getMessage());
        }

        $data =  $query->fetch();
        $status = $data['id'];

        return $status;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getUser($id)
    {
        $query = $this->db->prepare("SELECT * FROM users WHERE id= ?");
        $query->bindValue(1, $id);

        try {
            $query->execute();
        } catch (PDOException $e) {
            die($e->getMessage());
        }

        return $query->fetch();
    }
}
