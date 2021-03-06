<?php

class Event extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('username')) {
            redirect('Home/login', 'refresh');
        }
        $this->load->model('eventmodel');
        $this->load->model('functionsmodel');
        $this->load->model('coursemodel');
        $this->load->model('personmodel');
    }

    public function refresh($function = 'Home/Home') {
        redirect($function, 'refresh');
    }

    public function loadpage($data = null, $view = 'Home', $pagetitle = 'HOME | BCIPN', $page = array('includes/Header', 'includes/Navigation')) {
        $data['deleted_count'] = $this->functionsmodel->getDeletedDataCounts();
        $data['pagetitle'] = $pagetitle;
        for ($i = 0; $i < count($page); $i++) {
            $this->load->View($page[$i], $data);
        }
        $this->load->View($view, $data);
        $this->load->View('includes/Footer');
    }

    public function event() {
        $this->loadpage(null, 'Events', 'Add new Event | BCIPN');
    }

    public function createEvent() {
        $this->form_validation->set_rules('event_title', 'Event title ', 'required');
        if ($this->form_validation->run() == false) {
            $this->event();
        } else {
            $this->sendDataToModel();
        }
    }

    public function sendDataToModel() {

        $event_title = $this->input->post('event_title');
        /* course category is now event_type */
        $event_course_category = $this->input->post('event_course_category');
        /* course sub-category is now course */
        $event_course_subcategory = $this->input->post('event_course_subcategory');
        /* event level is now coverage level */
        $coverage_level = $this->input->post('coverage_level');
        $coverageLocation = $this->input->post('coverage_location');
        $event_year = $this->input->post('event_year');
        $event_start_date = $this->input->post('event_start_date');
        $event_end_date = $this->input->post('event_end_date');
        /* $event_implementedby = $this->input->post('event_implementedby'); */
        $event_venue = $this->input->post('event_venue');
        $event_address = $this->input->post('event_address');
        $event_country = $this->input->post('event_country');
        $organizer_identifier = $this->input->post('org_identifier');
        $longitude = $this->input->post('longitude');
        $latitude = $this->input->post('latitude');

        /* 888888888888888888888 start 88888888888888888888888 */
        //if implementing partners are checked
        $impl_partners = array();
        //If main organizers are checked follow this block
        $main_organizers = array();

        //  if ($organizer_identifier == 'main') {
        $k = 0;
        foreach ($_POST as $key => $value) {
            $array = explode('_', $key);
            if ($array[0] == 'mainorg') {

                $main_organizers[$k][0] = $array[1];
                $main_organizers[$k][1] = $this->input->post($key);
                // echo $this->input->post($key);
                $k++;
            }
        }
        //   } else if ($organizer_identifier == 'partner') {
        $k = 0;
        foreach ($_POST as $key => $value) {
            $array = explode('_', $key);
            if ($array[0] == 'implpartner') {

                $impl_partners[$k][0] = $array[1];
                $impl_partners[$k][1] = $this->input->post($key);
                // echo $this->input->post($key);
                $k++;
            }
        }
        //  }
        /* 88888888888888888888888 end 8888888888888888888888 */

        $date = date("Y-m-d H:i:s");
        $created_by = $this->session->userdata('username');

//
//        $identifier = $this->input->post('identifier');
//        $event_id;
//        switch ($identifier) {
//            case 'insert':
//                $event_id = NULL;
//                break;
//            case 'edit':
//                $event_id = $this->input->post('event_id');
//                echo $event_id."==";
//                break;
//        }
///

        $event_data_insert = array(
            'event_id' => NULL,
            'title' => $event_title,
            'course_cat_id' => $event_course_category,
            'course_subcat_id' => $event_course_subcategory,
            'coverage_level' => $coverage_level,
            'coverage_location' => $coverageLocation,
            'year' => $event_year,
            'start_date' => $event_start_date,
            'end_date' => $event_end_date,
            'venue' => $event_venue,
            'address' => $event_address,
            'country' => $event_country,
            'created_by' => $created_by,
            'created_date' => $date,
            'longitude' => $longitude,
            'latitude' => $latitude
        );
        $event_data_update = array(
            'title' => $event_title,
            'course_cat_id' => $event_course_category,
            'course_subcat_id' => $event_course_subcategory,
            'coverage_level' => $coverage_level,
            'coverage_location' => $coverageLocation,
            'year' => $event_year,
            'start_date' => $event_start_date,
            'end_date' => $event_end_date,
            'venue' => $event_venue,
            'address' => $event_address,
            'longitude' => $longitude,
            'latitude' => $latitude
        );

        $event_id = $this->testIfEventExists(array(
            'title' => $event_title,
            'course_cat_id' => $event_course_category,
            'course_subcat_id' => $event_course_subcategory,
            'year' => $event_year,
            'start_date' => $event_start_date,
            'end_date' => $event_end_date,
            'venue' => $event_venue,
            'address' => $event_address,
            'country' => $event_country,
                ));



        $data['title'] = $event_title;
//        $data['course'] = $this->coursemodel->getCourseName($event_course_category);
//        $data['subcourse'] = $this->coursemodel->getSubCourseName($event_course_subcategory);
//        $data['start_date'] = $event_start_date;
//        $data['end_date'] = $event_end_date;
//        $data['venue'] = $event_venue;
//        $data['address'] = $event_address;
//        $data['person_data'] = $this->personmodel->getPeople(0, 30);

        if ($event_id != "0") {
            //  if ($identifier != 'edit') {
            $data['course'] = $this->coursemodel->getCourseName($event_course_category);
            $data['subcourse'] = $this->coursemodel->getSubCourseName($event_course_subcategory);
            $data['start_date'] = $event_start_date;
            $data['end_date'] = $event_end_date;
            $data['venue'] = $event_venue;
            $data['address'] = $event_address;
            $data['person_data'] = $this->personmodel->getPeople(0, 30);
            $this->loadEventDetail($event_id);
            //  }
        } else {

            $event_id = $this->eventmodel->saveEventData($event_data_insert, $main_organizers, $impl_partners);

            if ($event_id != 0) {
                $data['event_id'] = $event_id;
                $data['event_title'] = $event_title;
                $this->loadpage($data, 'People', 'Add participants | BCIPN');
            } else {
                $this->loadpage();
            }
        }
    }

    function getCoverageLocation() {
        $coverage_level_id = $this->input->post('coverage_level');
        echo $this->eventmodel->getAllCoverageLocation($coverage_level_id);
    }

    function person_exists() {
        $fullname = $this->input->post('fullname');
        $dob_en = $this->input->post('dob_en');
        $dob_np = $this->input->post('dob_np');
        //replace /(slash) by -(hyphen)
        // $dob_np = str_replace("/","-",$dob_np);
        $mobile = $this->input->post('mobile');
        $phone = $this->input->post('phone');
        $person_data = $this->eventmodel->person_exists($fullname, $dob_en, $dob_np, $mobile, $phone);
        echo $person_data;
    }

    function addParticipant() {
        $data['event_id'] = $this->input->get('id', TRUE);
        $event_detail = $this->eventmodel->getEventDetail($this->input->get('id', TRUE));
        $data['event_title'] = $event_detail[1];
        $this->loadpage($data, 'people', 'Add participants | BCIPN');
    }

    public function updateEvent() {

        $event_id = $this->input->post('event_id');
        $event_title = $this->input->post('event_title');
        /* course category is now event_type */
        $event_course_category = $this->input->post('event_course_category');
        /* course sub-category is now course */
        $event_course_subcategory = $this->input->post('event_course_subcategory');
        /* event level is now coverage level */
        $coverage_level = $this->input->post('coverage_level');
        $coverageLocation = $this->input->post('coverage_location');
        $event_year = $this->input->post('event_year');
        $event_start_date = $this->input->post('event_start_date');
        $event_end_date = $this->input->post('event_end_date');
        /* $event_implementedby = $this->input->post('event_implementedby'); */
        $event_venue = $this->input->post('event_venue');
        $event_address = $this->input->post('event_address');
        $longitude = $this->input->post('longitude');
        $latitude = $this->input->post('latitude');

        //if implementing partners are checked
        $impl_partners = array();


        //If main organizers are checked follow this block
        $main_organizers = array();

        $k = 0;
        foreach ($_POST as $key => $value) {
            $array = explode('_', $key);
            if ($array[0] == 'mainorg') {

                $main_organizers[$k][0] = $array[1];
                $main_organizers[$k][1] = $this->input->post($key);
                // echo $this->input->post($key);
                $k++;
            }
        }

        $k = 0;
        foreach ($_POST as $key => $value) {
            $array = explode('_', $key);
            if ($array[0] == 'implpartner') {

                $impl_partners[$k][0] = $array[1];
                $impl_partners[$k][1] = $this->input->post($key);
                // echo $this->input->post($key);
                $k++;
            }
        }



        $event_data_update = array(
            'title' => $event_title,
            'course_cat_id' => $event_course_category,
            'course_subcat_id' => $event_course_subcategory,
            'coverage_level' => $coverage_level,
            'coverage_location' => $coverageLocation,
            'year' => $event_year,
            'start_date' => $event_start_date,
            'end_date' => $event_end_date,
            'venue' => $event_venue,
            'address' => $event_address,
            'longitude' => $longitude,
            'latitude' => $latitude
        );

        $this->eventmodel->updateEventData($event_data_update, $event_id, $main_organizers, $impl_partners);
        $this->viewEvent($event_id);
    }

    public function updateEventData($data, $event_id, $csparty_value) {
        $this->load->model('personmodel');
        $success = $this->eventmodel->updateEventData($data, $event_id, $csparty_value);
        $this->loadEventDetail($event_id);
    }

    public function testIfEventExists($data_array) {
        $success = $this->eventmodel->testIfEventExists($data_array);
        return $success;
    }

    public function deleteEvent() {
        $data['deleted_count'] = $this->functionsmodel->getDeletedDataCounts();
        $success = $this->eventmodel->deleteEvent($this->input->get('id', TRUE));
        $this->deleteParticipants($this->input->get('id', TRUE));
        $data['event_data'] = $this->eventmodel->getEvents(0, 30);
//        $this->load->View('includes/Header');
//        $this->load->View('includes/Navigation', $data);
//        $this->load->View('EventManagement', $data);
//        $this->load->View('includes/Footer');
        $this->loadpage($data, 'EventManagement', 'Manage events| BCIPN');
    }

    public function deleteParticipants($event_id) {
        //  $this->load->model('functionsmodel');
        $success = $this->eventmodel->deleteParticipants($event_id);
    }

