<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update status] process for the tasks
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tasks;
use Illuminate\Contracts\Support\Responsable;

class UpdateStatusResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for tasks
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        //full payload array
        $payload = $this->payload;

        //card
        $board['tasks'] = $tasks;
        $html = view('pages/tasks/components/kanban/card', compact('board'))->render();

        //update kanban card completely
        if ($old_status == $new_status) {
            $jsondata['dom_html'][] = array(
                'selector' => "#card_task_" . $tasks->first()->task_id,
                'action' => 'replace-with',
                'value' => $html);
        }

        //move card to new board
        if ($old_status != $new_status) {
            //remove from current board
            $jsondata['dom_visibility'][] = [
                'selector' => '#card_task_' . $tasks->first()->task_id,
                'action' => 'hide-remove',
            ];
            //add to new board
            $jsondata['dom_html_end'][] = [
                'selector' => '#kanban-board-wrapper-' . $new_status,
                'action' => 'prepend',
                'value' => $html,
            ];

            //replace the row of this record
            $html = view('pages/tasks/components/table/ajax', compact('tasks'))->render();
            $jsondata['dom_html'][] = array(
                'selector' => "#task_" . $tasks->first()->task_id,
                'action' => 'replace-with',
                'value' => $html);

        }

        //update display text
        $jsondata['dom_html'][] = [
            'selector' => '#card-task-status-text',
            'action' => 'replace',
            'value' => $display_status,
        ];

        //remove loading
        $jsondata['dom_classes'][] = array(
            'selector' => '#card-task-status-text',
            'action' => 'remove',
            'value' => 'loading');

        //response
        return response()->json($jsondata);

    }

}
