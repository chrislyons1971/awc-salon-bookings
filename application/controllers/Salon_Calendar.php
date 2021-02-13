<?php

class Salon_Calendar extends CI_Controller{
    public function index($year = false, $month = false, $day = false, $cs_id = false, $booking = false){
        // check user is logged in
        if(!$this->session->userdata('logged_in')){
            redirect('users/login');
        }
        date_default_timezone_set('Europe/London');
        $class_sessions = $this->class_model->get_class_sessions();

        if($day === false){
            $this->calendar_view($year, $month, $day, $class_sessions);
        } else if ($cs_id === false){
            $this->day_view($year, $month, $day, $class_sessions);
        } else if ($booking === false){
            if ($cs_id != 'add') $this->hour_view($year, $month, $day, $cs_id, $class_sessions);
            else $this->add_class_session($year, $month, $day, $class_sessions);
        } else {
            if ($booking != 'add') $this->booking_view($year, $month, $day, $cs_id, $booking, $class_sessions);
            else $this->add_salon_session($year, $month, $day, $cs_id, $class_sessions);
        }
    }

    public function calendar_view($year = false, $month = false, $day = false, $class_sessions){
        // current month if no month entered
        if($year === false || $month === false /*|| !is_int($year) || !is_int($month)*/) {
            $date = $this->date_format( date('Y-m-d H:i:s', time()) );
            redirect('salon_calendar/'.$date['year'].'/'.$date['month']);
        } else $date = array(
            'year'  => $year,
            'month' => $month,
            'day'   => 0
        );

        //redirect('users/logout');
        $data['title'] = 'Calendar';
        $data['year'] = $year;
        $data['month'] = $month;

        $data['calendar_data'] = array();
        // go thru all datetimes of this month and fill
        foreach($class_sessions as $class_session) { 
            $cs_date = $this->date_format($class_session['Start_Time']);

            if ($cs_date['month'] == $date['month'] && $cs_date['year'] == $date['year']) {
                // shows data in table
                $data['calendar_data'][$cs_date['day']] = '';
            }
        }

        $prefs = array(
            'start_day'         => 'monday',
            'show_next_prev'    => true,
            'next_prev_url'     => base_url().'salon_calendar',
            'day_type'          => 'short',
            // 'show_other_days'   => true,
            // Creating template for table
            'template'          => '
            {table_open} <table cellpadding="1" cellspacing="2" class="table text-center calendar" > {/table_open}

                {heading_row_start} <tr class="table-primary"> {/heading_row_start}

                    {heading_previous_cell} <th class=""><div style="padding-top: 1rem; padding-bottom: 1rem;"><a href="{previous_url}">&lt;&lt;</a></div></th> {/heading_previous_cell}
                    {heading_title_cell} <th colspan="{colspan}"><div style="padding-top: 1rem; padding-bottom: 1rem;">{heading}</div></th> {/heading_title_cell}
                    {heading_next_cell} <th class=""><div style="padding-top: 1rem; padding-bottom: 1rem;"><a href="{next_url}">&gt;&gt;</a></div></th> {/heading_next_cell}

                {heading_row_end} </tr> {/heading_row_end}


                //Deciding where to week row start
                {week_row_start} <tr class="table-dark" style="color:black"> {/week_row_start}
                
                    //Deciding  week day cell and  week days
                    {week_day_cell} <th scope="col"><div style="padding-top: 1rem; padding-bottom: 1rem;">{week_day}</div></th> {/week_day_cell}
                
                //week row end
                {week_row_end} </tr> {/week_row_end}


                {cal_row_start} <tr class="table table-light"> {/cal_row_start}
                    {cal_cell_start} <td> {/cal_cell_start}

                        {cal_cell_content} <a href="'.$month.'/{day}" class="table-primary">{day}</a> {/cal_cell_content}
                        {cal_cell_content_today} <a href="'.$month.'/{day}" class="table-success">{day}</a> {/cal_cell_content_today}

                        {cal_cell_no_content} <a href="'.$month.'/{day}" class="table-none">{day}</a> {/cal_cell_no_content}
                        {cal_cell_no_content_today} <a href="'.$month.'/{day}" class="table-info">{day}</a> {/cal_cell_no_content_today}

                        {cal_cell_blank} <div style="padding-top: 1rem; padding-bottom: 1rem;"><br></div> {/cal_cell_blank}

                    {cal_cell_end} </td> {/cal_cell_end}
                {cal_row_end} </tr> {/cal_row_end}

            {table_close}</table>{/table_close}
            '
        );

        // Loading calendar library and configuring table template
        $this->load->library('calendar', $prefs);

        $this->load->view('templates/header');
        $this->load->view('salon_calendar/index', $data);
        $this->load->view('templates/footer');
    }

