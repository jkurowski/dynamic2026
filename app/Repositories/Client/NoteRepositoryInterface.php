<?php namespace App\Repositories\Client;

interface NoteRepositoryInterface
{
    /**
     * @param $attributes
     * @param $client
     * @return object
     */
    public function getNotes($attributes, $client): object;

    /**
     * @param $client
     * @param $note
     * @return object
     */
    public function getNote($client, $note): object;

    /**
     * @param array $attributes
     * @param $client
     * @return array
     */
    public function createNote(array $attributes, $client): array;

    /**
     * @param $request
     * @param $note
     * @param $client
     * @return array
     */
    public function updateNote($request, $client, $note): array;

    /**
     * @param $request
     * @param $client
     * @param $note
     * @return array
     */
    public function pinNote($request, $client, $note): array;

    /**
     * @param $note
     * @param $client
     * @return array
     */
    public function destroyNote($note, $client);
}