//------------------------------------------------------------------------------
    public function editEvent() {
        $event_id = $this->input->get('id', TRUE);
        $eventDetail_array = $this->eventmodel->getEventDetail($event_id);

        /*
          $eventDetail_array[0] = $row->event_id;
          $eventDetail_array[1] = $row->title;
          $eventDetail_array[2] = $row->year;
          $eventDetail_array[3] = $row->course_cat_id;
          $eventDetail_array[4] = $row->course_subcat_id;
          $eventDetail_array[5] = $row->start_date;
          $eventDetail_array[6] = $row->end_date;
          $eventDetail_array[7] = $row->venue;
          $eventDetail_array[8] = $this->getCoverageLevelName($row->coverage_level);
          $eventDetail_array[9] = $row->coverage_location;
          $eventDetail_array[10] = $row->address;
          $eventDetail_array[11] = $row->country;
         *///----------------
        $data['event_id'] = $eventDetail_array[0];
        $data['title'] = $eventDetail_array[1];
        $data['event_year'] = $eventDetail_array[2];
        $data['course_cat_list'] = $this->coursemodel->getCourseData();
        $data['course_cat_id'] = $eventDetail_array[3];
        $data['course_subcat_list'] = $this->coursemodel->getSubCourseData($eventDetail_array[3]);
        $data['course_subcat_id'] = $eventDetail_array[4];
        $data['start_date'] = $eventDetail_array[5];
        $data['end_date'] = $eventDetail_array[6];
        $data['venue'] = $eventDetail_array[7];
        $data['level'] = $eventDetail_array[8];
        $data['location'] = $eventDetail_array[9];
        $data['address'] = $eventDetail_array[10];
        $data['country'] = $eventDetail_array[11];
        $data['coverage_level_id'] = $eventDetail_array[12];
        $data['longitude'] = $eventDetail_array[13];
        $data['latitude'] = $eventDetail_array[14];
        $locationstring = '';
        switch (strtoupper($data['level'])) {
            case 'MUNICIPALITY':case 'DISTRICT': case 'REGION': case 'VDC':
                $location = $this->eventmodel->getCoverageLocation($data['coverage_level_id']);
                for ($i = 0; $i < count($location); $i++) {
                    $selected = '';
                    if (trim($data['location']) == trim($location[$i][1])) {
                        $selected = 'selected';
                    }
                    $locationstring .= '<option value = "' . $location[$i][1] . '" ' . $selected . '>' . $location[$i][1] . '</option>';
                }
                break;
            default:
                $locationstring .= '<input id="coverage_location" type="text" value="' . $data['location'] . '" name="coverage_location" placeholder="Enter location..">';
                break;
        }
        $data['location'] = $locationstring;

        $content = "";
        $query = $this->coursemodel->getCourseResultSet();
        foreach ($query->result() as $row) {
            $selected = '';
            if ($data['course_cat_id'] == $row->course_cat_id) {
                $selected = 'selected';
            }
            $content .= '<option value="' . $row->course_cat_id . '" ' . $selected . '>' . $row->coursename . '</option>';
        }
        $data['CourseContent'] = $content;
        $data['coverage_level_array'] = $this->functionsmodel->getCoverageLevel();
        $data['organizer_array'] = $this->eventmodel->getAllOrganizers(); //contains a list of all organizers
        //------------------------------------------------------------------------
        $data['main_organizer_array'] = $this->eventmodel->getMainOrganizer($event_id); //contains a list of selected organizers
        $data['impl_partner_array'] = $this->eventmodel->getImplementingPartner($event_id);
        //------------------------------------------------------------------------
        $this->loadpage($data, 'EditEvent', 'Edit events| BCIPN');
    }

