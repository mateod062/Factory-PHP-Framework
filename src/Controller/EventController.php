<?php

namespace Factory\PhpFramework\Controller;

use Factory\PhpFramework\Database\Connection;
use Factory\PhpFramework\Router\JsonResponse;
use Factory\PhpFramework\Router\Request;

class EventController
{
    private Connection $dbConnection;

    public function __construct()
    {
        $this->dbConnection = Connection::getInstance();
    }

    /**
     * Fetch an event by ID with named placeholders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchEventActionAssoc(Request $request): JsonResponse
    {
        $eventId = $request->get('id');
        $query = "SELECT * FROM event WHERE id = :id";
        $result = $this->dbConnection->fetchAssoc($query, ['id' => $eventId]);

        if (!$result) {
            http_response_code(404);
            return new JsonResponse(['error' => 'Event not found']);
        }

        return new JsonResponse($result);
    }

    /**
     * Fetch an event by ID with unnamed placeholders
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchEventActionNum(Request $request): JsonResponse
    {
        $eventId = $request->get('id');
        $query = "SELECT * FROM event WHERE id = ?";
        $result = $this->dbConnection->fetchAssoc($query, [$eventId]);

        if (!$result) {
            http_response_code(404);
            return new JsonResponse(['error' => 'Event not found']);
        }

        return new JsonResponse($result);
    }

    /**
     * Fetch all events
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function fetchAllEventsAction(Request $request): JsonResponse
    {
        $query = "SELECT * FROM event";
        $result = $this->dbConnection->fetchAssocAll($query);
        return new JsonResponse($result);
    }

    /**
     * Insert an event
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function insertEventAction(Request $request): JsonResponse
    {
        $data = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ];
        $result = $this->dbConnection->insert('event', $data);
        return new JsonResponse(['success' => $result]);
    }

    /**
     * Batch insert events
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function batchInsertEventsAction(Request $request): JsonResponse
    {
        $data = $request->getBody()['data'];
        $result = $this->dbConnection->insert('event', $data);
        return new JsonResponse(['success' => $result]);
    }

    /**
     * Update an event
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function updateEventAction(Request $request): JsonResponse
    {
        $eventId = $request->get('id');
        $data = [
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ];
        $result = $this->dbConnection->update('event', $data, ['id' => $eventId]);
        return new JsonResponse(['success' => $result]);
    }
}