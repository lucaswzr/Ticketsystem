<?php

require_once 'vendor/autoload.php';

use APP\classes\twigRenderer;

$renderer = new \APP\classes\twigRenderer();
$ticket = new \APP\classes\ticketController();

$ticketID = isset($_GET['ticket']) ? intval($_GET['ticket']) : 0;
if ($ticketID > 0) {
    $specificTicketData = $ticket->ticketData($ticketID);
    echo $renderer->render('ticket.twig', ['ticketdetails' => $specificTicketData]);
} elseif (isset($_GET['newticket'])){
    echo $renderer->render('newTicket.twig');
} elseif (isset($_GET['editTicket']) && intval($_GET['editTicket'])){
    $editableTicketData = $ticket->ticketData(intval($_GET['editTicket']));
    echo $renderer->render('newTicket.twig', ['ticketdetails' => $editableTicketData]);
} else {

    $filter = $_GET['filter'] ?? 0;
    $alltickets = $ticket->getAllTickets($filter);
    echo $renderer->render('ticketlist.twig', ['data' => $alltickets]);
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){

    if (isset($_POST['submitTicket'])){
        $ticket->ticketForm($_POST);
    } elseif (isset($_POST['closeTicket'])){
        $ticket->closeOpenTicket($_POST['ticketID'], 1);
    } elseif (isset($_POST['openTicket'])){
        $ticket->closeOpenTicket($_POST['ticketID'], 0);
    }
}