//--------------------------------------------------------------------------------------------------------------    

    public function budgetEntry() {
        $content = '';
        $event_id = $this->input->get('id', TRUE);
        $data['event_id'] = $event_id;
        $data['event_title'] = $this->eventmodel->getEventTitle($data['event_id']);
        $data['budget_currency'] = $this->eventmodel->getBudgetCurrency($data['event_id']);
        $currency = $this->eventmodel->getcurrency();
        $data['share'] = $this->eventmodel->getShare($event_id);
        $data['csparty_array'] = $this->functionsmodel->getALLCostSharingParties();
        $data['direct_cost_array'] = $this->eventmodel->getDirectCost($event_id);
        $data['inkind_contribution_array'] = $this->eventmodel->getInkindContribution($event_id);


        if ($currency != 0) {
            for ($i = 0; $i < count($currency); $i++) {
                $content.=' <option value="' . $currency[$i][0] . '"';
                if (isset($data['budget_currency']) && $data['budget_currency'] == $currency[$i][0]) {
                    $content .= ' selected ';
                }
                $content .='>' . $currency[$i][0] . '</option> ';
            }
            $data['currency'] = $content;
        }


        $this->loadpage($data, 'BudgetEntry', 'Budget entry | BCIPN');
    }

    public function saveBudget() {
        $csparty_value = array();
        $k = 0;
        foreach ($_POST as $key => $value) {
            $array = explode('_', $key);
            if ($array[0] == 'csparty') {

                $csparty_value[$k][0] = $array[1];
                $csparty_value[$k][1] = $this->input->post($key);
                $k++;
            } else {
                
            }
        }
        $event_id = $this->input->post('event_id');
        $total_direct_cost = $this->input->post('total_direct_cost');
        $staff_cost = $this->input->post('staff_cost');
        $travel_cost = $this->input->post('travel_cost');
        $unit = $this->input->post('currency_unit');

        $success = $this->eventmodel->saveBudget($event_id, $csparty_value, $total_direct_cost, $staff_cost, $travel_cost, $unit);

        $data['event_id'] = $event_id;
        $data['event_title'] = $this->eventmodel->getEventTitle($data['event_id']);
        $data['share'] = $this->eventmodel->getShare($event_id);
        $data['csparty_array'] = $this->functionsmodel->getALLCostSharingParties();
        $data['direct_cost_array'] = $this->eventmodel->getDirectCost($event_id);
        $data['inkind_contribution_array'] = $this->eventmodel->getInkindContribution($event_id);
        $data['budget_currency'] = $this->eventmodel->getBudgetCurrency($event_id);
        $currency = $this->eventmodel->getcurrency();
        if ($currency != 0) {
            $content = '';
            for ($i = 0; $i < count($currency); $i++) {
                $content .=' <option value="' . $currency[$i][0] . '"';
                if (isset($data['budget_currency']) && $data['budget_currency'] == $currency[$i][0]) {
                    $content .= ' selected ';
                }
                $content .='>' . $currency[$i][0] . '</option> ';
            }
            $data['currency'] = $content;
        }


        $this->loadpage($data, 'BudgetEntry', 'Budget entry | BCIPN');
    }

    function saveInkindContribution() {
        $event_id = $this->input->post('event_id');
        $level = $this->input->post('level');
        $description = $this->input->post('description');
        $pax = $this->input->post('pax');
        $rate = $this->input->post('rate');
        $hour = $this->input->post('hour');
        $inkind_contribution_array = array(
            'event_id' => $event_id,
            'level' => $level,
            'description' => $description,
            'pax' => $pax,
            'hour' => $hour,
            'rate' => $rate,
            'updated_by' => $this->session->userdata('username'),
            'updated_date' => date("Y-m-d H:i:s")
        );

        $inkind_id = $this->eventmodel->saveInkindContribution($inkind_contribution_array);
        if ($inkind_id == 0) {
            echo '0';
        } else {
            //if data is inserted successfully , return the insert_id as ajax response
            echo $inkind_id;
        }
    }

    function deleteInkindContribution() {
        $inkind_id = $this->input->post('inkind_id');
        $success = $this->eventmodel->deleteInkindContribution($inkind_id);
        echo $success;
    }

    public function viewEvent($id = 0) {
        //  $this->load->model('functionsmodel');
        if ($id == 0) {
            $event_id = $this->input->get('id', TRUE);
        } else {
            $event_id = $id;
        }
        $data = $this->loadEventDetail($event_id);
        $data['directcost_array'] = $this->eventmodel->getDirectCost($event_id);
        $data['inkind_contribution_array'] = $this->eventmodel->getInkindContribution($event_id);
        $data['main_organizer_array'] = $this->eventmodel->getMainOrganizer($event_id);
        $data['impl_partner_array'] = $this->eventmodel->getImplementingPartner($event_id);
        $data['unit'] = $this->eventmodel->getBudgetCurrency($event_id);
        $this->loadpage($data, 'EventDetail', 'Event Details | BCIPN');
    }

    public function loadEventDetail($event_id) {
        $event_detail_array = $this->eventmodel->getEventDetail($event_id);
        $data['share'] = $this->eventmodel->getShare($event_id);
        $data['participants_array'] = $this->eventmodel->getAllParticipants($event_id);

        $data['event_id'] = $event_detail_array[0];
        $data['title'] = $event_detail_array[1];
        $data['year'] = $event_detail_array[2];
        $data['course'] = $this->coursemodel->getCourseName($event_detail_array[3]);
        $data['subcourse'] = $this->coursemodel->getSubCourseName($event_detail_array[4]);
        $data['start_date'] = $event_detail_array[5];
        $data['end_date'] = $event_detail_array[6];
        $data['venue'] = $event_detail_array[7];
        $data['level'] = $event_detail_array[8];
        $data['location'] = $event_detail_array[9];
        $data['address'] = $event_detail_array[10];
        $data['country'] = $event_detail_array[11];
        //coverage level is 12
        $data['longitude'] = $event_detail_array[13];
        $data['latitude'] = $event_detail_array[14];
        // $data['cost_sharing'] = $event_detail_array[12];

        $data['deleted_count'] = $this->functionsmodel->getDeletedDataCounts();
//        $this->load->View('includes/Header');
//        $this->load->View('includes/Navigation', $data);
//        $this->load->View('EventDetail', $data);
//        $this->load->View('includes/Footer');
        return $data;
    }

    /* just for the ajax call */

    public function grabSubCourseData_async() {
        $course_id = $this->input->post('course_cat_id');

        //  $this->load->model('eventmodel');
        $success = $this->eventmodel->getSubCourseData($course_id);

        if ($success == "no") {
            echo "no";
        } else {
            echo $success;
        }
    }

    public function addInstructor_async() {
        $personId = $this->input->post('person_id');
        $eventId = $this->input->post('event_id');
        $event_instructor = $this->input->post('event_inst');
        $person = $this->personmodel->getPersonDetail($personId);
        $person_age = $person[4];
        // $this->load->model('functionsmodel');
        $success = $this->functionsmodel->addInstructor($personId, $eventId, $event_instructor, $person_age);
        if ($success == "1") {
            echo "yes";
        } else {
            echo "no";
        }
    }

    public function addCandidate_async() {
        $personId = $this->input->post('person_id');
        $eventId = $this->input->post('event_id');
        // $this->load->model('functionsmodel');
        $success = $this->functionsmodel->addCandidate_async($personId, $eventId);
        if ($success == "1") {
            echo "yes";
        } else {
            echo "no";
        }
    }

    public function deleteCandidate_async() {
        if (is_int(intval(($this->input->post('participation_id')))) && $this->input->post('participation_id') > 0) {
            $participation_id = $this->input->post('participation_id');
            $success = $this->functionsmodel->deleteCandidate_async(0, 0, $participation_id);
            if ($success == "1") {
                echo "yes";
            } else {
                echo "no";
            }
        } else {
            $personId = $this->input->post('person_id');
            $eventId = $this->input->post('event_id');
            //  $this->load->model('functionsmodel');
            $success = $this->functionsmodel->deleteCandidate_async($personId, $eventId);
            if ($success == "1") {
                echo "yes";
            } else {
                echo "no";
            }
        }
    }

    public function search_person_async() {
        $string = $this->input->post('search_string');
        $identifier = $this->input->post('identifier');
        //  $this->load->model('functionsmodel');
        $success = $this->functionsmodel->searchPerson_async($string, $identifier);
        echo $success;
    }

    public function searchEvent() {
        $item_per_page = 30;
        $page_no = 1;
        $string = $this->input->post('event_searchString');

        $data['search_string'] = $string;
        $deleted = 0; // show data which are not deleted
        $data['event_data'] = $this->eventmodel->getEvents(($page_no - 1) * $item_per_page, ($page_no * $item_per_page), $deleted, $string);
        $data['total_pages'] = $this->eventmodel->getEvent_pagination_totalpage();
        $data['current_page'] = $page_no;
        $data['deleted_count'] = $this->functionsmodel->getDeletedDataCounts();

//        $this->load->View('includes/Header');
//        $this->load->View('includes/Navigation', $data);
//        $this->load->View('eventManagement', $data);
//        $this->load->View('includes/Footer');
        $this->loadpage($data, 'eventManagement', 'Manage event | BCIPN');
    }

    public function event_pagination() {
        $item_per_page = 30;
        $page_no = $this->input->get('page', TRUE);
        $search_string = $this->input->get('search_string', TRUE);

        $pages = $this->eventmodel->getTotalPages_event($item_per_page);

        $data['total_pages'] = $pages;
        $data['current_page'] = $page_no;
        $deleted = 0;
        $data['event_data'] = $this->eventmodel->getEvents(($page_no - 1) * $item_per_page, ($page_no * $item_per_page), $deleted, $search_string);
        $data['search_string'] = $search_string;
        $data['deleted_count'] = $this->functionsmodel->getDeletedDataCounts();
//        $this->load->View('includes/Header');
//        $this->load->View('includes/Navigation', $data);
//        $this->load->View('eventManagement', $data);
//        $this->load->View('includes/Footer');
        $this->loadpage($data, 'eventManagement', 'Manage event | BCIPN');
    }

}

?>
