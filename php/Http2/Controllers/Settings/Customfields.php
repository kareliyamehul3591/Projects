<?php

/** --------------------------------------------------------------------------------
 * This controller manages all the business logic for template settings
 *
 * @package    Grow CRM
 * @author     NextLoop
 *----------------------------------------------------------------------------------*/

namespace App\Http\Controllers\Settings;
use App\Http\Controllers\Controller;
use App\Http\Responses\Settings\Customfields\IndexResponse;
use App\Http\Responses\Settings\Customfields\UpdateResponse;
use Illuminate\Http\Request;

class Customfields extends Controller {

    public function __construct() {

        //parent
        parent::__construct();

        //authenticated
        $this->middleware('auth');

        //settings general
        $this->middleware('settingsMiddlewareIndex');

    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function showClient() {

        //crumbs, page data & stats
        $page = $this->pageSettings('clients');

        $fields = \App\Models\CustomField::Where('customfields_type', 'clients')->get();

        //reponse payload
        $payload = [
            'page' => $page,
            'fields' => $fields,
            'template' => 'pages/settings/sections/customfields/clients',
        ];

        //show the view
        return new IndexResponse($payload);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateClient() {

        //status
        $status = true;
        $message = '';
        $count = 0;

        //validate - check if some boxes are ticked but the field name is blank
        foreach (request('customfields_title') as $key => $value) {
            $count++;
            //error count
            $error = false;
            if ($value == '') {
                if ($_POST['customfields_required'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_show_client_page'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_show_invoice'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_status'][$key] == 'on') {
                    $error = true;
                }
            }
            //message
            if ($error) {
                $message .= __('lang.form_field') . " ($count) </br>";
                //error status
                $status = false;
            }
        }

        //validate form
        if (!$status) {
            abort(409, __('lang.the_following_fields_do_not_have_a_name') . '</br>' . $message);
        }

        //update each field
        foreach (request('customfields_title') as $key => $value) {
            //reset existing account owner
            \App\Models\CustomField::where('customfields_id', $key)
                ->update([
                    'customfields_title' => $_POST['customfields_title'][$key],
                    'customfields_required' => runtimeDBCheckBoxYesNo($_POST['customfields_required'][$key]),
                    'customfields_show_client_page' => runtimeDBCheckBoxYesNo($_POST['customfields_show_client_page'][$key]),
                    'customfields_show_invoice' => runtimeDBCheckBoxYesNo($_POST['customfields_show_invoice'][$key]),
                    'customfields_status' => runtimeDBCheckBoxEnabledDisabled($_POST['customfields_status'][$key]),
                ]);
        }

        //reponse payload
        $payload = [];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function showProject() {

        //crumbs, page data & stats
        $page = $this->pageSettings('projects');

        $fields = \App\Models\CustomField::Where('customfields_type', 'projects')->get();

        //reponse payload
        $payload = [
            'page' => $page,
            'fields' => $fields,
            'template' => 'pages/settings/sections/customfields/projects',
        ];

        //show the view
        return new IndexResponse($payload);
    }


    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProject() {

        //status
        $status = true;
        $message = '';
        $count = 0;

        //validate - check if some boxes are ticked but the field name is blank
        foreach (request('customfields_title') as $key => $value) {
            $count++;
            //error count
            $error = false;
            if ($value == '') {
                if ($_POST['customfields_required'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_show_project_page'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_status'][$key] == 'on') {
                    $error = true;
                }
            }
            //message
            if ($error) {
                $message .= __('lang.form_field') . " ($count) </br>";
                //error status
                $status = false;
            }
        }

        //validate form
        if (!$status) {
            abort(409, __('lang.the_following_fields_do_not_have_a_name') . '</br>' . $message);
        }

        //update each field
        foreach (request('customfields_title') as $key => $value) {
            //reset existing account owner
            \App\Models\CustomField::where('customfields_id', $key)
                ->update([
                    'customfields_title' => $_POST['customfields_title'][$key],
                    'customfields_required' => runtimeDBCheckBoxYesNo($_POST['customfields_required'][$key]),
                    'customfields_show_project_page' => runtimeDBCheckBoxYesNo($_POST['customfields_show_project_page'][$key]),
                    'customfields_status' => runtimeDBCheckBoxEnabledDisabled($_POST['customfields_status'][$key]),
                ]);
        }

        //reponse payload
        $payload = [];

        //generate a response
        return new UpdateResponse($payload);
    }




        /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function showLead() {

        //crumbs, page data & stats
        $page = $this->pageSettings('leads');

        $fields = \App\Models\CustomField::Where('customfields_type', 'leads')->get();

        //reponse payload
        $payload = [
            'page' => $page,
            'fields' => $fields,
            'template' => 'pages/settings/sections/customfields/leads',
        ];

        //show the view
        return new IndexResponse($payload);
    }


    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateLead() {

        //status
        $status = true;
        $message = '';
        $count = 0;

        //validate - check if some boxes are ticked but the field name is blank
        foreach (request('customfields_title') as $key => $value) {
            $count++;
            //error count
            $error = false;
            if ($value == '') {
                if ($_POST['customfields_required'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_show_lead_summary'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_status'][$key] == 'on') {
                    $error = true;
                }
            }
            //message
            if ($error) {
                $message .= __('lang.form_field') . " ($count) </br>";
                //error status
                $status = false;
            }
        }

        //validate form
        if (!$status) {
            abort(409, __('lang.the_following_fields_do_not_have_a_name') . '</br>' . $message);
        }

        //update each field
        foreach (request('customfields_title') as $key => $value) {
            //reset existing account owner
            \App\Models\CustomField::where('customfields_id', $key)
                ->update([
                    'customfields_title' => $_POST['customfields_title'][$key],
                    'customfields_required' => runtimeDBCheckBoxYesNo($_POST['customfields_required'][$key]),
                    'customfields_show_lead_summary' => runtimeDBCheckBoxYesNo($_POST['customfields_show_lead_summary'][$key]),
                    'customfields_status' => runtimeDBCheckBoxEnabledDisabled($_POST['customfields_status'][$key]),
                ]);
        }

        //reponse payload
        $payload = [];

        //generate a response
        return new UpdateResponse($payload);
    }



            /**
     * Display general settings
     *
     * @return \Illuminate\Http\Response
     */
    public function showTask() {

        //crumbs, page data & stats
        $page = $this->pageSettings('tasks');

        $fields = \App\Models\CustomField::Where('customfields_type', 'tasks')->get();

        //reponse payload
        $payload = [
            'page' => $page,
            'fields' => $fields,
            'template' => 'pages/settings/sections/customfields/tasks',
        ];

        //show the view
        return new IndexResponse($payload);
    }


    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateTask() {

        //status
        $status = true;
        $message = '';
        $count = 0;

        //validate - check if some boxes are ticked but the field name is blank
        foreach (request('customfields_title') as $key => $value) {
            $count++;
            //error count
            $error = false;
            if ($value == '') {
                if ($_POST['customfields_required'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_show_task_summary'][$key] == 'on') {
                    $error = true;
                }
                if ($_POST['customfields_status'][$key] == 'on') {
                    $error = true;
                }
            }
            //message
            if ($error) {
                $message .= __('lang.form_field') . " ($count) </br>";
                //error status
                $status = false;
            }
        }

        //validate form
        if (!$status) {
            abort(409, __('lang.the_following_fields_do_not_have_a_name') . '</br>' . $message);
        }

        //update each field
        foreach (request('customfields_title') as $key => $value) {
            //reset existing account owner
            \App\Models\CustomField::where('customfields_id', $key)
                ->update([
                    'customfields_title' => $_POST['customfields_title'][$key],
                    'customfields_required' => runtimeDBCheckBoxYesNo($_POST['customfields_required'][$key]),
                    'customfields_show_task_summary' => runtimeDBCheckBoxYesNo($_POST['customfields_show_task_summary'][$key]),
                    'customfields_status' => runtimeDBCheckBoxEnabledDisabled($_POST['customfields_status'][$key]),
                ]);
        }

        //reponse payload
        $payload = [];

        //generate a response
        return new UpdateResponse($payload);
    }

    /**
     * basic page setting for this section of the app
     * @param string $section page section (optional)
     * @param array $data any other data (optional)
     * @return array
     */
    private function pageSettings($section = '', $data = []) {

        $page = [
            'crumbs_special_class' => 'main-pages-crumbs',
            'page' => 'settings',
            'meta_title' => ' - ' . __('lang.settings'),
            'heading' => __('lang.settings'),
            'settingsmenu_general' => 'active',
        ];

        if ($section == 'clients') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.clients'),
                __('lang.custom_form_fields'),
            ];
        }

        if ($section == 'projects') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.projects'),
                __('lang.custom_form_fields'),
            ];
        }

        if ($section == 'tasks') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.tasks'),
                __('lang.custom_form_fields'),
            ];
        }

        if ($section == 'leads') {
            $page['crumbs'] = [
                __('lang.settings'),
                __('lang.leads'),
                __('lang.custom_form_fields'),
            ];
        }
        return $page;
    }

}