    public function day_view($year, $month, $day, $class_sessions){

        $data['salons'] = $this->salon_model->get_salons();
        $data['workspaces'] = $this->salon_model->get_workspaces();
        $data['student_workspaces'] = $this->salon_model->get_student_workspaces();

        $date = strtotime($day.'-'.$month.'-'.$year.' 00:00:00');
        $data['day'] = $day;
        $data['year'] = $year;
        $data['month'] = $month;
        $data['fulldate'] = date('l jS F Y', $date);
        $data['is_today'] = $data['fulldate'] ==  date('l jS F Y', time());
        //$data['class_sessions'] = $class_sessions;
        $data['sessions'] = array();
        $data['sessions_room'] = array();

        for ($i=8; $i<22; $i++) { 
            foreach($class_sessions as $class_session) { 
                $cs_date = $this->date_format($class_session['Start_Time']);
                $cs_starttime = $this->time_format($class_session['Start_Time']);
                $cs_endtime = $this->time_format($class_session['End_Time']);

                // Filter class sessions to day
                if ($cs_date['day'] == $day && $cs_date['month'] == $month && $cs_date['year'] == $year
                        && $i >= $cs_starttime['hrs'] && $i < $cs_endtime['hrs'] ) {
                    $data['sessions'][$i] = $class_session;
                    
                    // Get salon room for each class session
                    foreach ($data['student_workspaces'] as $s_workspace)
                      if ($s_workspace['Class_Session_ID'] == $class_session['Class_Session_ID'] )
                        foreach ($data['workspaces'] as $workspace) 
                          if ($workspace['Workspace_ID'] == $s_workspace['Workspace_ID'])
                            foreach ($data['salons'] as $salon) 
                              if ($workspace['Salon_Room_Name'] == $salon['Salon_Room_Name'])
                                $data['sessions_room'][$i] = $salon['Salon_Room_Name'];
                }
            }
        }

        $this->load->view('templates/header');
        $this->load->view('salon_calendar/day', $data);
        $this->load->view('templates/footer');
    
    }

    public function hour_view($year, $month, $day, $cs_id, $class_sessions){

        $data['class_sessions'] = $class_sessions;
        $data['salons'] = $this->salon_model->get_salons();
        $data['workspaces'] = $this->salon_model->get_workspaces();
        $data['student_workspaces'] = $this->salon_model->get_student_workspaces();
        $data['bookings'] = $this->user_model->get_bookings();
        $data['students'] = $this->class_model->get_students();
        $date = strtotime($day.'-'.$month.'-'.$year.' 00:00:00');

        // get hour
        foreach ($class_sessions as $class_session) {
            $d = $this->date_format($class_session['Start_Time']);
            $d = strtotime($d['day'].'-'.$d['month'].'-'.$d['year'].' 00:00:00');
            if ( $d == $date ) $h = $this->time_format($class_session['Start_Time']);
         //   if ( $d == $date ) echo ''.$h['hrs'].'-'.$h['mins'];
        }
        $data['hour'] = $h['hrs'];
        $data['day'] = $day;
        $data['year'] = $year;
        $data['month'] = $month;
        //$data['hour'] = $this->time_format();
        $data['fulldate'] = date('l jS F Y', $date);
        $data['is_today'] = $data['fulldate'] ==  date('l jS F Y', time());
        $data['cs_id'] = $cs_id;
        $data['type'] = '';
        $data['salon_room'] = '';

        // find salon type from cs id
        foreach ($data['student_workspaces'] as $student_workspace)
          if ($student_workspace['Class_Session_ID'] == $cs_id)
            foreach ($data['workspaces'] as $workspace)
              if ($student_workspace['Workspace_ID'] == $workspace['Workspace_ID']){
                $data['type'] = $workspace['Workspace_Type'];

                foreach ($data['salons'] as $salon)
                  if ($workspace['Salon_Room_Name'] == $salon['Salon_Room_Name'])
                    $data['salon_room'] = $salon['Salon_Room_Name'];
              }


        $data['sessions'] = array();
        $data['sessions_workspace'] = array();

        for ($i=0; $i<60; $i+=15) { 

            foreach($data['bookings'] as $booking) { 
                $booking_date = $this->date_format($booking['Salon_Session_Start_Time']);
                $booking_time = $this->time_format($booking['Salon_Session_Start_Time']);
               // echo $booking['Salon_Session_Start_Time'];

                foreach ($data['student_workspaces'] as $student_workspace) {

                    // filter bookings to hour, mins and workspace
                    if ($booking_date['day'] == $day && $booking_date['month'] == $month && $booking_date['year'] == $year
                            && $data['hour'] == $booking_time['hrs'] &&  $i == $booking_time['mins']
                            && $student_workspace['Student_Workspace_ID'] == $booking['Student_Workspace_ID']) {

                        $data['sessions'][$i] = $student_workspace['Student_ID'];

                        // get data for booking to view
                        foreach ($data['workspaces'] as $workspace) 
                            if ($workspace['Workspace_ID'] == $student_workspace['Workspace_ID'])
                                $data['sessions_workspace'][$i] = array(
                                    'workspace'     => $workspace['Workspace_ID'],
                                    'timeslot'    => $i,
                                    'student'     => $student_workspace['Student_ID'],
                                    'booking'     => $booking['Booking_ID']
                                );
                    }
                }
            }
        }

        $this->load->view('templates/header');
        $this->load->view('salon_calendar/hour', $data);
        $this->load->view('templates/footer');
    
    }

