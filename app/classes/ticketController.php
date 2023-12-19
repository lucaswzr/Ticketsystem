<?php

namespace APP\classes;

use APP\db\ConnectionDB;

class ticketController
{
    private $connection;

    function __construct(){
        $this->connection = ConnectionDB::getDB();
    }

    public function getAllTickets($filter): bool|array
    {
        $tickets = $this->connection->prepare('SELECT ticketID,creator,creationDate,Prio FROM `ticket` WHERE finish IN (:filter)');
        $tickets->bindValue('filter', $filter);
        $tickets->execute();
        return $tickets->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ticketData($ticketID): bool|array
    {
        $ticketData = $this->connection->prepare('SELECT * FROM `ticket` LEFT JOIN `ticketContent` ON ticketContent.ticketID = ticket.ticketID LEFT JOIN ticketUser
                                                ON ticketUser.ticketID = ticket.ticketID WHERE ticket.ticketID = :ticketid');
        $ticketData->bindParam('ticketid', $ticketID);
        $ticketData->execute();
        return $ticketData->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function ticketForm($data): void
    {

        $formData = [
            'prio' => $data['prio'] ?? '0',
            'creationDate' => $data['creationDate'] ?? 'NOW()',
            'finishDate' => $data['finishDate'] ?? '',
            'typ' => $data['typ'] ?? '',
            'describtion' => $data['describtion'] ?? '',
            'appearanceTime' => $data['appearanceTime'] ?? '',
            'customerID' => $data['customerID'] ?? '',
            'firstName' => $data['firstName'] ?? '',
            'lastName' => $data['lastName'] ?? '',
            'adress' => $data['adress'] ?? '',
            'phoneNumber' => $data['phoneNumber'] ?? '',
            'mail' => $data['mail'] ?? '',
        ];

        try {
            $this->connection->beginTransaction();

            $stmt = $this->connection->prepare("INSERT INTO ticket (creator, creationDate, finishDate, Prio) VALUES (:creator, :erstellungsdatum, :fertigDatum, :prioritaet)");
            $stmt->bindValue(':creator', '13');
            $stmt->bindValue(':erstellungsdatum', $formData['creationDate']);
            $stmt->bindValue(':fertigDatum', $formData['finishDate']);
            $stmt->bindValue(':prioritaet', $formData['prio']);
            $stmt->execute();
            $lastID = $this->connection->lastInsertId();

            $stmt2 = $this->connection->prepare("INSERT INTO ticketContent (ticketID, typ, describtion, appearanceTime) VALUES (:id, :typ, :descr, :appearanceTime)");
            $stmt2->bindValue(':id', $lastID);
            $stmt2->bindValue(':typ', $formData['typ']);
            $stmt2->bindValue(':descr', $formData['describtion']);
            $stmt2->bindValue(':appearanceTime', $formData['appearanceTime']);
            $stmt2->execute();

            $stmt3 = $this->connection->prepare("INSERT INTO ticketUser (ticketID, customerID, firstName, lastName, adress, phoneNumber, mail) VALUES (:id, :customerID, :firstName, :lastName, :adress, :phoneNumber, :mail)");
            $stmt3->bindValue(':id', $lastID);
            $stmt3->bindValue(':customerID', $formData['customerID']);
            $stmt3->bindValue(':firstName', $formData['firstName']);
            $stmt3->bindValue(':lastName', $formData['lastName']);
            $stmt3->bindValue(':adress', $formData['adress']);
            $stmt3->bindValue(':phoneNumber', $formData['phoneNumber']);
            $stmt3->bindValue(':mail', $formData['mail']);
            $stmt3->execute();

            $this->connection->commit();

        } catch (\Exception $e){
            $this->connection->rollBack();
            echo "Fehler: " . $e->getMessage();
        }
    }

    public function closeOpenTicket($ticketID, $direction): void
    {

        $stmt = $this->connection->prepare('UPDATE ticket SET finish = :direc WHERE ticketID= :ticketid');
        $stmt->bindValue('direc', $direction);
        $stmt->bindValue('ticketid', $ticketID);
        $stmt->execute();

    }
}