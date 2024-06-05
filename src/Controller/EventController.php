<?php

namespace Factory\PhpFramework\Controller;

use Factory\PhpFramework\Model\Event;
use Factory\PhpFramework\Router\JsonResponse;
use Factory\PhpFramework\Router\Request;
use Factory\PhpFramework\Router\Response;
use Factory\PhpFramework\Twig;

class EventController
{
    public function index(): Response
    {
        $events = Event::all();
        $eventsArray = array_map(fn($event) => $event->toArray(), $events);
        return new Response(Twig::render('event/index.html.twig', ['events' => $eventsArray]));
    }

    public function show(Request $request): JsonResponse|Response
    {
        $id = $request->get('id');
        $event = Event::find($id);
        if (!$event) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }
        return new Response(Twig::render('event/show.html.twig', ['event' => $event->toArray()]));
    }

    public function create(): Response
    {
        return new Response(Twig::render('event/create.html.twig'));
    }

    public function store(Request $request): JsonResponse
    {
        $event = new Event([
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]);
        $event->save();
        header('Location: /Factory-PHP-Framework/events');
        exit();
    }

    public function edit(Request $request): JsonResponse|Response
    {
        $id = $request->get('id');
        $event = Event::find($id);
        if (!$event) {
            return new JsonResponse(['error' => 'Event not found']);
        }
        return new Response(Twig::render('event/edit.html.twig', ['event' => $event->toArray()]));
    }

    public function update(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $event = Event::find($id);
        if (!$event) {
            return new JsonResponse(['error' => 'Event not found']);
        }

        $event->name = $request->get('name');
        $event->description = $request->get('description');

        $event->update();
        header('Location: /Factory-PHP-Framework/events');
        exit();
    }

    public function delete(Request $request): JsonResponse
    {
        $id = $request->get('id');
        $event = Event::find($id);
        if (!$event) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }
        $event->delete();
        header('Location: /Factory-PHP-Framework/events');
        exit();
    }

    public function softDelete(Request $request): JsonResponse|Response
    {
        $id = $request->get('id');
        $event = Event::find($id);
        if (!$event) {
            return new JsonResponse(['error' => 'Event not found'], 404);
        }
        $event->softDelete();
        header('Location: /Factory-PHP-Framework/events');
        exit();
    }
}