<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [create] process for the events
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Events;
use Illuminate\Contracts\Support\Responsable;

class TopNavResponse implements Responsable {

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

        //render the form
        $html = view('pages/events/topnav', compact('page', 'events'))->render();
        $jsondata['dom_html'][] = array(
            'selector' => '#topnav-events-container',
            'action' => 'replace',
            'value' => $html);

        if ($count > 0) {
            //show footer
            $jsondata['dom_visibility'][] = [
                'selector' => '#topnav-events-container-footer',
                'action' => 'show',
            ];
            //show flashing icon on bell
            $jsondata['dom_visibility'][] = [
                'selector' => '#topnav-notification-icon',
                'action' => 'show',
            ];         
        }else{
            //show flashing icon on bell
            $jsondata['dom_visibility'][] = [
                'selector' => '#topnav-notification-icon',
                'action' => 'hide',
            ];   
        }

        //ajax response
        return response()->json($jsondata);

    }
}