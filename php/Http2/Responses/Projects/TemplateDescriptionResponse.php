<?php

/** --------------------------------------------------------------------------------
 * This classes renders the response for the [update] process for the projects
 * controller
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Responses\Projects;
use Illuminate\Contracts\Support\Responsable;

class TemplateDescriptionResponse implements Responsable {

    private $payload;

    public function __construct($payload = array()) {
        $this->payload = $payload;
    }

    /**
     * render the view for team members
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function toResponse($request) {

        //set all data to arrays
        foreach ($this->payload as $key => $value) {
            $$key = $value;
        }

        $jsondata['dom_html'][] = [
            'selector' => '#project_descriptionX',
            'action' => 'replace',
            'value' => $template->project_description ?? '',
        ];

        //update the data in the text editor
        $jsondata['tinymce_new_data'][] = [
            'selector' => 'project_description', //do not put #
            'value' => $template->project_description ?? '',
        ];

        //response
        return response()->json($jsondata);
    }

}