    public function booking_view($year, $month, $day, $cs_id, $booking, $class_sessions){

        $data['class_sessions'] = $class_sessions;
        $data['booking_id'] = $booking;
        $data['salons'] = $this->salon_model->get_salons();
        $data['workspaces'] = $this->salon_model->get_workspaces();
        $data['student_workspaces'] = $this->salon_model->get_student_workspaces();
        $data['bookings'] = $this->user_model->get_bookings();
        $data['students'] = $this->class_model->get_students();
        $date = strtotime($day.'-'.$month.'-'.$year.' 00:00:00');

        // get hour
        foreach ($class_sessions as $class_session) {
            $d = $this->date_format($class_session['Start_Time']);
            $d = strtotime($d['day'].'-'.$d['month'].'-'.$d['year'].' 00:00:00');
            if ( $d == $date ) $h = $this->time_format($class_session['Start_Time']);
        }
        $data['hour'] = $h['hrs'];
        $data['day'] = $day;
        $data['year'] = $year;
        $data['month'] = $month;
        //$data['hour'] = $this->time_format();
        $data['fulldate'] = date('l jS F Y', $date);
        $data['is_today'] = $data['fulldate'] ==  date('l jS F Y', time());
        $data['cs_id'] = $cs_id;
        $data['type'] = '';

        // find salon type from cs id
        foreach ($data['student_workspaces'] as $student_workspace)
            if ($student_workspace['Class_Session_ID'] == $cs_id)
                foreach ($data['workspaces'] as $workspace)
                    if ($student_workspace['Workspace_ID'] == $workspace['Workspace_ID'])
                        $data['type'] = $workspace['Workspace_Type'];

        $data['sessions'] = array();
        $data['booking_data'] = array();


        foreach($data['bookings'] as $booking) { 
            $booking_date = $this->date_format($booking['Salon_Session_Start_Time']);
            $booking_time = $this->time_format($booking['Salon_Session_Start_Time']);
            if($booking_time['mins'] == 0) $booking_time['mins'] == '00';

            foreach ($data['student_workspaces'] as $student_workspace) {
              if ($student_workspace['Student_Workspace_ID'] == $booking['Student_Workspace_ID']){
                foreach ($data['workspaces'] as $workspace)
                  if ($workspace['Workspace_ID'] == $student_workspace['Workspace_ID'])
                    foreach($data['students'] as $student)
                      if($student['Student_ID'] == $student_workspace['Student_ID'])
                        $data['booking_data'] = array(
                            'workspace'   => $workspace['Workspace_ID'],
                            'timeslot'    => $booking_time['hrs'].':'.$booking_time['mins'],
                            'student'     => $student['Forename'].' '.$student['Surname'],
                            'booking'     => $booking['Booking_ID'],
                            'client'      => $booking['Customer_Username']
                        );
                }
            }
        }

        $this->load->view('templates/header');
        $this->load->view('salon_calendar/booking', $data);
        $this->load->view('templates/footer');
    
    }

    public function add_salon_session($year, $month, $day, $cs_id, $class_sessions){
        die("add salon session form");
    }

    public function add_class_session($year, $month, $day, $class_sessions){
        die("add class session form");
    }

    public function date_format($datetime){
        $day = (int) substr($datetime, 8, 2);
        $month = (int) substr($datetime, 5, 2);
        $year = (int) substr($datetime, 0, 4);
        return array(
            'day'   => $day,
            'month' => $month,
            'year'  => $year
        );
    }

    public function time_format($datetime){
        $secs = (int) substr($datetime, 17, 2);
        $mins = (int) substr($datetime, 14, 2);
        $hrs = (int) substr($datetime, 11, 2);

        return array(
            'hrs'   => $hrs,
            'mins'  => $mins,
            'secs'  => $secs
        );
    }

    public function check_valid_date_format($date){
        $date_array = $this->date_format($date);
        return checkdate(
            $time_array['day'],
            $time_array['month'],
            $time_array['year']
        );
    }

}