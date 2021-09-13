<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [show] process for the tasks
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Tasks;
use Illuminate\Contracts\Support\Responsable;

class ShowResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view
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

        // RIGHT PANEL---
        $html = view('pages/task/rightpanel', compact('page', 'task', 'assigned', 'milestones', 'project_assigned', 'fields'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#card-right-panel',
            'action' => 'replace',
            'value' => $html);

        // LEFT PANEL---
        $html = view('pages/task/leftpanel', compact('page', 'task', 'progress'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#card-left-panel',
            'action' => 'replace',
            'value' => $html);

        // COMMENTS---
        $html = view('pages/task/components/comment', compact('comments'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#card-comments-container',
            'action' => 'replace',
            'value' => $html);

        // CHECKLISTS---
        $html = view('pages/task/components/checklist', compact('checklists', 'progress'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#card-checklists-container',
            'action' => 'replace',
            'value' => $html);

        // CHECKLIST PROGRESS---
        $html = view('pages/task/components/progressbar', compact('progress'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#card-checklist-progress-container',
            'action' => 'replace',
            'value' => $html);

        // ATTACHMENTS---
        $html = view('pages/task/components/attachment', compact('attachments'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#card-attachments-container',
            'action' => 'replace',
            'value' => $html);

        //HIDE NOTIFICATION ICONS ON CARDS
        $jsondata['dom_visibility'][] = [
            'selector' => "#card_notification_attachment_$id",
            'action' => 'hide',
        ];
        $jsondata['dom_visibility'][] = [
            'selector' => "#card_notification_comment_$id",
            'action' => 'hide',
        ];

        // SHOW MODAL------
        $jsondata['dom_classes'][] = [
            'selector' => '#cardModalContent',
            'action' => 'remove',
            'value' => 'hidden',
        ];

        //update browser url
        $jsondata['dom_browser_url'] = [
            'title' => __('lang.task') . ' - ' . $task->task_title,
            'url' => url("/tasks/v/" . $task->task_id . "/" . str_slug($task->task_title)),
        ];

        //BOOT THE JAVASCRIPT FOR CARDS
        $jsondata['postrun_functions'][] = [
            'value' => 'NXBootCards',
        ];

        //ajax response
        return response()->json($jsondata);

    }

}
